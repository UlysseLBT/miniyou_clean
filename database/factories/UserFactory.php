<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        $email = fake()->unique()->safeEmail();
        $base = Str::before($email, '@'); // ex: rcartwright
        return [
            'name' => fake()->name(),
            'username' => fake()->unique()->userName(),
            'email' => $email,
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // ou Hash::make(...)
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
