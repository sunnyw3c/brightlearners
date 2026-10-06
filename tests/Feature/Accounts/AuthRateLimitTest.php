<?php

use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

test('the login limiter triggers after the configured threshold', function () {
    $user = User::factory()->create();

    RateLimiter::clear(Str::lower($user->email).'|127.0.0.1');

    $max = 5;

    for ($i = 0; $i < $max; $i++) {
        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);
    }

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ])->assertStatus(429);
});

test('the registration limiter triggers after the configured threshold', function () {
    RateLimiter::clear('repeated-attempt@example.com|127.0.0.1');

    $max = config('account.rate_limits.registration.max_attempts');
    $payload = [
        'name' => 'Parent',
        'email' => 'repeated-attempt@example.com',
        'password' => 'a-secure-password',
        'password_confirmation' => 'a-secure-password',
    ];

    for ($i = 0; $i < $max; $i++) {
        $this->post(route('register.store'), $payload);
    }

    $this->post(route('register.store'), $payload)->assertStatus(429);
});

test('the password reset limiter triggers after the configured threshold', function () {
    $user = User::factory()->create();

    RateLimiter::clear(Str::lower($user->email).'|127.0.0.1');

    $max = config('account.rate_limits.password_reset.max_attempts');

    for ($i = 0; $i < $max; $i++) {
        $this->post(route('password.email'), ['email' => $user->email]);
    }

    $this->post(route('password.email'), ['email' => $user->email])
        ->assertStatus(429);
});
