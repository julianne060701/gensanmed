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
            'Employee'
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
        ];

        foreach ($permissions as $roleName => $rolePermissions) {
            $role = Role::where('name', $roleName)->first();

            foreach ($rolePermissions as $permission) {
                $perm = Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
                $role->givePermissionTo($perm);
            }
        }
    }
}
