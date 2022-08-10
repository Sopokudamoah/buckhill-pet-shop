<?php

use App\Models\Order;
use App\Models\User;

test('user can view order information', function () {
    $user = User::factory()->create();
    $token = $user->createToken()->plainTextToken;

    $order = Order::factory()->for($user)->withPayment()->create();

    $this->assertModelExists($order);
    $response = apiTest()->withToken($token)->get(route('api.v1.order.show', $order));

    $response->assertStatus(200);
    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('show-order-200.json', $response->content());
});

test('user cannot view order of another user', function () {
    $user = User::factory()->create();

    $token = $user->createToken()->plainTextToken;

    $order = Order::factory()->for(User::factory())->withPayment()->create();

    $this->assertNotEquals($user->id, $order->user_id);

    $response = apiTest()->withToken($token)->get(route('api.v1.order.show', $order));

    $response->assertStatus(404);

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('show-order-404.json', $response->content());
});
