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

        $this->call(
            UserSeeder::class
        );
    }
}
