<?php

use App\Models\Order;
use App\Models\User;

test('user can create order', function () {
    $user = User::factory()->create();
    $token = $user->createToken()->plainTextToken;

    $order = Order::factory()->for($user)->withPayment()->make();

    $response = apiTest()->withToken($token)->post(route('api.v1.order.create'), $order->toArray());

    $response->assertStatus(200);
    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('create-order-200.json', $response->content());
});


test('user cannot create order with invalid requirements', function () {
    $user = User::factory()->create();
    $token = $user->createToken()->plainTextToken;

    $order = Order::factory()->for($user)->make(['address' => null]);

    $response = apiTest()->withToken($token)->post(route('api.v1.order.create'), $order->toArray());

    $response->assertStatus(422);
    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('create-order-422.json', $response->content());
});
