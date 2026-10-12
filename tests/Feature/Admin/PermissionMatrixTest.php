<?php

use App\Domains\Accounts\Actions\SyncStaffRoles;
use App\Domains\Catalog\Actions\ChangeProductPrice;
use App\Domains\Catalog\Models\Product;
use App\Domains\Content\Models\LearningResource;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Ensure all standard roles and permissions exist
    $roles = [
        'super-admin',
        'business-admin',
        'content-manager',
        'teacher-reviewer',
        'customer-support',
        'finance',
        'marketing',
    ];

    $permissions = [
        'curriculum.view', 'curriculum.edit', 'curriculum.review',
        'resources.view', 'resources.edit', 'resources.review', 'resources.approve', 'resources.publish',
        'products.view', 'products.edit-copy', 'products.edit-price',
        'coupons.manage', 'orders.view', 'orders.manage',
        'refunds.issue', 'refunds.request',
        'entitlements.override', 'entitlements.override-limited',
        'programmes.view', 'programmes.edit', 'programmes.review',
        'plans.edit-price', 'customers.view', 'customers.view-limited',
        'articles.edit', 'redirects.manage', 'roles.manage',
        'audit.view', 'audit.view-own-area', 'audit.view-limited',
        'analytics.view', 'analytics.export', 'system.view',
    ];

    foreach ($permissions as $perm) {
        Permission::findOrCreate($perm, 'web');
    }

    foreach ($roles as $roleName) {
        Role::findOrCreate($roleName, 'web');
    }

    Role::findByName('super-admin')->givePermissionTo($permissions);
    Role::findByName('business-admin')->givePermissionTo($permissions);

    Role::findByName('content-manager')->givePermissionTo([
        'curriculum.view', 'curriculum.edit', 'curriculum.review',
        'resources.view', 'resources.edit', 'resources.publish',
        'products.view', 'products.edit-copy',
        'orders.view', 'programmes.view', 'programmes.edit',
        'articles.edit', 'audit.view-own-area',
    ]);

    Role::findByName('teacher-reviewer')->givePermissionTo([
        'curriculum.view', 'curriculum.review',
        'resources.view', 'resources.review', 'resources.approve',
        'products.view', 'programmes.view', 'programmes.review',
    ]);

    Role::findByName('customer-support')->givePermissionTo([
        'curriculum.view', 'resources.view', 'products.view',
        'orders.view', 'orders.manage', 'refunds.request',
        'entitlements.override-limited', 'programmes.view',
        'customers.view-limited', 'audit.view-limited',
    ]);

    Role::findByName('finance')->givePermissionTo([
        'products.view', 'orders.view', 'orders.manage',
        'refunds.issue', 'refunds.request', 'customers.view-limited',
        'audit.view-limited', 'analytics.view',
    ]);

    Role::findByName('marketing')->givePermissionTo([
        'products.view', 'programmes.view', 'articles.edit',
        'redirects.manage', 'analytics.view',
    ]);
});

test('a content reviewer or content manager cannot change a product price', function () {
    $contentManager = User::factory()->create();
    $contentManager->assignRole('content-manager');

    $product = Product::factory()->create([
        'regular_price' => 50000,
        'sale_price' => null,
    ]);

    $this->actingAs($contentManager);

    expect(Gate::forUser($contentManager)->allows('editPrice', $product))->toBeFalse();

    expect(fn () => app(ChangeProductPrice::class)->handle($product, 60000))
        ->toThrow(AuthorizationException::class);
});

test('a teacher reviewer cannot change a product price', function () {
    $teacher = User::factory()->create();
    $teacher->assignRole('teacher-reviewer');

    $product = Product::factory()->create([
        'regular_price' => 50000,
    ]);

    $this->actingAs($teacher);

    expect(Gate::forUser($teacher)->allows('editPrice', $product))->toBeFalse();

    expect(fn () => app(ChangeProductPrice::class)->handle($product, 70000))
        ->toThrow(AuthorizationException::class);
});

test('customer support cannot assign an admin or staff role', function () {
    $support = User::factory()->create();
    $support->assignRole('customer-support');

    $targetUser = User::factory()->create();

    $this->actingAs($support);

    expect(Gate::forUser($support)->allows('roles.manage'))->toBeFalse();

    expect(fn () => app(SyncStaffRoles::class)->handle($targetUser, ['super-admin'], $support))
        ->toThrow(AuthorizationException::class);
});

test('content manager cannot issue refunds', function () {
    $contentManager = User::factory()->create();
    $contentManager->assignRole('content-manager');

    expect($contentManager->can('refunds.issue'))->toBeFalse();
});

test('customer support cannot publish resources', function () {
    $support = User::factory()->create();
    $support->assignRole('customer-support');

    $resource = LearningResource::factory()->create();

    expect(Gate::forUser($support)->allows('publish', $resource))->toBeFalse();
});

test('only super admin and business admin can manage staff accounts', function () {
    $superAdmin = User::factory()->create();
    $superAdmin->assignRole('super-admin');

    $businessAdmin = User::factory()->create();
    $businessAdmin->assignRole('business-admin');

    $finance = User::factory()->create();
    $finance->assignRole('finance');

    expect(Gate::forUser($superAdmin)->allows('viewAny', User::class))->toBeTrue();
    expect(Gate::forUser($businessAdmin)->allows('viewAny', User::class))->toBeTrue();
    expect(Gate::forUser($finance)->allows('viewAny', User::class))->toBeFalse();
});
