<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * The staff roles, from ../../docs/reference/roles-and-permissions.md.
     *
     * @var list<string>
     */
    private const ROLES = [
        'super-admin',
        'business-admin',
        'content-manager',
        'teacher-reviewer',
        'customer-support',
        'finance',
        'marketing',
    ];

    /**
     * Permission name to the roles that hold it, from the permission names
     * table in ../../docs/reference/roles-and-permissions.md. Safe to run
     * again: roles are synced to exactly this list every time.
     *
     * @var array<string, list<string>>
     */
    private const PERMISSIONS = [
        'curriculum.view' => ['super-admin', 'business-admin', 'content-manager', 'teacher-reviewer', 'customer-support'],
        'curriculum.edit' => ['super-admin', 'business-admin', 'content-manager'],
        'curriculum.review' => ['super-admin', 'business-admin', 'content-manager', 'teacher-reviewer'],
        'resources.view' => ['super-admin', 'business-admin', 'content-manager', 'teacher-reviewer', 'customer-support'],
        'resources.edit' => ['super-admin', 'business-admin', 'content-manager'],
        'resources.review' => ['super-admin', 'business-admin', 'teacher-reviewer'],
        'resources.approve' => ['super-admin', 'business-admin', 'teacher-reviewer'],
        'resources.publish' => ['super-admin', 'business-admin', 'content-manager'],
        'products.view' => ['super-admin', 'business-admin', 'content-manager', 'teacher-reviewer', 'customer-support', 'finance', 'marketing'],
        'products.edit-copy' => ['super-admin', 'business-admin', 'content-manager'],
        'products.edit-price' => ['super-admin', 'business-admin'],
        'coupons.manage' => ['super-admin', 'business-admin'],
        'orders.view' => ['super-admin', 'business-admin', 'content-manager', 'customer-support', 'finance'],
        'orders.manage' => ['super-admin', 'business-admin', 'customer-support', 'finance'],
        'refunds.issue' => ['super-admin', 'business-admin', 'finance'],
        'refunds.request' => ['super-admin', 'business-admin', 'customer-support', 'finance'],
        'entitlements.override' => ['super-admin', 'business-admin'],
        'entitlements.override-limited' => ['super-admin', 'business-admin', 'customer-support'],
        'programmes.view' => ['super-admin', 'business-admin', 'content-manager', 'teacher-reviewer', 'customer-support', 'marketing'],
        'programmes.edit' => ['super-admin', 'business-admin', 'content-manager'],
        'programmes.review' => ['super-admin', 'business-admin', 'teacher-reviewer'],
        'plans.edit-price' => ['super-admin', 'business-admin'],
        'customers.view' => ['super-admin', 'business-admin'],
        'customers.view-limited' => ['super-admin', 'business-admin', 'customer-support', 'finance'],
        'articles.edit' => ['super-admin', 'business-admin', 'content-manager', 'marketing'],
        'redirects.manage' => ['super-admin', 'business-admin', 'marketing'],
        'roles.manage' => ['super-admin', 'business-admin'],
        'audit.view' => ['super-admin', 'business-admin'],
        'audit.view-own-area' => ['super-admin', 'business-admin', 'content-manager'],
        'audit.view-limited' => ['super-admin', 'business-admin', 'customer-support', 'finance'],
        'analytics.view' => ['super-admin', 'business-admin', 'finance', 'marketing'],
        'analytics.export' => ['super-admin', 'business-admin'],
        'system.view' => ['super-admin'],
    ];

    /**
     * Run the database seeds.
     *
     * Safe to run again after every deploy: permissions are created once,
     * and each role's permission set is synced to exactly what the
     * reference matrix says, so a changed matrix is reflected on the next
     * deploy without manual cleanup.
     */
    public function run(): void
    {
        foreach (self::ROLES as $role) {
            Role::findOrCreate($role, 'web');
        }

        $rolePermissions = array_fill_keys(self::ROLES, []);

        foreach (self::PERMISSIONS as $permission => $roles) {
            Permission::findOrCreate($permission, 'web');

            foreach ($roles as $role) {
                $rolePermissions[$role][] = $permission;
            }
        }

        foreach ($rolePermissions as $role => $permissions) {
            Role::findOrCreate($role, 'web')->syncPermissions($permissions);
        }
    }
}
