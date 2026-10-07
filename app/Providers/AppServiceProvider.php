<?php

namespace App\Providers;

use App\Domains\Accounts\Listeners\CreateNotificationPreferencesForNewUser;
use App\Domains\Commerce\Listeners\MergeGuestCart;
use App\Domains\Content\Events\ResourceVersionUploaded;
use App\Domains\Content\Listeners\QueueResourcePreviewGeneration;
use App\Domains\Membership\Contracts\MembershipChecker;
use App\Domains\Membership\Services\NullMembershipChecker;
use App\Domains\Payments\Contracts\PaymentGateway;
use App\Domains\Payments\Gateways\FakeGateway;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(MembershipChecker::class, NullMembershipChecker::class);
        $this->app->bind(PaymentGateway::class, FakeGateway::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->configureEventListeners();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        Gate::before(fn (User $user): ?true => $user->hasRole('super-admin') ? true : null);

        Model::shouldBeStrict(! app()->isProduction());

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }

    /**
     * Register listeners for domain folders, which Laravel's event
     * auto-discovery does not scan (it only looks in app/Listeners).
     */
    protected function configureEventListeners(): void
    {
        Event::listen(Registered::class, CreateNotificationPreferencesForNewUser::class);
        Event::listen(Login::class, MergeGuestCart::class);
        Event::listen(ResourceVersionUploaded::class, QueueResourcePreviewGeneration::class);
    }
}
