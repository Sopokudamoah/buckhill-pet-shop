<?php

use App\Models\Payment;
use App\Models\User;

test('user can create payment using credit card', function () {
    $user = User::factory()->create();
    $token = $user->createToken()->plainTextToken;

    $payment = Payment::factory()->creditCard()->make();

    $response = apiTest()->withToken($token)->post(route('api.v1.payments.create'), $payment->toArray());

    $response->assertStatus(200);
    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('create-payment-200.json', $response->content());
});


test('user can create payment using bank transfer', function () {
    $user = User::factory()->create();
    $token = $user->createToken()->plainTextToken;

    $payment = Payment::factory()->bankTransfer()->make();

    $response = apiTest()->withToken($token)->post(route('api.v1.payments.create'), $payment->toArray());

    $response->assertStatus(200);
    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('create-payment-200.json', $response->content());
});


test('user can create payment and pay cash on delivery', function () {
    $user = User::factory()->create();
    $token = $user->createToken()->plainTextToken;

    $payment = Payment::factory()->cashOnDelivery()->make();

    $response = apiTest()->withToken($token)->post(route('api.v1.payments.create'), $payment->toArray());

    $response->assertStatus(200);
    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('create-payment-200.json', $response->content());
});

test('user cannot create payment with invalid requirements', function () {
    $user = User::factory()->create();
    $token = $user->createToken()->plainTextToken;

    $payment = Payment::factory()->make();

    $response = apiTest()->withToken($token)->post(route('api.v1.payments.create'), $payment->toArray());

    $response->assertStatus(422);
    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('create-payment-422.json', $response->content());
});
