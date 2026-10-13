<?php

use App\Domains\Accounts\Models\LearningProfile;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Membership\Actions\CancelRecurringSubscription;
use App\Domains\Membership\Actions\ProcessRecurringFailure;
use App\Domains\Membership\Actions\ProcessRecurringRenewal;
use App\Domains\Membership\Contracts\MembershipChecker;
use App\Domains\Membership\Enums\SubscriptionStatus;
use App\Domains\Membership\Models\Subscription;
use App\Domains\Membership\Models\SubscriptionPlan;
use App\Models\User;
use Carbon\CarbonImmutable;

test('recurring renewal webhook is idempotent and extending term occurs only once', function () {
    $user = User::factory()->create();
    $plan = SubscriptionPlan::factory()->create([
        'code' => 'monthly',
        'duration_days' => 30,
        'duration_months' => 1,
        'auto_renew_supported' => true,
    ]);

    $initialStart = CarbonImmutable::now();
    $initialEnd = $initialStart->addDays(30);

    $subscription = Subscription::factory()->create([
        'user_id' => $user->id,
        'plan_id' => $plan->id,
        'provider_subscription_id' => 'sub_razorpay_test_123',
        'starts_at' => $initialStart,
        'ends_at' => $initialEnd,
        'status' => SubscriptionStatus::Active,
        'auto_renew' => true,
    ]);

    $renewalAction = app(ProcessRecurringRenewal::class);
    $paymentId = 'pay_renewal_xyz_999';

    // 1st webhook delivery
    $renewalAction->handle(
        subscription: $subscription,
        providerPaymentId: $paymentId,
        amount: 19900,
        currency: 'INR',
        renewalReference: $paymentId,
    );

    $subscription->refresh();
    $firstRenewedEnd = $subscription->ends_at;

    expect($firstRenewedEnd->greaterThan($initialEnd))->toBeTrue();

    // 2nd webhook delivery with identical renewal reference (duplicate delivery)
    $renewalAction->handle(
        subscription: $subscription,
        providerPaymentId: $paymentId,
        amount: 19900,
        currency: 'INR',
        renewalReference: $paymentId,
    );

    $subscription->refresh();

    // Term must NOT be extended a second time!
    expect($subscription->ends_at->toIso8601String())->toBe($firstRenewedEnd->toIso8601String());
});

test('cancellation and access end-date rules match customer terms through time travel', function () {
    $user = User::factory()->create();
    $class = SchoolClass::factory()->create();
    $child = LearningProfile::factory()->create([
        'user_id' => $user->id,
        'class_id' => $class->id,
        'active' => true,
    ]);

    $plan = SubscriptionPlan::factory()->create([
        'code' => 'monthly',
        'duration_days' => 30,
        'duration_months' => 1,
        'auto_renew_supported' => true,
    ]);

    $now = CarbonImmutable::now();
    $endsAt = $now->addDays(20);

    $subscription = Subscription::factory()->create([
        'user_id' => $user->id,
        'plan_id' => $plan->id,
        'starts_at' => $now->subDays(10),
        'ends_at' => $endsAt,
        'status' => SubscriptionStatus::Active,
        'auto_renew' => true,
    ]);

    // Parent cancels subscription
    $cancelAction = app(CancelRecurringSubscription::class);
    $cancelAction->handle($subscription, false, 'Parent requested cancellation');

    $subscription->refresh();
    expect($subscription->auto_renew)->toBeFalse()
        ->and($subscription->cancel_at_period_end)->toBeTrue()
        ->and($subscription->cancelled_at)->not->toBeNull();

    // 1. Immediately after cancellation (current date), customer STILL has active access!
    $membershipChecker = app(MembershipChecker::class);
    expect($membershipChecker->hasActiveMembership($user))->toBeTrue();

    // 2. Time travel to before ends_at (e.g. 5 days before end): still has access
    CarbonImmutable::setTestNow($endsAt->subDays(5));
    expect($membershipChecker->hasActiveMembership($user))->toBeTrue();

    // 3. Time travel to after ends_at (e.g. 1 day after term ends): access is expired
    CarbonImmutable::setTestNow($endsAt->addDays(1));
    expect($membershipChecker->hasActiveMembership($user))->toBeFalse();

    CarbonImmutable::setTestNow(); // Reset time travel
});

test('renewal payment failure sets grace period and preserves access until grace period ends', function () {
    $user = User::factory()->create();
    $plan = SubscriptionPlan::factory()->create(['code' => 'monthly']);

    $now = CarbonImmutable::now();
    $endsAt = $now->subMinutes(5); // Term ended 5 minutes ago

    $subscription = Subscription::factory()->create([
        'user_id' => $user->id,
        'plan_id' => $plan->id,
        'starts_at' => $now->subDays(30),
        'ends_at' => $endsAt,
        'status' => SubscriptionStatus::Active,
        'auto_renew' => true,
    ]);

    $failureAction = app(ProcessRecurringFailure::class);
    $failureAction->handle($subscription, 'Card authorization failed', 5);

    $subscription->refresh();
    expect($subscription->grace_ends_at)->not->toBeNull()
        ->and($subscription->isWithinGracePeriod())->toBeTrue();

    $membershipChecker = app(MembershipChecker::class);

    // During grace period, membership remains active
    expect($membershipChecker->hasActiveMembership($user))->toBeTrue();

    // Travel beyond grace period
    CarbonImmutable::setTestNow($subscription->grace_ends_at->addHour());
    expect($membershipChecker->hasActiveMembership($user))->toBeFalse();

    CarbonImmutable::setTestNow(); // Reset time travel
});
