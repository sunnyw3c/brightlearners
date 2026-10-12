<?php

namespace App\Providers;

use App\Domains\Access\Events\ResourceDownloaded;
use App\Domains\Access\Listeners\GrantEntitlementsOnOrderPaid;
use App\Domains\Access\Listeners\RecordResourceDownload;
use App\Domains\Access\Listeners\RevokeEntitlementsOnRefund;
use App\Domains\Accounts\Listeners\CreateNotificationPreferencesForNewUser;
use App\Domains\Analytics\Listeners\TrackCartConvertedToOrder;
use App\Domains\Analytics\Listeners\TrackMembershipActivated;
use App\Domains\Analytics\Listeners\TrackOrderPaid;
use App\Domains\Analytics\Listeners\TrackResourceDownloaded;
use App\Domains\Analytics\Listeners\TrackResourceProgress;
use App\Domains\Catalog\Models\Product;
use App\Domains\Commerce\Events\CartConvertedToOrder;
use App\Domains\Commerce\Listeners\MergeGuestCartOnLogin;
use App\Domains\Content\Events\MaterialCorrectionPublished;
use App\Domains\Content\Events\ResourcePublished;
use App\Domains\Content\Events\ResourceVersionUploaded;
use App\Domains\Content\Listeners\QueueResourcePreviewGeneration;
use App\Domains\Content\Listeners\SendCorrectionNoticeOnPublished;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Curriculum\Listeners\InvalidateCurriculumTreeCache;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Curriculum\Models\Skill;
use App\Domains\Curriculum\Models\Subject;
use App\Domains\Curriculum\Models\Topic;
use App\Domains\Curriculum\Queries\CurriculumTree;
use App\Domains\Growth\Models\Article;
use App\Domains\Growth\Observers\SlugRedirectObserver;
use App\Domains\Learning\Events\ResourceCompleted;
use App\Domains\Learning\Events\ResourceOpened;
use App\Domains\Learning\Listeners\RecordProgressOnDownload;
use App\Domains\Membership\Contracts\MembershipChecker;
use App\Domains\Membership\Events\LearningMonthPublished;
use App\Domains\Membership\Events\LearningWeekReleased;
use App\Domains\Membership\Events\MembershipActivated;
use App\Domains\Membership\Events\MembershipExpiring;
use App\Domains\Membership\Listeners\ActivateMembershipOnOrderPaid;
use App\Domains\Membership\Listeners\SendLearningNotificationOnWeekReleased;
use App\Domains\Membership\Listeners\SendMembershipExpiringNotice;
use App\Domains\Membership\Listeners\SendMonthlyCheckNotification;
use App\Domains\Membership\Services\DefaultMembershipChecker;
use App\Domains\Payments\Contracts\PaymentGateway;
use App\Domains\Payments\Events\OrderPaid;
use App\Domains\Payments\Events\RefundCompleted;
use App\Domains\Payments\Gateways\RazorpayGateway;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
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
        $this->configureModelObservers();
        $this->configureRateLimiting();
    }

    protected function configureModelObservers(): void
    {
        Article::observe(SlugRedirectObserver::class);
        Product::observe(SlugRedirectObserver::class);
        LearningResource::observe(SlugRedirectObserver::class);

        $flushCurriculum = static function (): void {
            CurriculumTree::flushCache();
        };

        SchoolClass::saved($flushCurriculum);
        SchoolClass::deleted($flushCurriculum);
        Subject::saved($flushCurriculum);
        Subject::deleted($flushCurriculum);
        Topic::saved($flushCurriculum);
        Topic::deleted($flushCurriculum);
        Skill::saved($flushCurriculum);
        Skill::deleted($flushCurriculum);
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        if (app()->isProduction()) {
            URL::forceScheme('https');
        }

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
        Event::listen(OrderPaid::class, ActivateMembershipOnOrderPaid::class);
        Event::listen(RefundCompleted::class, RevokeEntitlementsOnRefund::class);
        Event::listen(ResourceDownloaded::class, RecordResourceDownload::class);
        Event::listen(ResourceDownloaded::class, RecordProgressOnDownload::class);
        Event::listen(MaterialCorrectionPublished::class, SendCorrectionNoticeOnPublished::class);
        Event::listen(MembershipExpiring::class, SendMembershipExpiringNotice::class);
        Event::listen(LearningWeekReleased::class, SendLearningNotificationOnWeekReleased::class);
        Event::listen(LearningMonthPublished::class, SendMonthlyCheckNotification::class);
        Event::listen(ResourcePublished::class, InvalidateCurriculumTreeCache::class);

        // Analytics domain event listeners (Phase 13)
        Event::listen(OrderPaid::class, TrackOrderPaid::class);
        Event::listen(MembershipActivated::class, TrackMembershipActivated::class);
        Event::listen(ResourceDownloaded::class, TrackResourceDownloaded::class);
        Event::listen(ResourceOpened::class, [TrackResourceProgress::class, 'handleOpened']);
        Event::listen(ResourceCompleted::class, [TrackResourceProgress::class, 'handleCompleted']);
        Event::listen(CartConvertedToOrder::class, TrackCartConvertedToOrder::class);
    }

    /**
     * Configure named application rate limiters.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('search', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('coupon', function (Request $request) {
            return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('downloads', function (Request $request) {
            return Limit::perMinute(30)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('checkout', function (Request $request) {
            return Limit::perMinute(15)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('events', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: ($request->cookie('bl_anon_id') ?: $request->ip()));
        });
    }
}
