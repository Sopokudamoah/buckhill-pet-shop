<?php

use App\Models\User;

test('user can retrieve account information', function () {
    $user = User::factory()->create();
    $token = $user->createToken()->plainTextToken;

    $response = apiTest()->withToken($token)->get(route('api.v1.user.index'));

    $response->assertStatus(200);

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('user-information-200.json', $response->content());
});
