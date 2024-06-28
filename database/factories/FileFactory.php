<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\File>
 */
class FileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fileName = fake()->name();
        return [
            'uuid' => fake()->uuid(),
            'name' => $fileName,
            'path' => storage_path('pet-shop/' . $fileName),
            'size' => fake()->numberBetween(),
            'type' => fake()->randomElement(['jpeg', 'jpg', 'png', 'webp', 'gif']),
        ];
    }
}
