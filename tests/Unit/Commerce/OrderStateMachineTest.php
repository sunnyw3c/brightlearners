<?php

use App\Domains\Commerce\Actions\TransitionOrder;
use App\Domains\Commerce\Enums\OrderStatus;
use App\Domains\Commerce\Models\Order;
use DomainException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('allows every documented order transition', function (OrderStatus $from, OrderStatus $to) {
    $order = Order::factory()->create(['status' => $from]);

    $transitioned = app(TransitionOrder::class)->handle($order, $to);

    expect($transitioned->status)->toBe($to);
})->with([
    'draft to pending' => [OrderStatus::Draft, OrderStatus::PendingPayment],
    'draft to cancelled' => [OrderStatus::Draft, OrderStatus::Cancelled],
    'pending to paid' => [OrderStatus::PendingPayment, OrderStatus::Paid],
    'pending to failed' => [OrderStatus::PendingPayment, OrderStatus::Failed],
    'pending to cancelled' => [OrderStatus::PendingPayment, OrderStatus::Cancelled],
    'failed to pending' => [OrderStatus::Failed, OrderStatus::PendingPayment],
    'failed to cancelled' => [OrderStatus::Failed, OrderStatus::Cancelled],
    'paid to refunded' => [OrderStatus::Paid, OrderStatus::Refunded],
    'paid to partial refund' => [OrderStatus::Paid, OrderStatus::PartiallyRefunded],
    'partial refund to refunded' => [OrderStatus::PartiallyRefunded, OrderStatus::Refunded],
]);

test('refuses transitions outside the documented state machine', function (OrderStatus $from, OrderStatus $to) {
    $order = Order::factory()->create(['status' => $from]);

    expect(fn () => app(TransitionOrder::class)->handle($order, $to))->toThrow(DomainException::class);
})->with([
    'draft to paid' => [OrderStatus::Draft, OrderStatus::Paid],
    'pending to refunded' => [OrderStatus::PendingPayment, OrderStatus::Refunded],
    'failed to paid' => [OrderStatus::Failed, OrderStatus::Paid],
    'paid to cancelled' => [OrderStatus::Paid, OrderStatus::Cancelled],
    'cancelled is terminal' => [OrderStatus::Cancelled, OrderStatus::PendingPayment],
    'refunded is terminal' => [OrderStatus::Refunded, OrderStatus::Paid],
]);
