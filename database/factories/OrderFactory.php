<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @property float $total
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $products = Product::factory()->for(Category::factory())->count(fake()->numberBetween(1, 5))->create();

        $products = $products->map(function (Product $product) {
            $quantity = fake()->numberBetween(1, 5);
            $amount = $product->price * $quantity;
            $this->total = +$amount;
            return ['product' => $product->uuid, 'quantity' => $quantity];
        });

        return [
            'products' => $products->toArray(),
            'delivery_fee' => $this->total > 500 ? null : 15,
            'address' => ['billing' => fake()->streetAddress(), 'shipping' => fake()->streetAddress()],
            'amount' => $this->total,
        ];
    }
}
