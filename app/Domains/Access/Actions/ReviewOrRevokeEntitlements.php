<?php

namespace App\Domains\Access\Actions;

use App\Domains\Access\Models\Entitlement;
use App\Domains\Commerce\Enums\OrderStatus;
use App\Domains\Payments\Models\Refund;
use Illuminate\Support\Facades\DB;

class ReviewOrRevokeEntitlements
{
    public function handle(Refund $refund): void
    {
        DB::transaction(function () use ($refund): void {
            $payment = $refund->payment;
            if ($payment === null) {
                return;
            }

            $order = $payment->order;
            if ($order === null) {
                return;
            }

            // Check if order is fully refunded or partially refunded
            if ($order->status === OrderStatus::Refunded) {
                $orderItemIds = $order->items()->pluck('id');

                Entitlement::query()
                    ->where('user_id', $order->user_id)
                    ->where('source_type', 'order_item')
                    ->whereIn('source_id', $orderItemIds)
                    ->whereNull('revoked_at')
                    ->update(['revoked_at' => now()]);
            }
        });
    }
}
