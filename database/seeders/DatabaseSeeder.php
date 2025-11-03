<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
// use App\Models\Mime; // décommente si tu veux aussi peupler la table mime

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // utilisateurs aléatoires (avec emails uniques via la factory)
        User::factory(5)->create();

        // admin idempotent
        User::updateOrCreate(
            ['email' => 'admin@example.com'],     // critère d'unicité
            [
                'name' => 'Admin',
                'username' => 'admin',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]
        );
    }
}
