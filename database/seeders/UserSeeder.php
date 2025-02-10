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
                'lname' => 'gensanmed',
                'email' => 'admin@gensanmed.com',
                'role' => 'administrator',
                //'profile_image_url' => 'images/default-image.jpg',
                'password' => 'password',
            ],
            [
                'name' => 'IT',
                'lname' => 'gensanmed',
                'email' => 'specialstaff@anvy.com',
                'role' => 'IT',
               // 'profile_image_url' => 'images/default-image.jpg',
                'password' => 'password',
            ],
            [
                'name' => 'engineer',
                'lname' => 'gensanmed',
                'email' => 'specialuser@anvy.com',
                'role' => 'engineer',
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
                'lname' => $userData['lname'],
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
