<?php

use App\Domains\Catalog\Models\Product;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Auth\Access\AuthorizationException;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});

/**
 * Only a role with `products.edit-price` may change a live price
 * (docs/plan/phase-06-product-catalogue.md, "Admin (Filament)"). The
 * check in `Product::booted()` fires on every update regardless of
 * entry point, so a crafted request that skips the Filament form
 * cannot bypass it.
 */
test('a content-manager cannot change a price even by writing to the model directly', function () {
    $contentManager = User::factory()->create();
    $contentManager->assignRole(Role::findOrCreate('content-manager', 'web'));
    $this->actingAs($contentManager);

    $product = Product::factory()->create(['regular_price' => 10_000]);

    expect(fn () => $product->update(['regular_price' => 5_000]))
        ->toThrow(AuthorizationException::class);

    expect($product->refresh()->regular_price)->toBe(10_000);
});

test('a content-manager can still edit product copy', function () {
    $contentManager = User::factory()->create();
    $contentManager->assignRole(Role::findOrCreate('content-manager', 'web'));
    $this->actingAs($contentManager);

    $product = Product::factory()->create(['name' => 'Old name']);

    $product->update(['name' => 'New name']);

    expect($product->refresh()->name)->toBe('New name');
});

test('a business-admin can change a price', function () {
    $admin = User::factory()->create();
    $admin->assignRole(Role::findOrCreate('business-admin', 'web'));
    $this->actingAs($admin);

    $product = Product::factory()->create(['regular_price' => 10_000]);

    $product->update(['regular_price' => 5_000]);

    expect($product->refresh()->regular_price)->toBe(5_000);
});

test('the product policy refuses editPrice for a content-manager', function () {
    $contentManager = User::factory()->create();
    $contentManager->assignRole(Role::findOrCreate('content-manager', 'web'));

    $product = Product::factory()->create();

    expect($contentManager->can('editPrice', $product))->toBeFalse();
});
