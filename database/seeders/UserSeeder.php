<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'admin',
                'email' => 'admin@gensanmed.com',
                'role' => 'Administrator',
                //'profile_image_url' => 'images/default-image.jpg',
                'password' => 'password',
            ],
            [
                'name' => 'IT',
                'email' => 'IT@gensanmed',
                'role' => 'IT',
               // 'profile_image_url' => 'images/default-image.jpg',
                'password' => 'password',
            ],
            [
                'name' => 'engineer',
                'email' => 'engineer@gensanmed',
                'role' => 'Engineer',
                //'profile_image_url' => 'images/default-image.jpg',
                'password' => 'password',
            ],
            [
                'name' => 'purchaser',
                'email' => 'purchaser@gensanmed',
                'role' => 'Purchaser',
                //'profile_image_url' => 'images/default-image.jpg',
                'password' => 'password',
            ],
            [
                'name' => 'staff',
                'email' => 'staff@gensanmed',
                'role' => 'Staff',
                //'profile_image_url' => 'images/default-image.jpg',
                'password' => 'password',
            ],
            [
                'name' => 'employee',
                'email' => 'employee@gensanmed',
                'role' => 'Employee',
                //'profile_image_url' => 'images/default-image.jpg',
                'password' => 'password',
            ],
            [
                'name' => 'head',
                'email' => 'head@gensanmed',
                'role' => 'Head',
                //'profile_image_url' => 'images/default-image.jpg',
                'password' => 'password',
            ],
        ];
        
        foreach ($users as $userData) {
           
            if (User::where('email', $userData['email'])->exists()) {
                echo "User with email {$userData['email']} already exists. Skipping creation.\n";
                continue;
            }
        

            User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                //'profile_image_url' => $userData['profile_image_url'],
                'password' => Hash::make($userData['password']),
            ])
                ->assignRole($userData['role'])
                ->givePermissionTo('can use all');
        
            echo "User with email {$userData['email']} created successfully.\n";
        }
        

    }
}
