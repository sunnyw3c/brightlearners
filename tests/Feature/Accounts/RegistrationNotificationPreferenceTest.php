<?php

use App\Domains\Accounts\Models\NotificationPreference;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;

beforeEach(function () {
    RateLimiter::clear('priya@example.com|127.0.0.1');
});

test('registering creates a notification preference row with marketing consent unticked by default', function () {
    $this->post(route('register.store'), [
        'name' => 'Priya Parent',
        'email' => 'priya@example.com',
        'password' => 'a-secure-password',
        'password_confirmation' => 'a-secure-password',
    ]);

    $user = User::query()->where('email', 'priya@example.com')->firstOrFail();
    $preference = NotificationPreference::query()->where('user_id', $user->id)->firstOrFail();

    expect($preference->marketing_consent)->toBeFalse()
        ->and($preference->marketing_consent_at)->toBeNull();
});

test('ticking the marketing-consent checkbox at registration is recorded', function () {
    $this->post(route('register.store'), [
        'name' => 'Priya Parent',
        'email' => 'priya@example.com',
        'password' => 'a-secure-password',
        'password_confirmation' => 'a-secure-password',
        'marketing_consent' => '1',
    ]);

    $user = User::query()->where('email', 'priya@example.com')->firstOrFail();
    $preference = NotificationPreference::query()->where('user_id', $user->id)->firstOrFail();

    expect($preference->marketing_consent)->toBeTrue()
        ->and($preference->marketing_consent_at)->not->toBeNull()
        ->and($preference->marketing_consent_source)->toBe('registration');
});
