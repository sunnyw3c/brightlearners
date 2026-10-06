<?php

use App\Domains\Accounts\Enums\UserStatus;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;

beforeEach(function () {
    RateLimiter::clear('ravi@example.com|127.0.0.1');
});

test('a suspended user cannot log in', function () {
    $user = User::factory()->create([
        'email' => 'ravi@example.com',
        'status' => UserStatus::Suspended,
    ]);

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
});

test('an active user can still log in', function () {
    $user = User::factory()->create(['email' => 'ravi@example.com']);

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('account.dashboard'));

    $this->assertAuthenticatedAs($user);
});
