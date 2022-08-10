<?php

use App\Models\OrderStatus;
use App\Models\User;

test('admin can view order status', function () {
    $user = User::factory()->isAdmin()->create();

    $token = $user->createToken()->plainTextToken;

    $order_status = OrderStatus::factory()->create();

    $this->assertModelExists($order_status);
    $response = apiTest()->withToken($token)->get(route('api.v1.order-status.show', $order_status));

    $response->assertStatus(200);
    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('show-order-status-200.json', $response->content());
});
