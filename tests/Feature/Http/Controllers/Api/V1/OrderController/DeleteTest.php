<?php

use App\Models\Order;
use App\Models\User;

test('user can delete order', function () {
    $user = User::factory()->create();

    $token = $user->createToken()->plainTextToken;

    $order = Order::factory()->for($user)->withPayment()->create();

    $this->assertModelExists($order);
    $response = apiTest()->withToken($token)->delete(route('api.v1.order.delete', $order));

    $response->assertStatus(200);
    $this->assertNull(Order::find($order->id));
    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('delete-order-200.json', $response->content());
});

test('user cannot delete order if uuid is invalid', function () {
    $user = User::factory()->create();

    $token = $user->createToken()->plainTextToken;

    $response = apiTest()->withToken($token)->delete(route('api.v1.order.delete', fake()->uuid()));

    $response->assertStatus(404);

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('delete-order-404.json', $response->content());
});
