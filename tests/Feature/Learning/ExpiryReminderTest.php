<?php

use App\Domains\Membership\Enums\SubscriptionStatus;
use App\Domains\Membership\Models\Subscription;
use App\Models\User;
use App\Notifications\MembershipExpiringNotice;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;

test('expiry reminder schedule sends notices on day 14 and day 3 and none after expiry', function () {
    Notification::fake();

    $startDate = Carbon::parse('2026-10-01 10:00:00');
    Carbon::setTestNow($startDate);

    $user = User::factory()->create();
    $subscription = Subscription::factory()->active()->create([
        'user_id' => $user->id,
        'starts_at' => $startDate,
        'ends_at' => $startDate->copy()->addDays(90), // Expiring at day 90
        'status' => SubscriptionStatus::Active,
    ]);

    // 1. On Day 10 (80 days remaining): No notice
    Carbon::setTestNow($startDate->copy()->addDays(10));
    $this->artisan('membership:expire')->assertSuccessful();
    Notification::assertNothingSent();

    // 2. On Day 76 (14 days remaining): Day 14 notice sent
    Carbon::setTestNow($subscription->ends_at->copy()->subDays(14)->setTime(12, 0, 0));
    $this->artisan('membership:expire')->assertSuccessful();

    Notification::assertSentTo($user, MembershipExpiringNotice::class, function ($notification) {
        return $notification->daysRemaining === 14;
    });

    // 3. Running again on same day does NOT send duplicate notice
    $this->artisan('membership:expire')->assertSuccessful();
    Notification::assertSentTimes(MembershipExpiringNotice::class, 1);

    // 4. On Day 87 (3 days remaining): Day 3 notice sent
    Carbon::setTestNow($subscription->ends_at->copy()->subDays(3)->setTime(12, 0, 0));
    $this->artisan('membership:expire')->assertSuccessful();

    Notification::assertSentTo($user, MembershipExpiringNotice::class, function ($notification) {
        return $notification->daysRemaining === 3;
    });
    Notification::assertSentTimes(MembershipExpiringNotice::class, 2);

    // 5. Past expiry: membership marked expired, no expiry notices sent
    Carbon::setTestNow($subscription->ends_at->copy()->addDay());
    $this->artisan('membership:expire')->assertSuccessful();

    expect($subscription->fresh()->status)->toBe(SubscriptionStatus::Expired);
    Notification::assertSentTimes(MembershipExpiringNotice::class, 2); // No new expiring notices
});
