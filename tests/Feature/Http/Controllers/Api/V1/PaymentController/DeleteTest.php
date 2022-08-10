<?php

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;

test('user can delete payment', function () {
    $user = User::factory()->create();

    $token = $user->createToken()->plainTextToken;

    $payment = Payment::factory()->cashOnDelivery()->create();
    Order::factory()->for($payment)->for($user)->create();

    $this->assertModelExists($payment);

    $response = apiTest()->withToken($token)->delete(route('api.v1.payments.delete', $payment));

    $response->assertStatus(200);
    $this->assertNull(Payment::find($payment->id));
    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('delete-payment-200.json', $response->content());
});

test('user cannot delete payment', function () {
    $user = User::factory()->create();

    $token = $user->createToken()->plainTextToken;

    $payment = Payment::factory()->cashOnDelivery()->create();
    Order::factory()->for($payment)->for(User::factory())->create();

    $this->assertModelExists($payment);

    $response = apiTest()->withToken($token)->delete(route('api.v1.payments.delete', $payment));

    $response->assertStatus(404);

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('delete-payment-404.json', $response->content());
});
