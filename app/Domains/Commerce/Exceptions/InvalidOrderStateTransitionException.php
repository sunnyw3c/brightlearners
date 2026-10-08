<?php

namespace App\Domains\Commerce\Exceptions;

use App\Domains\Commerce\Enums\OrderStatus;
use DomainException;

class InvalidOrderStateTransitionException extends DomainException
{
    public function __construct(OrderStatus $from, OrderStatus $to)
    {
        parent::__construct("Cannot transition order status from '{$from->value}' to '{$to->value}'.");
    }
}
