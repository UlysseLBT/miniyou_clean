<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Post;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // si tu seed aussi des users avec une factory :
            'user_id' => User::factory(),

            'titre' => fake('fr_FR')->sentence(4),
            'texte' => fake('fr_FR')->paragraph(),

            // nom de colonne aligné avec ta migration
            'url' => fake('fr_FR')->url(),
        ];
    }
}
