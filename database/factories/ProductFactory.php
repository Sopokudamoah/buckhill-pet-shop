<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\File;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => fake()->word(),
            'price' => fake()->numberBetween(10, 1000),
            'description' => fake()->sentences(4, true),
            'category_uuid' => Category::query()->inRandomOrder()->value('uuid'),
            'metadata' => [
                'brand' => Brand::query()->inRandomOrder()->value('uuid'),
                'image' => File::factory()->create()->uuid
            ]
        ];
    }
}
