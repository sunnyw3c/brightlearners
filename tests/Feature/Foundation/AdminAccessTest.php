<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

test('a guest is redirected to the admin login', function () {
    $this->get('/admin')->assertRedirect(route('filament.admin.auth.login'));
});

test('an authenticated user without a staff role cannot access admin', function () {
    $this->actingAs(User::factory()->create())
        ->get('/admin')
        ->assertForbidden();
});

test('a super admin can access the admin panel', function () {
    $superAdmin = User::factory()->create();
    $superAdmin->assignRole(Role::findOrCreate('super-admin', 'web'));

    $this->actingAs($superAdmin)
        ->get('/admin')
        ->assertOk();
});
