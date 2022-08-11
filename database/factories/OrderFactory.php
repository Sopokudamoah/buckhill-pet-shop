<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Payment;
use App\Models\Product;
use BadMethodCallException;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        $products = Product::query()->inRandomOrder()->take(fake()->numberBetween(1, 5))->get();

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
            'order_status_id' => OrderStatus::inRandomOrder()->value('id')
        ];
    }

    /**
     * @param $method
     * @return OrderFactory
     * @throws BadMethodCallException
     * @props credit|cr|skdf
     */

    public function withPayment($method = null)
    {
        if (empty($method)) {
            $method = fake()->randomElement(['credit_card', 'cash_on_delivery', 'bank_transfer']);
        }

        return $this->state(function (array $attributes) use ($method) {
            $method = Str::camel($method);
            return [
                'payment_id' => in_array($attributes['order_status_id'], ['4', '5']) ? null : Payment::factory(
                )->{$method}()->create(),
                'shipped_at' => $attributes['order_status_id'] == '5' ? fake()->dateTime() : null
            ];
        });
    }
}
