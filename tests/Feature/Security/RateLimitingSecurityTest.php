<?php

use App\Domains\Content\Models\LearningResource;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;

test('the search route rate limits excessive queries', function () {
    $ip = '198.51.100.1';
    RateLimiter::clear($ip);

    for ($i = 0; $i < 60; $i++) {
        $this->withServerVariables(['REMOTE_ADDR' => $ip])
            ->get(route('search', ['q' => 'math']))
            ->assertOk();
    }

    $this->withServerVariables(['REMOTE_ADDR' => $ip])
        ->get(route('search', ['q' => 'math']))
        ->assertStatus(429);
});

test('the coupon route rate limits brute force attempts', function () {
    $ip = '198.51.100.2';
    RateLimiter::clear($ip);

    for ($i = 0; $i < 10; $i++) {
        $this->withServerVariables(['REMOTE_ADDR' => $ip])
            ->post(route('cart.coupon.store'), ['code' => 'INVALID'.$i]);
    }

    $this->withServerVariables(['REMOTE_ADDR' => $ip])
        ->post(route('cart.coupon.store'), ['code' => 'INVALID99'])
        ->assertStatus(429);
});

test('the download route rate limits excessive downloads', function () {
    $user = User::factory()->create();
    $resource = LearningResource::factory()->published()->free()->create();

    RateLimiter::clear((string) $user->id);

    for ($i = 0; $i < 30; $i++) {
        $this->actingAs($user)
            ->get(route('downloads.show', $resource));
    }

    $this->actingAs($user)
        ->get(route('downloads.show', $resource))
        ->assertStatus(429);
});

test('the checkout routes rate limit rapid submissions', function () {
    $user = User::factory()->create();
    RateLimiter::clear((string) $user->id);

    for ($i = 0; $i < 15; $i++) {
        $this->actingAs($user)
            ->get(route('checkout.show'));
    }

    $this->actingAs($user)
        ->get(route('checkout.show'))
        ->assertStatus(429);
});

test('the events route rate limits excessive client events', function () {
    $ip = '198.51.100.3';
    RateLimiter::clear($ip);

    for ($i = 0; $i < 60; $i++) {
        $this->withServerVariables(['REMOTE_ADDR' => $ip])
            ->post(route('events.store'), [
                'name' => 'page_viewed',
                'url' => 'https://brightlearners.test/learn',
            ]);
    }

    $this->withServerVariables(['REMOTE_ADDR' => $ip])
        ->post(route('events.store'), [
            'name' => 'page_viewed',
            'url' => 'https://brightlearners.test/learn',
        ])
        ->assertStatus(429);
});
