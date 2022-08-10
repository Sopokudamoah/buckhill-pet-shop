<?php

use App\Models\User;

test('user can access order status list', function () {
    $user = User::factory()->create();
    $token = $user->createToken()->plainTextToken;

    $response = apiTest()->withToken($token)->get(route('api.v1.order-status.index', ['page' => 1]));
    $response->assertStatus(200);

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('order-statuses-listing-200.json', $response->content());
});
