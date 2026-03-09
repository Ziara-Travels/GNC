<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if superadmin already exists
        if (!User::where('email', 'superadmin@example.com')->exists()) {
            User::create([
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
                'email_verified_at' => now()
            ]);
        }
    }
}
