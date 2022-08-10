<?php

use App\Models\OrderStatus;
use App\Models\User;

test('admin can update order status', function () {
    $user = User::factory()->isAdmin()->create();
    $token = $user->createToken()->plainTextToken;

    $order_status = OrderStatus::factory()->create();

    $this->assertModelExists($order_status);

    $new_title = fake()->words(2, true);
    $this->assertNotEquals($order_status->title, $new_title);

    $order_status->title = $new_title;

    $response = apiTest()->withToken($token)->put(
        route('api.v1.order-status.update', $order_status),
        $order_status->only([
            'title',
        ])
    );

    $response->assertStatus(200);

    $order_status->refresh();

    $this->assertEquals($order_status->title, $new_title);
    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('update-order-status-200.json', $response->content());
});
