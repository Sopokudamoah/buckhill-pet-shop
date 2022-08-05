<?php

use App\Models\User;

test('admin can log out', function () {
    $admin = User::factory()->create(['is_admin' => 1]);
    $token = $admin->createToken()->plainTextToken;

    $response = apiTest()->withToken($token)->get(route('api.v1.admin.logout'));
    $response->assertStatus(200);
});


test('admin cannot log out without authentication', function () {
    $response = apiTest()->get(route('api.v1.admin.logout'));
    $response->assertStatus(401);
});


test('user cannot log out without is admin access', function () {
    $admin = User::factory()->create(['is_admin' => 0]);
    $token = $admin->createToken()->plainTextToken;

    $response = apiTest()->withToken($token)->get(route('api.v1.admin.logout'));
    $response->assertStatus(401);
});
