<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'Administrator', 'guard_name' => 'web']);
        $purchaserRole = Role::firstOrCreate(['name' => 'Purchaser', 'guard_name' => 'web']);
        $itRole = Role::firstOrCreate(['name' => 'IT', 'guard_name' => 'web']);
        $engineerRole = Role::firstOrCreate(['name' => 'Engineer', 'guard_name' => 'web']);
        $staffRole = Role::firstOrCreate(['name' => 'Staff', 'guard_name' => 'web']);
        $employeeRole = Role::firstOrCreate(['name' => 'Employee', 'guard_name' => 'web']);

        // Create permissions
        $permissions = [
            'view-admin-menu',
            'view-engineer-menu',
            'view-it-menu',
            'view-purchaser-menu',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Assign permissions to roles
        $adminRole->givePermissionTo(['view-admin-menu']);
        $purchaserRole->givePermissionTo(['view-purchaser-menu']);
        $itRole->givePermissionTo(['view-it-menu']);
        $engineerRole->givePermissionTo(['view-engineer-menu']);
    }
}
