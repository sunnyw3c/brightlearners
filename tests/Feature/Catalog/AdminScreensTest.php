<?php

use App\Domains\Catalog\Models\Product;
use App\Domains\Content\Models\LearningResource;
use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->superAdmin = User::factory()->create();
    $this->superAdmin->assignRole(Role::findOrCreate('super-admin', 'web'));
});

test('the product list, create, view and edit screens render, including relation managers', function () {
    $resource = LearningResource::factory()->create();
    $product = Product::factory()->create();
    $product->resources()->attach($resource->id, ['sort_order' => 0, 'version_policy' => 'current']);

    $bundle = Product::factory()->bundle()->create();
    $bundle->childProducts()->attach($product->id, ['sort_order' => 0]);

    $this->actingAs($this->superAdmin)->get('/admin/products')->assertOk();
    $this->actingAs($this->superAdmin)->get('/admin/products/create')->assertOk();
    $this->actingAs($this->superAdmin)->get("/admin/products/{$product->id}")->assertOk();
    $this->actingAs($this->superAdmin)->get("/admin/products/{$product->id}/edit")->assertOk();
    $this->actingAs($this->superAdmin)->get("/admin/products/{$bundle->id}/edit")->assertOk();
});
