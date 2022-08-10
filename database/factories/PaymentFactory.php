<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [];
    }

    public function creditCard()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'credit_card',
                'details' => [
                    'holder_name' => fake()->name(),
                    'number' => fake()->creditCardNumber(),
                    'ccv' => fake()->numberBetween(111, 999),
                    'expire_date' => fake()->creditCardExpirationDate()->format('m/y')
                ],
            ];
        });
    }


    public function cashOnDelivery()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'cash_on_delivery',
                'details' => [
                    'first_name' => fake()->firstName(),
                    'last_name' => fake()->lastName(),
                    'address' => fake()->address(),
                ],
            ];
        });
    }


    public function bankTransfer()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'bank_transfer',
                'details' => [
                    'swift' => fake()->swiftBicNumber(),
                    'iban' => fake()->iban(),
                    'name' => fake()->name(),
                ],
            ];
        });
    }
}
