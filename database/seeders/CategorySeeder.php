<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\File;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = Brand::all();
        Category::factory()->count(10)->create()->each(function (Category $category) use ($brands) {
            $category->products()->saveMany(Product::factory()->count(20)->make([
                'metadata' => json_encode(['brand' => $brands->random()->uuid, 'file' => File::factory()->create()->uuid])
            ]));
        });
    }
}
