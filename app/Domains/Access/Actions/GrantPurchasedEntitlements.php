<?php

namespace App\Domains\Access\Actions;

use App\Domains\Access\Models\Entitlement;
use App\Domains\Commerce\Models\Order;
use App\Domains\Commerce\Models\OrderItem;
use App\Domains\Content\Models\LearningResource;
use Illuminate\Support\Facades\DB;

class GrantPurchasedEntitlements
{
    public function handle(Order $order): void
    {
        DB::transaction(function () use ($order): void {
            $order->loadMissing(['items.product.resources', 'items.product.childProducts.resources']);

            foreach ($order->items as $item) {
                /** @var OrderItem $item */
                $product = $item->product;
                if ($product === null) {
                    continue;
                }

                $resources = $product->deliverableResources();

                foreach ($resources as $resource) {
                    /** @var LearningResource $resource */
                    Entitlement::query()->firstOrCreate([
                        'user_id' => $order->user_id,
                        'resource_id' => $resource->id,
                        'source_type' => 'order_item',
                        'source_id' => $item->id,
                    ], [
                        'starts_at' => $order->paid_at ?? now(),
                        'ends_at' => null,
                        'revoked_at' => null,
                    ]);
                }
            }
        });
    }
}
