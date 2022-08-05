<?php

use App\Models\User;

test('admin can create admin account', function () {
    $admin = User::factory()->isAdmin()->create();
    $token = $admin->createToken()->plainTextToken;

    $user = User::factory()->plainPassword()->make();
    $user->password_confirmation = $user->password;

    $response = apiTest()->withToken($token)->post(
        route('api.v1.admin.create'),
        $user->only(
            [
                'first_name',
                'last_name',
                'email',
                'password',
                'password_confirmation',
                'avatar',
                'address',
                'phone_number',
                'is_marketing',
            ]
        )
    );

    $response->assertStatus(200);
    $response->assertJsonStructure(['success', 'message', 'data']);

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('admin-user-create-200.json', $response->content());
});

test('admin cannot create admin account with invalid data', function () {
    $admin = User::factory()->isAdmin()->create();
    $token = $admin->createToken()->plainTextToken;

    $user = User::factory()->plainPassword()->make();
    $user->password_confirmation = fake()->password();

    $response = apiTest()->withToken($token)->post(
        route('api.v1.admin.create'),
        $user->only([
            'first_name',
            'last_name',
            'email',
            'password',
            'password_confirmation',
            'avatar',
            'address',
            'phone_number',
            'is_marketing',
        ])
    );

    $response->assertStatus(422);
    $response->assertJsonStructure(['success', 'message', 'data']);

    $response->assertSee("The password confirmation does not match.");

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('admin-user-create-422.json', $response->content());
});
