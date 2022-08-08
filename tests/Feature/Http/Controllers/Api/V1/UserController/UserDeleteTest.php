<?php

use App\Models\User;

test('user can delete account', function () {
    $user = User::factory()->create();
    $token = $user->createToken()->plainTextToken;

    $response = apiTest()->withToken($token)->delete(route('api.v1.user.delete'));

    $response->assertStatus(200);
    $this->assertModelMissing($user);

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('user-delete-200.json', $response->content());
});
