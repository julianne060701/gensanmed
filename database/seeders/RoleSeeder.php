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
        $roles = [
            'Administrator',
            'Purchaser',
            'IT',
            'Engineer',
            'Staff',
            'Employee',
            'Head', // Added new role
            'MMO'
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // Create permissions
        $permissions = [
            'Administrator' => ['view-admin-menu'],
            'Purchaser' => ['view-purchaser-menu'],
            'IT' => ['view-it-menu'],
            'Engineer' => ['view-engineer-menu'],
            'Staff' => ['view-staff-menu'],
            'Employee' => ['view-employee-menu'],
            'Head' => ['view-head-menu'],// Added new permission
            'mmo' => ['view-mmo-menu']
        ];

        foreach ($permissions as $roleName => $rolePermissions) {
            $role = Role::where('name', $roleName)->first();
            foreach ($rolePermissions as $permissionName) {
                $permission = Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
                $role->givePermissionTo($permission);
            }
        }
    }
}
