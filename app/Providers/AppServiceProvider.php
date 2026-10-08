<?php

namespace App\Providers;

use App\Domains\Access\Events\ResourceDownloaded;
use App\Domains\Access\Listeners\GrantEntitlementsOnOrderPaid;
use App\Domains\Access\Listeners\RecordResourceDownload;
use App\Domains\Access\Listeners\RevokeEntitlementsOnRefund;
use App\Domains\Accounts\Listeners\CreateNotificationPreferencesForNewUser;
use App\Domains\Commerce\Listeners\MergeGuestCartOnLogin;
use App\Domains\Content\Events\ResourceVersionUploaded;
use App\Domains\Content\Listeners\QueueResourcePreviewGeneration;
use App\Domains\Membership\Contracts\MembershipChecker;
use App\Domains\Membership\Services\DefaultMembershipChecker;
use App\Domains\Payments\Contracts\PaymentGateway;
use App\Domains\Payments\Events\OrderPaid;
use App\Domains\Payments\Events\RefundCompleted;
use App\Domains\Payments\Gateways\RazorpayGateway;
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
        $this->app->bind(MembershipChecker::class, DefaultMembershipChecker::class);
        $this->app->bind(PaymentGateway::class, RazorpayGateway::class);
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
        Event::listen(ResourceVersionUploaded::class, QueueResourcePreviewGeneration::class);
        Event::listen(Login::class, MergeGuestCartOnLogin::class);
        Event::listen(OrderPaid::class, GrantEntitlementsOnOrderPaid::class);
        Event::listen(RefundCompleted::class, RevokeEntitlementsOnRefund::class);
        Event::listen(ResourceDownloaded::class, RecordResourceDownload::class);
    }
}
