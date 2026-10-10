<?php

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
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\SendQueuedNotifications;
use Illuminate\Support\Facades\Queue;

test('order confirmation notification is queued on notifications queue', function () {
    Queue::fake();

    $user = User::factory()->create();
    $order = Order::factory()->create(['user_id' => $user->id]);
    $notification = new OrderConfirmation($order);

    expect($notification)->toBeInstanceOf(ShouldQueue::class)
        ->and($notification->queue)->toBe('notifications');

    $user->notify($notification);

    Queue::assertPushed(SendQueuedNotifications::class, function ($job) {
        return $job->queue === 'notifications'
            && $job->notification instanceof OrderConfirmation;
    });
});

test('correction notice notification is queued on notifications queue', function () {
    Queue::fake();

    $user = User::factory()->create();
    $resource = LearningResource::factory()->published()->create();
    $correction = ResourceCorrection::factory()->create(['resource_id' => $resource->id]);
    $notification = new CorrectionNotice($correction);

    expect($notification)->toBeInstanceOf(ShouldQueue::class)
        ->and($notification->queue)->toBe('notifications');

    $user->notify($notification);

    Queue::assertPushed(SendQueuedNotifications::class, function ($job) {
        return $job->queue === 'notifications'
            && $job->notification instanceof CorrectionNotice;
    });
});

test('membership expiring notice notification is queued on notifications queue', function () {
    Queue::fake();

    $user = User::factory()->create();
    $sub = Subscription::factory()->create(['user_id' => $user->id]);
    $notification = new MembershipExpiringNotice($sub, 7);

    expect($notification)->toBeInstanceOf(ShouldQueue::class)
        ->and($notification->queue)->toBe('notifications');

    $user->notify($notification);

    Queue::assertPushed(SendQueuedNotifications::class, function ($job) {
        return $job->queue === 'notifications'
            && $job->notification instanceof MembershipExpiringNotice;
    });
});

test('weekly pack released notification is queued on notifications queue', function () {
    Queue::fake();

    $user = User::factory()->create();
    $week = LearningWeek::factory()->create();
    $notification = new WeeklyPackReleased($week);

    expect($notification)->toBeInstanceOf(ShouldQueue::class)
        ->and($notification->queue)->toBe('notifications');

    $user->notify($notification);

    Queue::assertPushed(SendQueuedNotifications::class, function ($job) {
        return $job->queue === 'notifications'
            && $job->notification instanceof WeeklyPackReleased;
    });
});

test('monthly check available notification is queued on notifications queue', function () {
    Queue::fake();

    $user = User::factory()->create();
    $program = LearningProgram::factory()->create();
    $notification = new MonthlyCheckAvailable($program);

    expect($notification)->toBeInstanceOf(ShouldQueue::class)
        ->and($notification->queue)->toBe('notifications');

    $user->notify($notification);

    Queue::assertPushed(SendQueuedNotifications::class, function ($job) {
        return $job->queue === 'notifications'
            && $job->notification instanceof MonthlyCheckAvailable;
    });
});
