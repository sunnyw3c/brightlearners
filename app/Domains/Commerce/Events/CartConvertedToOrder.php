<?php

namespace App\Domains\Commerce\Events;

use App\Domains\Commerce\Models\Cart;
use App\Domains\Commerce\Models\Order;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CartConvertedToOrder
{
    use Dispatchable, SerializesModels;

    public function __construct(public Cart $cart, public Order $order) {}
}
