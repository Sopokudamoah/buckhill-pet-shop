<?php

use App\Models\Category;
use App\Models\User;

test('admin can create order status', function () {
    $user = User::factory()->isAdmin()->create();
    $token = $user->createToken()->plainTextToken;

    $order_status = Category::factory()->make();

    $response = apiTest()->withToken($token)->post(
        route('api.v1.order-status.create'),
        $order_status->only(['title'])
    );

    $response->assertStatus(200);
    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('create-order-status-200.json', $response->content());
});
