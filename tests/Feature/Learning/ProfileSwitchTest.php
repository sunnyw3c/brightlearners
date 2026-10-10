<?php

use App\Domains\Accounts\Models\LearningProfile;
use App\Models\User;

test('a parent can switch between their own learning profiles', function () {
    $user = User::factory()->create();
    $profile1 = LearningProfile::factory()->create(['user_id' => $user->id, 'nickname' => 'Aarav']);
    $profile2 = LearningProfile::factory()->create(['user_id' => $user->id, 'nickname' => 'Myra']);

    $response = $this->actingAs($user)
        ->post(route('account.profiles.switch', $profile2));

    $response->assertRedirect(route('account.dashboard'));
    expect(session('active_learning_profile_id'))->toBe($profile2->id);

    $dashboardResponse = $this->actingAs($user)->get(route('account.dashboard'));
    $dashboardResponse->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('account/dashboard')
            ->where('active_profile.id', $profile2->id)
            ->where('active_profile.nickname', 'Myra')
        );
});

test('switching to another users profile ID is refused with 403', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    $profileA = LearningProfile::factory()->create(['user_id' => $userA->id, 'nickname' => 'Child A']);
    $profileB = LearningProfile::factory()->create(['user_id' => $userB->id, 'nickname' => 'Child B']);

    $response = $this->actingAs($userA)
        ->post(route('account.profiles.switch', $profileB));

    $response->assertForbidden();
});

test('passing another users profile ID in query parameter is refused', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    $profileA = LearningProfile::factory()->create(['user_id' => $userA->id]);
    $profileB = LearningProfile::factory()->create(['user_id' => $userB->id]);

    $response = $this->actingAs($userA)
        ->get(route('account.dashboard', ['profile_id' => $profileB->id]));

    $response->assertForbidden();
});

test('the dashboard shows only the owners profiles and data', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    $profileA = LearningProfile::factory()->create(['user_id' => $userA->id, 'nickname' => 'Aarav']);
    $profileB = LearningProfile::factory()->create(['user_id' => $userB->id, 'nickname' => 'Secret Child B']);

    $response = $this->actingAs($userA)->get(route('account.dashboard'));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('account/dashboard')
            ->has('profiles', 1)
            ->where('profiles.0.nickname', 'Aarav')
            ->where('active_profile.id', $profileA->id)
        );
});
