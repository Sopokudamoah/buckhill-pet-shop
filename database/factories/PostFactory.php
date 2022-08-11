<?php

namespace Database\Factories;

use App\Models\File;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => fake()->sentence(),
            'content' => fake()->sentences(10, true),
            'metadata' => ['author' => fake()->name(), 'image' => File::factory()->create()->uuid]
        ];
    }
}
