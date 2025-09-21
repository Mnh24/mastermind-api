<?php

// database/seeders/SuperAdminSeeder.php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'superadmin@mastermind.local';
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'SuperMNH',
                'password' => Hash::make('SuperSecret123!'),
                'role' => 'superadmin',
                'approved_at' => now(),
            ]
        );
    }
}
