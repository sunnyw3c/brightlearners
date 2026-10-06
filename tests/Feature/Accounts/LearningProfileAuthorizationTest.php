<?php

use App\Domains\Accounts\Models\LearningProfile;
use App\Models\User;

test('a parent cannot view another parent\'s learning profile list entry', function () {
    $owner = User::factory()->create();
    $intruder = User::factory()->create();
    $profile = LearningProfile::factory()->for($owner)->create();

    expect($intruder->can('view', $profile))->toBeFalse();
    expect($intruder->can('update', $profile))->toBeFalse();
    expect($intruder->can('delete', $profile))->toBeFalse();
});

test('a parent cannot update another parent\'s learning profile', function () {
    $owner = User::factory()->create();
    $intruder = User::factory()->create();
    $profile = LearningProfile::factory()->for($owner)->create();

    $this->actingAs($intruder)
        ->patch(route('account.profiles.update', $profile), ['nickname' => 'Changed'])
        ->assertForbidden();
});

test('a parent cannot delete another parent\'s learning profile', function () {
    $owner = User::factory()->create();
    $intruder = User::factory()->create();
    $profile = LearningProfile::factory()->for($owner)->create();

    $this->actingAs($intruder)
        ->delete(route('account.profiles.destroy', $profile))
        ->assertForbidden();

    expect($profile->refresh()->active)->toBeTrue();
});

test('a parent can manage their own learning profile', function () {
    $owner = User::factory()->create();
    $profile = LearningProfile::factory()->for($owner)->create();

    $this->actingAs($owner)
        ->patch(route('account.profiles.update', $profile), ['nickname' => 'Changed'])
        ->assertRedirect(route('account.profiles.index'));

    expect($profile->refresh()->nickname)->toBe('Changed');

    $this->actingAs($owner)
        ->delete(route('account.profiles.destroy', $profile))
        ->assertRedirect(route('account.profiles.index'));

    expect($profile->refresh()->active)->toBeFalse();
});
