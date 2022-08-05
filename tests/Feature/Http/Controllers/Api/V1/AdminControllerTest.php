<?php

use App\Models\User;

test('admin can log in with correct credentials', function () {
    $password = Str::random(10);
    $admin = User::factory()->create(['is_admin' => 1, 'password' => bcrypt($password)]);

    $this->assertModelExists($admin);

    $response = apiTest()->post(route('api.v1.admin.login', ['email' => $admin->email, 'password' => $password]));

    $response->assertStatus(200);
    $response->assertJsonStructure(['success', 'message', 'data']);
});


test('admin cannot log in with incorrect credentials', function () {
    $admin = User::factory()->create(['is_admin' => 1]);

    $this->assertModelExists($admin);

    $response = apiTest()->post(route('api.v1.admin.login', ['email' => $admin->email, 'password' => Str::random(10)]));

    $response->assertStatus(422);
    $response->assertSee("Invalid login credentials");
});


test('user cannot log in as an admin', function () {
    $password = Str::random(10);
    $user = User::factory()->create(['is_admin' => 0, 'password' => bcrypt($password)]);

    $this->assertModelExists($user);

    $response = apiTest()->post(route('api.v1.admin.login', ['email' => $user->email, 'password' => $password]));

    $response->assertStatus(422);
    $response->assertJsonStructure(['success', 'message', 'data']);
});


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
