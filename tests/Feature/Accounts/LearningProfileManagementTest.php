<?php

use App\Domains\Accounts\Models\LearningProfile;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Models\User;

test('a parent can add a learning profile', function () {
    $user = User::factory()->create();
    $schoolClass = SchoolClass::factory()->create();

    $this->actingAs($user)
        ->post(route('account.profiles.store'), [
            'nickname' => 'Riya',
            'class_id' => $schoolClass->id,
            'avatar_key' => 'fox',
        ])
        ->assertRedirect(route('account.profiles.index'));

    expect($user->learningProfiles()->where('nickname', 'Riya')->exists())->toBeTrue();
});

test('a parent cannot assign a learning profile to an inactive class', function () {
    $user = User::factory()->create();
    $schoolClass = SchoolClass::factory()->inactive()->create();

    $this->actingAs($user)
        ->post(route('account.profiles.store'), [
            'nickname' => 'Riya',
            'class_id' => $schoolClass->id,
        ])
        ->assertSessionHasErrors('class_id');
});

test('a parent cannot exceed the configured learning profile cap', function () {
    config(['account.max_learning_profiles' => 2]);

    $user = User::factory()->create();
    LearningProfile::factory()->for($user)->count(2)->create();

    $this->actingAs($user)
        ->post(route('account.profiles.store'), ['nickname' => 'One too many'])
        ->assertSessionHasErrors('nickname');

    expect($user->learningProfiles()->count())->toBe(2);
});

test('removing a learning profile deactivates it instead of deleting it', function () {
    $user = User::factory()->create();
    $profile = LearningProfile::factory()->for($user)->create();

    $this->actingAs($user)
        ->delete(route('account.profiles.destroy', $profile))
        ->assertRedirect(route('account.profiles.index'));

    expect(LearningProfile::query()->find($profile->id))->not->toBeNull()
        ->and($profile->refresh()->active)->toBeFalse();
});
