<?php

use App\Models\Payment;
use App\Models\User;

test('user can update payment using credit card', function () {
    $user = User::factory()->isAdmin()->create();
    $token = $user->createToken()->plainTextToken;

    $payment = Payment::factory()->creditCard()->create();

    $new_payment = Payment::factory()->bankTransfer()->make();

    $this->assertNotEquals($new_payment->type, $payment->type);

    $payment->type = $new_payment->type;
    $payment->details = $new_payment->details;

    $response = apiTest()->withToken($token)->put(
        route('api.v1.payments.update', $payment),
        $payment->only(['type', 'details'])
    );

    $response->assertStatus(200);

    $payment->refresh();

    $this->assertTrue($payment->type == $new_payment->type);

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('update-payment-200.json', $response->content());
});

test('user cannot update payment with invalid requirements', function () {
    $user = User::factory()->isAdmin()->create();
    $token = $user->createToken()->plainTextToken;

    $payment = Payment::factory()->creditCard()->create();

    $new_payment = Payment::factory()->bankTransfer()->make();


    $this->assertNotEquals($new_payment->type, $payment->type);

    $payment->type = $new_payment->type;

    $response = apiTest()->withToken($token)->put(
        route('api.v1.payments.update', $payment),
        $payment->only(['type', 'details'])
    );

    $response->assertStatus(422);

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('update-payment-422.json', $response->content());
});
