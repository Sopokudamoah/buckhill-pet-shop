<?php

namespace Database\Factories;

use App\Models\File;
use App\Models\Promotion;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Promotion>
 */
class PromotionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $start_date = fake()->dateTimeBetween('-2 month');
        return [
            'title' => fake()->sentence(),
            'content' => fake()->sentences(10, true),
            'metadata' => [
                'valid_from' => $start_date->format('Y-m-d'),
                'valid_to' => Carbon::parse($start_date)->addDays(fake()->numberBetween(7, 14))->format('Y-m-d'),
                'image' => File::factory()->create()->uuid
            ]
        ];
    }
}
