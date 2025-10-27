<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
// use App\Models\Mime; // décommente si tu veux aussi peupler la table mime

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin par défaut
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'              => 'Admin',
                'password'          => Hash::make('adminadmin'),
                'role'              => 'admin',     // nécessite la migration role
                'email_verified_at' => now(),
            ]
        );

        User::factory(20)->create();
    }
}
