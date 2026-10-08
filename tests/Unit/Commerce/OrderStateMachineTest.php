<?php

use App\Domains\Commerce\Actions\TransitionOrder;
use App\Domains\Commerce\Enums\OrderStatus;
use App\Domains\Commerce\Exceptions\InvalidOrderStateTransitionException;
use App\Domains\Commerce\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('valid order state transitions succeed', function (OrderStatus $from, OrderStatus $to) {
    $order = Order::factory()->create(['status' => $from]);
    $transitionAction = app(TransitionOrder::class);

    $updatedOrder = $transitionAction->handle($order, $to);

    expect($updatedOrder->status)->toBe($to);
})->with([
    [OrderStatus::Draft, OrderStatus::PendingPayment],
    [OrderStatus::Draft, OrderStatus::Cancelled],
    [OrderStatus::PendingPayment, OrderStatus::Paid],
    [OrderStatus::PendingPayment, OrderStatus::Failed],
    [OrderStatus::PendingPayment, OrderStatus::Cancelled],
    [OrderStatus::Failed, OrderStatus::PendingPayment],
    [OrderStatus::Failed, OrderStatus::Cancelled],
    [OrderStatus::Paid, OrderStatus::Refunded],
    [OrderStatus::Paid, OrderStatus::PartiallyRefunded],
    [OrderStatus::PartiallyRefunded, OrderStatus::Refunded],
]);

test('invalid order state transitions are refused and throw exception', function (OrderStatus $from, OrderStatus $invalidTo) {
    $order = Order::factory()->create(['status' => $from]);
    $transitionAction = app(TransitionOrder::class);

    expect(fn () => $transitionAction->handle($order, $invalidTo))
        ->toThrow(InvalidOrderStateTransitionException::class);
})->with([
    [OrderStatus::Draft, OrderStatus::Paid],
    [OrderStatus::PendingPayment, OrderStatus::Refunded],
    [OrderStatus::Paid, OrderStatus::PendingPayment],
    [OrderStatus::Paid, OrderStatus::Cancelled],
    [OrderStatus::Cancelled, OrderStatus::Paid],
    [OrderStatus::Cancelled, OrderStatus::PendingPayment],
    [OrderStatus::Refunded, OrderStatus::Paid],
    [OrderStatus::Refunded, OrderStatus::Cancelled],
]);
