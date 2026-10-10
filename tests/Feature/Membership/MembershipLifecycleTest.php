<?php

use App\Domains\Access\Services\AccessService;
use App\Domains\Accounts\Models\LearningProfile;
use App\Domains\Catalog\Enums\ProductType;
use App\Domains\Catalog\Models\Product;
use App\Domains\Commerce\Models\Order;
use App\Domains\Commerce\Models\OrderItem;
use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourceVersion;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Membership\Enums\SubscriptionEventType;
use App\Domains\Membership\Enums\SubscriptionStatus;
use App\Domains\Membership\Events\MembershipExpired;
use App\Domains\Membership\Events\MembershipExpiring;
use App\Domains\Membership\Models\LearningItem;
use App\Domains\Membership\Models\LearningProgram;
use App\Domains\Membership\Models\LearningWeek;
use App\Domains\Membership\Models\Subscription;
use App\Domains\Membership\Models\SubscriptionEvent;
use App\Domains\Membership\Models\SubscriptionPlan;
use App\Domains\Payments\Events\OrderPaid;
use App\Domains\Payments\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

uses(RefreshDatabase::class);

test('Full 90-day membership lifecycle: buy, activate, 4 releases, expiring notices, and expire', function () {
    Event::fake([MembershipExpiring::class, MembershipExpired::class]);

    // Start on Day 0
    Carbon::setTestNow('2026-06-01 10:00:00');

    $class1 = SchoolClass::factory()->create(['name' => 'Class 1', 'sort_order' => 1]);
    $class2 = SchoolClass::factory()->create(['name' => 'Class 2', 'sort_order' => 2]);

    $user = User::factory()->create();
    $child1 = LearningProfile::factory()->create(['user_id' => $user->id, 'class_id' => $class1->id]);

    $nonMember = User::factory()->create();
    $nonMemberChild = LearningProfile::factory()->create(['user_id' => $nonMember->id, 'class_id' => $class1->id]);

    // 1. Buy Pilot Membership
    $plan = SubscriptionPlan::factory()->pilot()->create(['duration_days' => 90]);
    $membershipProduct = Product::factory()->active()->create([
        'type' => ProductType::Membership,
        'subscription_plan_id' => $plan->id,
    ]);

    // Also user buys a permanent standalone product
    $permanentResource = LearningResource::factory()->create(['status' => ResourceStatus::Published]);
    ResourceVersion::factory()->create(['resource_id' => $permanentResource->id, 'is_current' => true]);
    $permanentProduct = Product::factory()->active()->create();
    $permanentProduct->resources()->attach($permanentResource->id);

    $order = Order::factory()->create(['user_id' => $user->id, 'paid_at' => now()]);
    OrderItem::factory()->create(['order_id' => $order->id, 'product_id' => $membershipProduct->id]);
    OrderItem::factory()->create(['order_id' => $order->id, 'product_id' => $permanentProduct->id]);
    $payment = Payment::factory()->create(['order_id' => $order->id]);

    // 2. Activate via OrderPaid event
    OrderPaid::dispatch($order, $payment);

    $subscription = Subscription::where('user_id', $user->id)->first();
    expect($subscription)->not->toBeNull();
    expect($subscription->status)->toBe(SubscriptionStatus::Active);
    expect($subscription->starts_at->toDateTimeString())->toBe('2026-06-01 10:00:00');
    expect($subscription->ends_at->toDateTimeString())->toBe('2026-08-30 10:00:00');

    // 3. Set up Month 6 Learning Programs and 4 weeks
    $programClass1 = LearningProgram::factory()->published()->create([
        'class_id' => $class1->id,
        'month' => 6,
        'year' => 2026,
    ]);

    // Create 4 weeks of resources for Class 1
    $weeks = [];
    $resources = [];
    for ($w = 1; $w <= 4; $w++) {
        $weekReleaseAt = Carbon::parse('2026-06-01 00:00:00')->addWeeks($w - 1);
        $week = LearningWeek::factory()->create([
            'program_id' => $programClass1->id,
            'week_number' => $w,
            'release_at' => $weekReleaseAt,
            'released_at' => null,
        ]);

        $res = LearningResource::factory()->create(['status' => ResourceStatus::Published]);
        ResourceVersion::factory()->create(['resource_id' => $res->id, 'is_current' => true]);
        LearningItem::factory()->create(['week_id' => $week->id, 'resource_id' => $res->id]);

        $weeks[$w] = $week;
        $resources[$w] = $res;
    }

    $accessService = app(AccessService::class);

    // Day 0: Run release command -> Week 1 releases
    $this->artisan('membership:release-weeks')->assertSuccessful();
    expect($weeks[1]->fresh()->isReleased())->toBeTrue();
    expect($weeks[2]->fresh()->isReleased())->toBeFalse();

    // Member child can access Week 1
    expect($accessService->canAccess($user, $resources[1])->allowed)->toBeTrue();
    // Non-member child cannot access Week 1
    expect($accessService->canAccess($nonMember, $resources[1])->allowed)->toBeFalse();
    // Week 2 is not yet accessible even to member child
    expect($accessService->canAccess($user, $resources[2])->allowed)->toBeFalse();

    // Day 7: Release Week 2
    Carbon::setTestNow('2026-06-08 00:01:00');
    $this->artisan('membership:release-weeks')->assertSuccessful();
    expect($weeks[2]->fresh()->isReleased())->toBeTrue();
    expect($accessService->canAccess($user, $resources[2])->allowed)->toBeTrue();

    // Day 14: Release Week 3
    Carbon::setTestNow('2026-06-15 00:01:00');
    $this->artisan('membership:release-weeks')->assertSuccessful();
    expect($weeks[3]->fresh()->isReleased())->toBeTrue();
    expect($accessService->canAccess($user, $resources[3])->allowed)->toBeTrue();

    // Day 21: Release Week 4
    Carbon::setTestNow('2026-06-22 00:01:00');
    $this->artisan('membership:release-weeks')->assertSuccessful();
    expect($weeks[4]->fresh()->isReleased())->toBeTrue();
    expect($accessService->canAccess($user, $resources[4])->allowed)->toBeTrue();

    // 4. Time travel to 14 days before expiry (2026-08-16)
    Carbon::setTestNow('2026-08-16 10:00:00');
    $this->artisan('membership:expire')->assertSuccessful();

    Event::assertDispatched(MembershipExpiring::class, function ($event) use ($subscription) {
        return $event->subscription->id === $subscription->id && $event->daysRemaining === 14;
    });
    expect($subscription->fresh()->status)->toBe(SubscriptionStatus::Active);

    // 5. Time travel to 3 days before expiry (2026-08-27)
    Carbon::setTestNow('2026-08-27 10:00:00');
    $this->artisan('membership:expire')->assertSuccessful();

    Event::assertDispatched(MembershipExpiring::class, function ($event) use ($subscription) {
        return $event->subscription->id === $subscription->id && $event->daysRemaining === 3;
    });
    expect($subscription->fresh()->status)->toBe(SubscriptionStatus::Active);

    // 6. Time travel to after expiry (2026-08-30 10:01:00)
    Carbon::setTestNow('2026-08-30 10:01:00');
    $this->artisan('membership:expire')->assertSuccessful();

    Event::assertDispatched(MembershipExpired::class, function ($event) use ($subscription) {
        return $event->subscription->id === $subscription->id;
    });
    expect($subscription->fresh()->status)->toBe(SubscriptionStatus::Expired);

    // Verify events recorded in database
    expect(SubscriptionEvent::where('subscription_id', $subscription->id)
        ->where('type', SubscriptionEventType::ExpiringNotice)->count())->toBe(2);
    expect(SubscriptionEvent::where('subscription_id', $subscription->id)
        ->where('type', SubscriptionEventType::Expired)->count())->toBe(1);

    // 7. Verify post-expiry access behavior
    // A new week released in September (after expiry)
    $septProgram = LearningProgram::factory()->published()->create([
        'class_id' => $class1->id,
        'month' => 9,
        'year' => 2026,
    ]);
    $septWeek = LearningWeek::factory()->released()->create([
        'program_id' => $septProgram->id,
        'week_number' => 1,
        'release_at' => Carbon::parse('2026-09-01'),
        'released_at' => Carbon::parse('2026-09-01'),
    ]);
    $septRes = LearningResource::factory()->create(['status' => ResourceStatus::Published]);
    ResourceVersion::factory()->create(['resource_id' => $septRes->id, 'is_current' => true]);
    LearningItem::factory()->create(['week_id' => $septWeek->id, 'resource_id' => $septRes->id]);

    // Member cannot access new releases released after expiry
    expect($accessService->canAccess($user, $septRes)->allowed)->toBeFalse();

    // Permanent purchase stays accessible after membership expires!
    expect($accessService->canAccess($user, $permanentResource)->allowed)->toBeTrue();
});
