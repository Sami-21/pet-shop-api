<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JwtToken>
 */
class JwtTokenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => Str::uuid(),
            'token_title' => fake()->name(),
            'expired_at' => fake()->dateTimeBetween('+1 minutes', '+60 minutes'),
        ];
    }
}
