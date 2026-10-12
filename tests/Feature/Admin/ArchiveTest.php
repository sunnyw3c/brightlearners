<?php

use App\Domains\Catalog\Enums\ProductStatus;
use App\Domains\Catalog\Models\Product;
use App\Domains\Commerce\Enums\OrderStatus;
use App\Domains\Commerce\Models\Order;
use App\Domains\Commerce\Models\OrderItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('an archived or deleted product remains visible in historical order and account order page renders cleanly', function () {
    $parent = User::factory()->create();

    $product = Product::factory()->create([
        'name' => 'Grade 1 Maths Workbook',
        'status' => ProductStatus::Active,
        'regular_price' => 49900,
    ]);

    $order = Order::factory()->create([
        'user_id' => $parent->id,
        'status' => OrderStatus::Paid,
        'total' => 49900,
        'currency' => 'INR',
    ]);

    $orderItem = OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'product_name' => 'Grade 1 Maths Workbook',
        'product_type' => 'workbook',
        'sku' => 'SKU-MATH-1',
        'unit_price' => 49900,
        'quantity' => 1,
        'total' => 49900,
    ]);

    // Archive the product and soft-delete it
    $product->update(['status' => ProductStatus::Archived]);
    $product->delete();

    // Verify parent's customer order view renders without errors
    $this->actingAs($parent);
    $response = $this->get(route('account.orders.show', $order));

    $response->assertOk();
    $response->assertSee('Grade 1 Maths Workbook');

    // Verify relationship withTrashed still loads smoothly
    expect($orderItem->fresh()->product)->not->toBeNull();
    expect($orderItem->fresh()->product->id)->toBe($product->id);
    expect($orderItem->fresh()->product_name)->toBe('Grade 1 Maths Workbook');
});
