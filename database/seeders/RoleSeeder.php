<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'Administrator', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Purchaser', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'IT', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Engineer', 'guard_name' => 'web']);
    }
}
