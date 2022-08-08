<?php

use App\Models\User;

test('user can edit their information', function () {
    $user = User::factory()->create();
    $token = $user->createToken()->plainTextToken;

    $response = apiTest()->withToken($token)->put(route('api.v1.user.edit'), [
        'first_name' => $firstname = fake()->firstName(),
        'last_name' => $lastname = fake()->lastName(),
        'email' => $user->email
    ]);

    $this->assertNotEquals($firstname, $user->first_name);
    $this->assertNotEquals($lastname, $user->last_name);

    $response->assertStatus(200);
    $user->refresh();

    $this->assertEquals($firstname, $user->first_name);
    $this->assertEquals($lastname, $user->last_name);

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('user-edit-200.json', $response->content());
});


test('user cannot edit email with existing email', function () {
    $user = User::factory()->create();
    $token = $user->createToken()->plainTextToken;

    $dummy = User::factory()->create();

    $this->assertModelExists($dummy);
    $response = apiTest()->withToken($token)->put(route('api.v1.user.edit', $user), [
        'email' => $dummy->email
    ]);

    $response->assertStatus(422);

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('user-edit-422.json', $response->content());
});
