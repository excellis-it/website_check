<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Define Permissions
        $permissions = [
            // Dashboard
            'view-dashboard',

            // User Management
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            'manage-users', // General management access

            // URL Management
            'view-urls',
            'create-urls',
            'edit-urls',
            'delete-urls',
            'check-urls',   // For manual status check
            'manage-urls',

            // Role & Permission Management
            'view-roles',
            'create-roles',
            'edit-roles',
            'delete-roles',
            'manage-roles',

            'view-permissions',
            'create-permissions',
            'edit-permissions',
            'delete-permissions',
            'manage-permissions',

            // General Settings (if applicable)
            'view-settings',
            'manage-settings',
        ];

        // Create Permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 2. Define Roles and Assign Permissions

        // ADMIN Role - Gets ALL permissions
        $adminRole = Role::firstOrCreate(['name' => 'SUPER ADMIN']);
        $adminRole->givePermissionTo(Permission::all());

        // CUSTOMER Role - Limited permissions (Example)
        $customerRole = Role::firstOrCreate(['name' => 'CUSTOMER']);
   
        $customerRole->givePermissionTo([
            'view-dashboard',

            'view-users',
            'create-users',
            'edit-users',
            // No delete-users
            'manage-users',

            'view-urls',
            'create-urls',
            'edit-urls',
            'check-urls',
            'manage-urls',
            // No delete-urls
        ]);

        echo "Roles and Permissions seeded successfully.\n";
    }
}
