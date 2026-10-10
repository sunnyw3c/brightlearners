<?php

use App\Domains\Accounts\Models\NotificationPreference;
use App\Domains\Commerce\Models\Order;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourceCorrection;
use App\Domains\Membership\Models\LearningProgram;
use App\Domains\Membership\Models\LearningWeek;
use App\Domains\Membership\Models\Subscription;
use App\Models\User;
use App\Notifications\CorrectionNotice;
use App\Notifications\MembershipExpiringNotice;
use App\Notifications\MonthlyCheckAvailable;
use App\Notifications\OrderConfirmation;
use App\Notifications\WeeklyPackReleased;

test('order confirmation is transactional and never suppressed by marketing opt-out or turned off preferences', function () {
    $user = User::factory()->create();
    NotificationPreference::factory()->create([
        'user_id' => $user->id,
        'marketing_consent' => false,
        'learning_reminders' => false,
        'membership_notices' => false,
    ]);

    $order = Order::factory()->create(['user_id' => $user->id]);
    $notification = new OrderConfirmation($order);

    $channels = $notification->via($user);
    expect($channels)->toContain('mail');
});

test('correction notice is transactional and never suppressed by marketing opt-out or turned off preferences', function () {
    $user = User::factory()->create();
    NotificationPreference::factory()->create([
        'user_id' => $user->id,
        'marketing_consent' => false,
        'learning_reminders' => false,
        'membership_notices' => false,
    ]);

    $resource = LearningResource::factory()->published()->create();
    $correction = ResourceCorrection::factory()->create(['resource_id' => $resource->id]);
    $notification = new CorrectionNotice($correction);

    $channels = $notification->via($user);
    expect($channels)->toContain('mail');
});

test('membership expiring notice respects membership notices preference and ignores marketing opt-out', function () {
    $user = User::factory()->create();
    $pref = NotificationPreference::factory()->create([
        'user_id' => $user->id,
        'membership_notices' => true,
        'marketing_consent' => false,
    ]);

    $sub = Subscription::factory()->create(['user_id' => $user->id]);
    $notification = new MembershipExpiringNotice($sub, 14);

    // With membership_notices = true and marketing = false -> allowed
    expect($notification->via($user))->toContain('mail');

    // With membership_notices = false -> suppressed
    $pref->update(['membership_notices' => false]);
    $user->unsetRelation('notificationPreference');
    expect($notification->via($user))->toBeEmpty();
});

test('weekly pack released respects learning reminders preference and ignores marketing opt-out', function () {
    $user = User::factory()->create();
    $pref = NotificationPreference::factory()->create([
        'user_id' => $user->id,
        'learning_reminders' => true,
        'marketing_consent' => false,
    ]);

    $week = LearningWeek::factory()->create();
    $notification = new WeeklyPackReleased($week);

    // With learning_reminders = true and marketing = false -> allowed
    expect($notification->via($user))->toContain('mail');

    // With learning_reminders = false -> suppressed
    $pref->update(['learning_reminders' => false]);
    $user->unsetRelation('notificationPreference');
    expect($notification->via($user))->toBeEmpty();
});

test('monthly check available respects learning reminders preference and ignores marketing opt-out', function () {
    $user = User::factory()->create();
    $pref = NotificationPreference::factory()->create([
        'user_id' => $user->id,
        'learning_reminders' => true,
        'marketing_consent' => false,
    ]);

    $program = LearningProgram::factory()->create();
    $notification = new MonthlyCheckAvailable($program);

    // With learning_reminders = true and marketing = false -> allowed
    expect($notification->via($user))->toContain('mail');

    // With learning_reminders = false -> suppressed
    $pref->update(['learning_reminders' => false]);
    $user->unsetRelation('notificationPreference');
    expect($notification->via($user))->toBeEmpty();
});
