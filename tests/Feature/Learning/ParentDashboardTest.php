<?php

use App\Domains\Accounts\Models\LearningProfile;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourceVersion;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Learning\Models\ResourceProgress;
use App\Domains\Membership\Enums\LearningProgramStatus;
use App\Domains\Membership\Enums\SubscriptionStatus;
use App\Domains\Membership\Models\LearningItem;
use App\Domains\Membership\Models\LearningProgram;
use App\Domains\Membership\Models\LearningWeek;
use App\Domains\Membership\Models\Subscription;
use App\Models\User;
use App\Notifications\WeeklyPackReleased;

test('a parent with no learning profiles sees prompt to add child', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('account.dashboard'));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('account/dashboard')
            ->where('active_profile', null)
            ->has('profiles', 0)
            ->where('is_active_member', false)
        );
});

test('a parent with profile but no active membership sees membership prompt and free resources', function () {
    $user = User::factory()->create();
    $profile = LearningProfile::factory()->create(['user_id' => $user->id, 'nickname' => 'Aarav']);

    $response = $this->actingAs($user)->get(route('account.dashboard'));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('account/dashboard')
            ->where('active_profile.id', $profile->id)
            ->where('is_active_member', false)
        );
});

test('a parent with active membership sees the current week within one screen', function () {
    $user = User::factory()->create();
    $class = SchoolClass::factory()->create(['name' => 'Class 2', 'slug' => 'class-2']);
    $profile = LearningProfile::factory()->create([
        'user_id' => $user->id,
        'nickname' => 'Aarav',
        'class_id' => $class->id,
    ]);

    Subscription::factory()->active()->create([
        'user_id' => $user->id,
        'status' => SubscriptionStatus::Active,
        'ends_at' => now()->addDays(60),
    ]);

    $program = LearningProgram::factory()->published()->create([
        'class_id' => $class->id,
        'month' => (int) now()->format('n'),
        'year' => (int) now()->format('Y'),
        'status' => LearningProgramStatus::Published,
    ]);

    $week1 = LearningWeek::factory()->released()->create([
        'program_id' => $program->id,
        'week_number' => 1,
        'title' => 'Numbers & Place Value',
        'emphasis' => 'Foundational counting',
    ]);

    $resource = LearningResource::factory()->published()->create(['is_free' => false]);
    ResourceVersion::factory()->create(['resource_id' => $resource->id, 'is_current' => true]);

    LearningItem::factory()->create([
        'week_id' => $week1->id,
        'resource_id' => $resource->id,
        'required' => true,
        'guidance' => 'Complete before Friday',
    ]);

    $response = $this->actingAs($user)->get(route('account.dashboard'));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('account/dashboard')
            ->where('is_active_member', true)
            ->where('active_profile.nickname', 'Aarav')
            ->where('programme.current_week_number', 1)
            ->where('programme.week_title', 'Numbers & Place Value')
            ->has('programme.items', 1)
            ->where('programme.items.0.title', $resource->title)
            ->where('next_action.type', 'activity')
            ->where('monthly_progress.total_activities', 1)
            ->where('monthly_progress.completed_activities', 0)
        );
});

test('a parent can complete a resource and see the month progress update', function () {
    $user = User::factory()->create();
    $class = SchoolClass::factory()->create();
    $profile = LearningProfile::factory()->create([
        'user_id' => $user->id,
        'nickname' => 'Aarav',
        'class_id' => $class->id,
    ]);

    Subscription::factory()->active()->create([
        'user_id' => $user->id,
        'ends_at' => now()->addDays(60),
    ]);

    $program = LearningProgram::factory()->published()->create([
        'class_id' => $class->id,
        'month' => (int) now()->format('n'),
        'year' => (int) now()->format('Y'),
    ]);

    $week = LearningWeek::factory()->released()->create(['program_id' => $program->id, 'week_number' => 1]);
    $resource = LearningResource::factory()->published()->create(['is_free' => false]);
    ResourceVersion::factory()->create(['resource_id' => $resource->id, 'is_current' => true]);

    LearningItem::factory()->create([
        'week_id' => $week->id,
        'resource_id' => $resource->id,
    ]);

    // Parent completes resource
    $completeResponse = $this->actingAs($user)->post(route('account.progress.store'), [
        'resource_id' => $resource->id,
        'profile_id' => $profile->id,
        'status' => 'completed',
    ]);
    $completeResponse->assertRedirect();

    // Check dashboard monthly progress updated
    $dashboardResponse = $this->actingAs($user)->get(route('account.dashboard'));
    $dashboardResponse->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('account/dashboard')
            ->where('monthly_progress.total_activities', 1)
            ->where('monthly_progress.completed_activities', 1)
            ->where('monthly_progress.percentage', 100)
            ->where('programme.items.0.is_completed', true)
        );
});

test('in-app notifications can be viewed and marked as read', function () {
    $user = User::factory()->create();
    $week = LearningWeek::factory()->create();

    $user->notify(new WeeklyPackReleased($week));

    expect($user->unreadNotifications()->count())->toBe(1);
    $notification = $user->unreadNotifications()->first();

    $response = $this->actingAs($user)->get(route('account.notifications'));
    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('account/notifications')
            ->has('notifications.data', 1)
            ->where('unread_count', 1)
        );

    // Mark single as read
    $markReadResponse = $this->actingAs($user)->post(route('account.notifications.read', $notification->id));
    $markReadResponse->assertRedirect();
    expect($user->fresh()->unreadNotifications()->count())->toBe(0);

    // Send another notification and mark all as read
    $user->notify(new WeeklyPackReleased($week));
    expect($user->fresh()->unreadNotifications()->count())->toBe(1);

    $markAllResponse = $this->actingAs($user)->post(route('account.notifications.mark-all-read'));
    $markAllResponse->assertRedirect();
    expect($user->fresh()->unreadNotifications()->count())->toBe(0);
});

test('account progress page renders activity stats and history', function () {
    $user = User::factory()->create();
    $profile = LearningProfile::factory()->create(['user_id' => $user->id, 'nickname' => 'Aarav']);
    $resource = LearningResource::factory()->published()->free()->create();
    ResourceVersion::factory()->create(['resource_id' => $resource->id, 'is_current' => true]);

    ResourceProgress::factory()->completed()->create([
        'user_id' => $user->id,
        'learning_profile_id' => $profile->id,
        'resource_id' => $resource->id,
    ]);

    $response = $this->actingAs($user)->get(route('account.progress'));
    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('account/progress')
            ->where('stats.completed', 1)
            ->has('progress', 1)
        );
});
