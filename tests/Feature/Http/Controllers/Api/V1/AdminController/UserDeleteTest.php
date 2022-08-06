<?php

use App\Models\User;

test('admin can delete user', function () {
    $admin = User::factory()->isAdmin()->create();
    $token = $admin->createToken()->plainTextToken;

    $user = User::factory()->create();

    $response = apiTest()->withToken($token)->delete(route('api.v1.admin.user-delete', $user));

    $response->assertStatus(200);
    $this->assertModelMissing($user);

    #The line below is of generating response samples for API documentation
    Storage::drive('responses')->put('admin-user-delete-200.json', $response->content());
});


test('admin cannot delete admin account', function () {
    $admin = User::factory()->isAdmin()->create();

    $token = $admin->createToken()->plainTextToken;
    $user = User::factory()->isAdmin()->create();

    $response = apiTest()->withToken($token)->delete(route('api.v1.admin.user-delete', $user));

    $response->assertStatus(403);

    #The line below is of generating response samples for API documentation
    Storage::drive('responses')->put('admin-user-delete-403.json', $response->content());
});


test('admin cannot delete a non-existing user account', function () {
    $admin = User::factory()->isAdmin()->create();

    $token = $admin->createToken()->plainTextToken;

    $uuid = fake()->uuid();

    $response = apiTest()->withToken($token)->delete(route('api.v1.admin.user-delete', $uuid));

    $response->assertStatus(404);

    #The line below is of generating response samples for API documentation
    Storage::drive('responses')->put('admin-user-delete-404.json', $response->content());
});
