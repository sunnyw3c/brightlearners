<?php

use App\Domains\Access\Models\Entitlement;
use App\Domains\Access\Policies\EntitlementPolicy;
use App\Domains\Catalog\Enums\ProductStatus;
use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Policies\ProductPolicy;
use App\Domains\Commerce\Models\Order;
use App\Domains\Commerce\Policies\OrderPolicy;
use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourceVersion;
use App\Domains\Content\Policies\LearningResourcePolicy;
use App\Domains\Content\Policies\ResourceVersionPolicy;
use App\Domains\Payments\Models\Payment;
use App\Domains\Payments\Policies\PaymentPolicy;
use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

test('policies forbid hard deletion on critical business records', function () {
    $staff = User::factory()->create();
    Role::findOrCreate('business-admin', 'web');
    $staff->assignRole('business-admin');

    $order = Order::factory()->create();
    $payment = Payment::factory()->create();
    $entitlement = Entitlement::factory()->create();
    $version = ResourceVersion::factory()->create();

    $publishedResource = LearningResource::factory()->create([
        'status' => ResourceStatus::Published,
    ]);

    $publishedProduct = Product::factory()->create([
        'status' => ProductStatus::Active,
    ]);

    $targetUser = User::factory()->create();

    // Check direct policy methods return false
    expect(app(OrderPolicy::class)->delete($staff, $order))->toBeFalse();
    expect(app(OrderPolicy::class)->forceDelete($staff, $order))->toBeFalse();

    expect(app(PaymentPolicy::class)->delete($staff, $payment))->toBeFalse();
    expect(app(PaymentPolicy::class)->forceDelete($staff, $payment))->toBeFalse();

    expect(app(EntitlementPolicy::class)->delete($staff, $entitlement))->toBeFalse();
    expect(app(EntitlementPolicy::class)->forceDelete($staff, $entitlement))->toBeFalse();

    expect(app(ResourceVersionPolicy::class)->delete($staff, $version))->toBeFalse();
    expect(app(ResourceVersionPolicy::class)->forceDelete($staff, $version))->toBeFalse();

    expect(app(LearningResourcePolicy::class)->delete($staff, $publishedResource))->toBeFalse();
    expect(app(LearningResourcePolicy::class)->forceDelete($staff, $publishedResource))->toBeFalse();

    expect(app(ProductPolicy::class)->delete($staff, $publishedProduct))->toBeFalse();
    expect(app(ProductPolicy::class)->forceDelete($staff, $publishedProduct))->toBeFalse();

    expect(app(UserPolicy::class)->delete($staff, $targetUser))->toBeFalse();
    expect(app(UserPolicy::class)->forceDelete($staff, $targetUser))->toBeFalse();

    // Check Gate integration with staff role
    expect(Gate::forUser($staff)->allows('delete', $order))->toBeFalse();
    expect(Gate::forUser($staff)->allows('delete', $payment))->toBeFalse();
    expect(Gate::forUser($staff)->allows('delete', $entitlement))->toBeFalse();
    expect(Gate::forUser($staff)->allows('delete', $version))->toBeFalse();
    expect(Gate::forUser($staff)->allows('delete', $publishedResource))->toBeFalse();
    expect(Gate::forUser($staff)->allows('delete', $publishedProduct))->toBeFalse();
    expect(Gate::forUser($staff)->allows('delete', $targetUser))->toBeFalse();
});
