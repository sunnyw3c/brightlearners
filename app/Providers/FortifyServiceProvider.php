<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureActions();
        $this->configureViews();
        $this->configureRateLimiting();
        $this->configureAdditionalRateLimiterMiddleware();
    }

    /**
     * Configure Fortify actions.
     */
    private function configureActions(): void
    {
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::createUsersUsing(CreateNewUser::class);

        Fortify::authenticateUsing(function (Request $request): ?User {
            $username = (string) $request->input(Fortify::username());

            if (config('fortify.lowercase_usernames')) {
                $username = Str::lower($username);
            }

            $user = User::query()->where(Fortify::username(), $username)->first();

            if (! $user || ! Hash::check((string) $request->input('password'), $user->password)) {
                return null;
            }

            if ($user->isSuspended()) {
                throw ValidationException::withMessages([
                    Fortify::username() => __('This account has been suspended.'),
                ]);
            }

            return $user;
        });
    }

    /**
     * Configure Fortify views.
     */
    private function configureViews(): void
    {
        Fortify::loginView(fn (Request $request) => Inertia::render('auth/login', [
            'canResetPassword' => Features::enabled(Features::resetPasswords()),
            'status' => $request->session()->get('status'),
        ]));

        Fortify::resetPasswordView(fn (Request $request) => Inertia::render('auth/reset-password', [
            'email' => $request->email,
            'token' => $request->route('token'),
            'passwordRules' => Password::defaults()->toPasswordRulesString(),
        ]));

        Fortify::requestPasswordResetLinkView(fn (Request $request) => Inertia::render('auth/forgot-password', [
            'status' => $request->session()->get('status'),
        ]));

        Fortify::verifyEmailView(fn (Request $request) => Inertia::render('auth/verify-email', [
            'status' => $request->session()->get('status'),
        ]));

        Fortify::registerView(fn () => Inertia::render('auth/register', [
            'passwordRules' => Password::defaults()->toPasswordRulesString(),
        ]));

        Fortify::twoFactorChallengeView(fn () => Inertia::render('auth/two-factor-challenge'));

        Fortify::confirmPasswordView(fn () => Inertia::render('auth/confirm-password'));
    }

    /**
     * Configure rate limiting.
     */
    private function configureRateLimiting(): void
    {
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('passkeys', function (Request $request) {
            return Limit::perMinute(10)->by(
                ($request->input('credential.id') ?: $request->session()->getId()).'|'.$request->ip(),
            );
        });

        RateLimiter::for('registration', function (Request $request) {
            $config = config('account.rate_limits.registration');

            return Limit::perMinutes($config['decay_minutes'], $config['max_attempts'])
                ->by(Str::lower((string) $request->input('email')).'|'.$request->ip());
        });

        RateLimiter::for('password-reset', function (Request $request) {
            $config = config('account.rate_limits.password_reset');

            return Limit::perMinutes($config['decay_minutes'], $config['max_attempts'])
                ->by(Str::lower((string) $request->input('email')).'|'.$request->ip());
        });
    }

    /**
     * Fortify does not expose configurable limiters for registration or
     * password-reset requests (only login, two-factor and passkeys). Web
     * and console routes load lazily, so the named routes do not exist
     * during this provider's boot(); attach the throttle middleware once
     * a route is matched for the current request instead, before the
     * pipeline gathers that route's middleware.
     */
    private function configureAdditionalRateLimiterMiddleware(): void
    {
        $additionalLimiters = [
            'register.store' => 'throttle:registration',
            'password.email' => 'throttle:password-reset',
        ];

        Event::listen(RouteMatched::class, function (RouteMatched $event) use ($additionalLimiters): void {
            $limiter = $additionalLimiters[$event->route->getName()] ?? null;

            if ($limiter !== null) {
                $event->route->middleware($limiter);
            }
        });
    }
}
