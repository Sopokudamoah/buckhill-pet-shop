<?php

use App\Models\OrderStatus;
use App\Models\User;

test('user can delete order status', function () {
    $user = User::factory()->create();

    $token = $user->createToken()->plainTextToken;

    $order_status = OrderStatus::factory()->create();

    $this->assertModelExists($order_status);
    $response = apiTest()->withToken($token)->delete(route('api.v1.order-status.delete', $order_status));

    $response->assertStatus(200);
    $this->assertNull(OrderStatus::find($order_status->id));
    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('delete-order-status-200.json', $response->content());
});
