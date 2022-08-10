<?php

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;

test('user can view payment information', function () {
    $user = User::factory()->create();

    $token = $user->createToken()->plainTextToken;

    $payment = Payment::factory()->cashOnDelivery()->create();
    Order::factory()->for($payment)->for($user)->create();

    $this->assertModelExists($payment);
    $response = apiTest()->withToken($token)->get(route('api.v1.payments.show', $payment));

    $response->assertStatus(200);
    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('show-payment-200.json', $response->content());
});

test('user cannot view payment for an order belonging to another user', function () {
    $user = User::factory()->create();
    $token = $user->createToken()->plainTextToken;

    $payment = Payment::factory()->cashOnDelivery()->create();

    $response = apiTest()->withToken($token)->get(route('api.v1.payments.show', $payment));

    $response->assertStatus(404);

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('show-payment-404.json', $response->content());
});
