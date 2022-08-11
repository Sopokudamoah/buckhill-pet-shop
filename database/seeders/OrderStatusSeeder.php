<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            '1' => 'open',
            '2' => 'pending',
            '3' => 'payment',
            '4' => 'paid',
            '5' => 'shipped',
            '6' => 'cancelled'
        ];

        foreach ($statuses as $id => $status) {
            OrderStatus::updateOrCreate(['id' => $id], ['title' => $status, 'id' => $id]);
        }
    }
}
