<?php

use App\Models\User;

test('admin can edit user', function () {
    $admin = User::factory()->isAdmin()->create();

    $token = $admin->createToken()->plainTextToken;

    $user = User::factory()->create();

    $response = apiTest()->withToken($token)->put(route('api.v1.admin.user-edit', $user), [
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
//    Storage::drive('responses')->put('admin-user-edit-200.json', $response->content());
});


test('admin cannot edit admin account', function () {
    $admin = User::factory()->isAdmin()->create();

    $token = $admin->createToken()->plainTextToken;
    $user = User::factory()->isAdmin()->create();

    $response = apiTest()->withToken($token)->put(route('api.v1.admin.user-edit', $user), [
        'first_name' => fake()->firstName(),
        'last_name' => fake()->lastName()
    ]);

    $response->assertStatus(403);

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('admin-user-edit-403.json', $response->content());
});


test('admin cannot edit user email with existing email', function () {
    $admin = User::factory()->isAdmin()->create();

    $token = $admin->createToken()->plainTextToken;
    $user = User::factory()->create();

    $response = apiTest()->withToken($token)->put(route('api.v1.admin.user-edit', $user), [
        'email' => $admin->email
    ]);

    $response->assertStatus(422);

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('admin-user-edit-422.json', $response->content());
});
