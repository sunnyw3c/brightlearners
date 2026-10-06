<?php

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Spatie\Permission\Models\Role;

test('a parent cannot open the admin panel', function () {
    $parent = User::factory()->create();

    $this->actingAs($parent)->get('/admin')->assertForbidden();
});

test('each staff role can open the admin panel', function (string $role) {
    $staff = User::factory()->create();
    $staff->assignRole(Role::findOrCreate($role, 'web'));

    $this->actingAs($staff)->get('/admin')->assertOk();
})->with([
    'super-admin',
    'business-admin',
    'content-manager',
    'teacher-reviewer',
    'customer-support',
    'finance',
    'marketing',
]);

test('only super admin and business admin can open the staff users screen', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $contentManager = User::factory()->create();
    $contentManager->assignRole(Role::findOrCreate('content-manager', 'web'));

    $this->actingAs($contentManager)->get('/admin/users')->assertForbidden();

    $businessAdmin = User::factory()->create();
    $businessAdmin->assignRole(Role::findOrCreate('business-admin', 'web'));

    $this->actingAs($businessAdmin)->get('/admin/users')->assertOk();
});

test('the staff users screen only lists users who hold a role', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $businessAdmin = User::factory()->create();
    $businessAdmin->assignRole(Role::findOrCreate('business-admin', 'web'));

    $parent = User::factory()->create();

    $response = $this->actingAs($businessAdmin)->get('/admin/users');

    $response->assertOk();
    $response->assertDontSee($parent->email);
    $response->assertSee($businessAdmin->email);
});
