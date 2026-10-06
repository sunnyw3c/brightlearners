<?php

use App\Models\User;

test('an unverified parent can reach public pages', function () {
    $user = User::factory()->unverified()->create();

    $this->actingAs($user)->get(route('home'))->assertOk();
});

test('an unverified parent is redirected away from the dashboard', function () {
    $user = User::factory()->unverified()->create();

    $this->actingAs($user)
        ->get(route('account.dashboard'))
        ->assertRedirect(route('verification.notice'));
});

test('an unverified parent is redirected away from learning profiles', function () {
    $user = User::factory()->unverified()->create();

    $this->actingAs($user)
        ->get(route('account.profiles.index'))
        ->assertRedirect(route('verification.notice'));
});

test('a verified parent can reach the dashboard and learning profiles', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('account.dashboard'))->assertOk();
    $this->actingAs($user)->get(route('account.profiles.index'))->assertOk();
});
