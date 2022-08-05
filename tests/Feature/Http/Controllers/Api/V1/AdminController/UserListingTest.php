<?php

use App\Models\User;

test('admin can access users', function () {
    $admin = User::factory()->isAdmin()->create();
    $token = $admin->createToken()->plainTextToken;

    $response = apiTest()->withToken($token)->get(route('api.v1.admin.user-listing', ['page' => 1]));
    $response->assertStatus(200);

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('admin-user-listing-200.json', $response->content());
});


test('admin cannot filter by disallowed fields', function () {
    $admin = User::factory()->isAdmin()->create();
    $token = $admin->createToken()->plainTextToken;

    $response = apiTest()->withToken($token)->get(
        route('api.v1.admin.user-listing', ['page' => 1, 'filter' => ['uuid' => fake()->uuid()]])
    );
    $response->assertStatus(400);

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('admin-user-listing-400.json', $response->content());
});
