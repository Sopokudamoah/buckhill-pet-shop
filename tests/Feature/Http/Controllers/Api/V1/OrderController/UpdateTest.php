<?php

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;

test('user can update order', function () {
    $user = User::factory()->create();
    $token = $user->createToken()->plainTextToken;

    $order = Order::factory()->for($user)->withPayment('credit_card')->create();

    $this->assertModelExists($order);

    $new_payment = Payment::factory()->bankTransfer()->create();
    $this->assertNotEquals($order->payment_id, $new_payment->id);

    $order->payment_id = $new_payment->id;

    $response = apiTest()->withToken($token)->put(
        route('api.v1.order.update', $order),
        $order->only(['payment_id'])
    );

    $response->assertStatus(200);

    $order->refresh();
    $this->assertEquals($order->payment_id, $new_payment->id);
    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('update-order-200.json', $response->content());
});


test('user cannot update order with invalid requirements', function () {
    $user = User::factory()->create();
    $token = $user->createToken()->plainTextToken;

    $order = Order::factory()->for($user)->withPayment('credit_card')->create();

    $this->assertModelExists($order);

    $new_payment = Payment::factory()->bankTransfer()->create();
    $this->assertNotEquals($order->payment_id, $new_payment->id);
    $order->payment_id = null;

    $response = apiTest()->withToken($token)->put(
        route('api.v1.order.update', $order),
        $order->only(['payment_id'])
    );

    $response->assertStatus(422);
    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('update-order-422.json', $response->content());
});
