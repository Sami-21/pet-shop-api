<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_uuid' => Category::factory()->create()->uuid,
            'uuid' => fake()->uuid(),
            'title' => fake()->name(),
            'price' => fake()->randomFloat(2, 100, 999999),
            'description' => fake()->text(),
            'metadata' => json_encode([
                'brand' => Str::uuid(),
                'file' => Str::uuid(),
            ]),
        ];
    }
}
