<?php

use App\Models\User;

test('user can create account', function () {
    $user = User::factory()->plainPassword()->make();
    $user->password_confirmation = $user->password;

    $response = apiTest()->post(
        route('api.v1.user.create'),
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
//    Storage::drive('responses')->put('user-create-200.json', $response->content());
});

test('user cannot create account with invalid data', function () {
    $user = User::factory()->plainPassword()->make();
    $user->password_confirmation = fake()->password();

    $response = apiTest()->post(
        route('api.v1.user.create'),
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
//    Storage::drive('responses')->put('user-create-422.json', $response->content());
});
