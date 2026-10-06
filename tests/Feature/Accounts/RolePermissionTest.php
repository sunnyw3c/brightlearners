<?php

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});

test('content manager cannot issue refunds', function () {
    $user = User::factory()->create();
    $user->assignRole(Role::findOrCreate('content-manager', 'web'));

    expect($user->can('refunds.issue'))->toBeFalse();
});

test('support cannot publish resources', function () {
    $user = User::factory()->create();
    $user->assignRole(Role::findOrCreate('customer-support', 'web'));

    expect($user->can('resources.publish'))->toBeFalse();
});

test('content manager cannot change a product price', function () {
    $user = User::factory()->create();
    $user->assignRole(Role::findOrCreate('content-manager', 'web'));

    expect($user->can('products.edit-price'))->toBeFalse();
    expect($user->can('products.edit-copy'))->toBeTrue();
});

test('only super admin and business admin manage roles', function () {
    foreach (['content-manager', 'teacher-reviewer', 'customer-support', 'finance', 'marketing'] as $role) {
        $user = User::factory()->create();
        $user->assignRole(Role::findOrCreate($role, 'web'));

        expect($user->can('roles.manage'))->toBeFalse();
    }

    foreach (['super-admin', 'business-admin'] as $role) {
        $user = User::factory()->create();
        $user->assignRole(Role::findOrCreate($role, 'web'));

        expect($user->can('roles.manage'))->toBeTrue();
    }
});

test('super admin passes every permission check via Gate::before', function () {
    $user = User::factory()->create();
    $user->assignRole(Role::findOrCreate('super-admin', 'web'));

    expect($user->can('a-permission-that-does-not-exist'))->toBeTrue();
});
