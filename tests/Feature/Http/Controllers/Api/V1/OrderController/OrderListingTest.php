<?php

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;

test('admin can access order list', function () {
    $user = User::factory()->isAdmin()->create();
    $token = $user->createToken()->plainTextToken;

    //Seed DB with orders for user
    Order::factory()->for($user)->for(Payment::factory())->count(30)
        ->state(
            new Sequence(
                fn ($sequence) => [
                    'payment_id' => Payment::factory()->{fake()->randomElement(
                        ['bankTransfer', 'creditCard', 'cashOnDelivery']
                    )}(),
                ],
            )
        )->create();

    $this->assertDatabaseCount($user->orders(), 30);


    $response = apiTest()->withToken($token)->get(route('api.v1.order.index', ['page' => 1]));
    $response->assertStatus(200);

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('orders-listing-200.json', $response->content());
});

test('admin cannot filter by disallowed fields', function () {
    $user = User::factory()->isAdmin()->create();
    $token = $user->createToken()->plainTextToken;

    $response = apiTest()->withToken($token)->get(
        route('api.v1.order.index', ['page' => 1, 'filter' => ['user_id' => $user->id]])
    );
    $response->assertStatus(400);

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('orders-listing-400.json', $response->content());
});
