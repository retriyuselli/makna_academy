<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin User
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@maknaacademy.com'],
            [
                'name' => 'Super Administrator',
                'avatar_url' => null,
                'password' => Hash::make('password123'),
                'phone' => '081234567890',
                'date_of_birth' => '1990-01-01',
                'gender' => 'male',
                'role' => 'super_admin',
                'email_verified_at' => now(),
            ]
        );

        // Create Admin Users
        $admin1 = User::firstOrCreate(
            ['email' => 'ahmad.wijaya@maknaacademy.com'],
            [
                'name' => 'Dr. Ahmad Wijaya',
                'avatar_url' => null,
                'password' => Hash::make('password123'),
                'phone' => '081234567891',
                'date_of_birth' => '1985-03-15',
                'gender' => 'male',
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $admin2 = User::firstOrCreate(
            ['email' => 'siti.nurhaliza@maknaacademy.com'],
            [
                'name' => 'Prof. Siti Nurhaliza',
                'avatar_url' => null,
                'password' => Hash::make('password123'),
                'phone' => '081234567892',
                'date_of_birth' => '1988-07-20',
                'gender' => 'female',
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Create Customer Users (Students)
        $customer1 = User::firstOrCreate(
            ['email' => 'budi.santoso@gmail.com'],
            [
                'name' => 'Budi Santoso',
                'avatar_url' => null,
                'password' => Hash::make('password123'),
                'phone' => '081234567893',
                'date_of_birth' => '2000-05-10',
                'gender' => 'male',
                'role' => 'customer',
                'email_verified_at' => now(),
            ]
        );

        $customer2 = User::firstOrCreate(
            ['email' => 'andi.pratama@gmail.com'],
            [
                'name' => 'Andi Pratama',
                'avatar_url' => null,
                'password' => Hash::make('password123'),
                'phone' => '081234567894',
                'date_of_birth' => '1999-11-25',
                'gender' => 'male',
                'role' => 'customer',
                'email_verified_at' => now(),
            ]
        );

        // Total: 5 users (1 super_admin + 2 admin + 2 customer)
        $this->command->info('UserSeeder completed successfully!');
        $this->command->info('Created 5 users total:');
        $this->command->info('- Super Admin: 1 user');
        $this->command->info('- Admin: 2 users');  
        $this->command->info('- Customer: 2 users');
    }
}
