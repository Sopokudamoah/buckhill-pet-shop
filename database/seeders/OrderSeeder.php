<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::factory()->count(20)->create();
        foreach ($users as $user) {
            Order::factory()->withPayment()->for($user)->count(fake()->numberBetween(1, 10))->create();
        }
    }
}
