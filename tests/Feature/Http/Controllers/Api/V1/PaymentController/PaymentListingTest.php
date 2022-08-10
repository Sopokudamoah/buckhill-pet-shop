<?php

use App\Models\Payment;
use App\Models\User;

test('user can access payment list', function () {
    $user = User::factory()->isAdmin()->create();
    $token = $user->createToken()->plainTextToken;

    //Seed DB with payments for user
    Payment::factory()->bankTransfer()->count(30)->create();

    $response = apiTest()->withToken($token)->get(route('api.v1.payments.index', ['page' => 1]));
    $response->assertStatus(200);

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('payments-listing-200.json', $response->content());
});
