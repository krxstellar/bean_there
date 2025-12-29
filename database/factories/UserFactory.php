<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

// @EXTENDS \ILLUMINATE\DATABASE\ELOQUENT\FACTORIES\FACTORY<\APP\MODELS\USER>
class UserFactory extends Factory
{
    // THE CURRENT PASSWORD BEING USED BY THE FACTORY
    protected static ?string $password;

    // DEFINE THE MODEL'S DEFAULT STATE
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    // INDICATE THAT THE MODEL'S EMAIL ADDRESS SHOULD BE UNVERIFIED
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
