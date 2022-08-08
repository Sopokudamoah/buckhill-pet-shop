<?php

use App\Models\User;

test('user can log in with correct credentials', function () {
    $password = Str::random(10);
    $user = User::factory()->create(['password' => bcrypt($password)]);

    $this->assertModelExists($user);
    $this->assertNull($user->last_login_at);

    $response = apiTest()->post(route('api.v1.user.login'), ['email' => $user->email, 'password' => $password]);

    $response->assertStatus(200);
    $response->assertJsonStructure(['success', 'message', 'data']);

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('user-login-200.json', $response->content());

    $user->refresh();
    $this->assertNotNull($user->last_login_at);
});


test('user cannot log in with incorrect credentials', function () {
    $admin = User::factory()->create();

    $this->assertModelExists($admin);

    $response = apiTest()->post(route('api.v1.user.login'), ['email' => $admin->email, 'password' => Str::random(10)]);

    $response->assertStatus(422);
    $response->assertSee("Invalid login credentials");

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('user-login-422.json', $response->content());
});


test('user cannot log in as an admin', function () {
    $password = Str::random(10);
    $user = User::factory()->create(['is_admin' => 0, 'password' => bcrypt($password)]);

    $this->assertModelExists($user);

    $response = apiTest()->post(route('api.v1.admin.login'), ['email' => $user->email, 'password' => $password]);

    $response->assertStatus(422);
    $response->assertJsonStructure(['success', 'message', 'data']);

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('admin-user-login-422.json', $response->content());
});
