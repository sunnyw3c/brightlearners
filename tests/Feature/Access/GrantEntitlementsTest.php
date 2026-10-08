<?php

use App\Domains\Access\Actions\GrantPurchasedEntitlements;
use App\Domains\Access\Models\Entitlement;
use App\Domains\Catalog\Enums\ProductType;
use App\Domains\Catalog\Models\Product;
use App\Domains\Commerce\Models\Order;
use App\Domains\Commerce\Models\OrderItem;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Payments\Events\OrderPaid;
use App\Domains\Payments\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('Paid order grants entitlements for purchased items and running twice is idempotent', function () {
    $user = User::factory()->create();
    $resource1 = LearningResource::factory()->create();
    $resource2 = LearningResource::factory()->create();

    $product1 = Product::factory()->create(['type' => ProductType::TopicPack]);
    $product1->resources()->attach($resource1->id);

    $product2 = Product::factory()->create(['type' => ProductType::Workbook]);
    $product2->resources()->attach($resource2->id);

    $order = Order::factory()->create(['user_id' => $user->id, 'paid_at' => now()]);
    OrderItem::factory()->create(['order_id' => $order->id, 'product_id' => $product1->id]);
    OrderItem::factory()->create(['order_id' => $order->id, 'product_id' => $product2->id]);

    $grantAction = app(GrantPurchasedEntitlements::class);
    $grantAction->handle($order);

    expect(Entitlement::where('user_id', $user->id)->count())->toBe(2);

    // Running second time creates no duplicate rows
    $grantAction->handle($order);
    expect(Entitlement::where('user_id', $user->id)->count())->toBe(2);
});

test('Bundle product purchase grants entitlements for all child products resources', function () {
    $user = User::factory()->create();
    $resourceA = LearningResource::factory()->create();
    $resourceB = LearningResource::factory()->create();

    $childProduct1 = Product::factory()->create(['type' => ProductType::TopicPack]);
    $childProduct1->resources()->attach($resourceA->id);

    $childProduct2 = Product::factory()->create(['type' => ProductType::Workbook]);
    $childProduct2->resources()->attach($resourceB->id);

    $bundle = Product::factory()->create(['type' => ProductType::Bundle]);
    $bundle->childProducts()->attach([$childProduct1->id, $childProduct2->id]);

    $order = Order::factory()->create(['user_id' => $user->id, 'paid_at' => now()]);
    OrderItem::factory()->create(['order_id' => $order->id, 'product_id' => $bundle->id]);

    OrderPaid::dispatch($order, Payment::factory()->create(['order_id' => $order->id]));

    expect(Entitlement::where('user_id', $user->id)->count())->toBe(2);
    expect(Entitlement::where('user_id', $user->id)->pluck('resource_id')->toArray())
        ->toContain($resourceA->id)
        ->toContain($resourceB->id);
});
