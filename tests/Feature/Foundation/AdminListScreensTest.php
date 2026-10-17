<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->superAdmin = User::factory()->create();
    $this->superAdmin->assignRole(Role::findOrCreate('super-admin', 'web'));
});

test('admin list screens with table actions render', function (string $path) {
    $this->actingAs($this->superAdmin)->get($path)->assertOk();
})->with([
    '/admin/resource-corrections',
    '/admin/entitlements',
    '/admin/payments',
    '/admin/coupons',
    '/admin/learning-programs',
    '/admin/orders',
    '/admin/audit-logs',
    '/admin/customers',
    '/admin/assessments',
]);
