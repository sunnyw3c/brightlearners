<?php

use App\Domains\Commerce\Models\Order;
use App\Models\User;

test('a user cannot view another user order detail page', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();

    $order = Order::factory()->create(['user_id' => $owner->id]);

    $this->actingAs($otherUser)
        ->get(route('account.orders.show', $order))
        ->assertForbidden();
});

test('an order owner can view their own order detail page', function () {
    $owner = User::factory()->create();
    $order = Order::factory()->create(['user_id' => $owner->id]);

    $this->actingAs($owner)
        ->get(route('account.orders.show', $order))
        ->assertOk();
});
