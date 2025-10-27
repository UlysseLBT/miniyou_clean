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
                'username'          => 'admin',     // nécessite la migration username
                'password'          => Hash::make('adminadmin'),
                'role'              => 'admin',     // nécessite la migration role
                'email_verified_at' => now(),
            ]
        );

        // Utilisateurs de démo
        User::factory(5)->create();

        // (Optionnel) Peupler la table mime :
        /*
        Mime::upsert([
            ['type'=>'image','subtype'=>'jpeg','full'=>'image/jpeg','extensions'=>['jpg','jpeg'],'is_allowed'=>true,'max_size_mb'=>50,'updated_at'=>now(),'created_at'=>now()],
            ['type'=>'image','subtype'=>'png','full'=>'image/png','extensions'=>['png'],'is_allowed'=>true,'max_size_mb'=>50,'updated_at'=>now(),'created_at'=>now()],
            ['type'=>'image','subtype'=>'webp','full'=>'image/webp','extensions'=>['webp'],'is_allowed'=>true,'max_size_mb'=>50,'updated_at'=>now(),'created_at'=>now()],
            ['type'=>'video','subtype'=>'mp4','full'=>'video/mp4','extensions'=>['mp4'],'is_allowed'=>true,'max_size_mb'=>200,'updated_at'=>now(),'created_at'=>now()],
        ], ['full'], ['type','subtype','extensions','is_allowed','max_size_mb','updated_at']);
        */
    }
}
