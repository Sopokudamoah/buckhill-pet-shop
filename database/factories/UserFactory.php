<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'is_admin' => 0,
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => fake()->dateTime(),
            'password' => bcrypt('userpassword'),
            'address' => fake()->address(),
            'phone_number' => fake()->e164PhoneNumber(),
            'is_marketing' => fake()->randomElement(['0', '1']),
            'avatar' => fake()->uuid()
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    public function isAdmin()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_admin' => 1,
                'password' => bcrypt('admin')
            ];
        });
    }

    public function plainPassword()
    {
        return $this->state(function (array $attributes) {
            return [
                'password' => $attributes['is_admin'] ? 'admin' : 'userpassword'
            ];
        });
    }
}
