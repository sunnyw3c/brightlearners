<?php

use App\Domains\Commerce\Models\Order;
use App\Models\User;

test('forbids a user from viewing another users order', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $order = Order::factory()->create(['user_id' => $owner->id]);

    $this->actingAs($otherUser)->get(route('account.orders.show', $order))->assertForbidden();
});

test('shows an order to its owner from snapshot data', function () {
    $owner = User::factory()->create();
    $order = Order::factory()->create(['user_id' => $owner->id]);

    $this->actingAs($owner)->get(route('account.orders.show', $order))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->where('order.order_number', $order->order_number));
});
