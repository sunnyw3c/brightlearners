<?php

namespace App\Domains\Catalog\Policies;

use App\Domains\Catalog\Enums\ProductStatus;
use App\Domains\Catalog\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('products.view');
    }

    public function view(User $user, Product $product): bool
    {
        return $user->can('products.view');
    }

    public function create(User $user): bool
    {
        return $user->can('products.edit-copy');
    }

    public function update(User $user, Product $product): bool
    {
        return $user->can('products.edit-copy');
    }

    public function delete(User $user, Product $product): bool
    {
        return $user->can('products.edit-copy') && $product->status === ProductStatus::Draft;
    }

    public function restore(User $user, Product $product): bool
    {
        return $user->can('products.edit-copy');
    }

    public function forceDelete(User $user, Product $product): bool
    {
        return false;
    }

    /**
     * Change `regular_price`, `sale_price` or the sale window. Enforced
     * again on the model itself (`Product::booted()`), so a crafted
     * request cannot bypass this by skipping the form.
     */
    public function editPrice(User $user, Product $product): bool
    {
        return $user->can('products.edit-price');
    }
}
