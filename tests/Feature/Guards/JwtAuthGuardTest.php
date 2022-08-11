<?php

use App\Models\User;

test('can use jwt token to make requests', function () {
    $admin = User::factory()->create();
    $token = $admin->createToken()->plainTextToken;

    $response = apiTest()->withToken($token)->get(route('api.v1.user.index'));

    $response->assertStatus(200);
});


test('cannot use expired jwt token to make requests', function () {
    $admin = User::factory()->create();
    $token = $admin->createToken(null, ['*'], now()->subMinute())->plainTextToken;

    $response = apiTest()->withToken($token)->get(route('api.v1.user.index'));

    $response->assertStatus(400);
});
