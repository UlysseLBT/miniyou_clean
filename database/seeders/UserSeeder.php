<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

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
        User::factory()->count(20)->create();
    }
}
