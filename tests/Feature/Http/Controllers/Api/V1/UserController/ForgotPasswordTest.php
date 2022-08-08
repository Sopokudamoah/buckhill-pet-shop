<?php

use App\Models\User;

test('user can request password reset with email', function () {
    $user = User::factory()->create();

    $response = apiTest()->post(route('api.v1.user.forgot-password'), $user->only('email'));

    $response->assertStatus(200);

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('user-forgot-password-200.json', $response->content());
});


test('user cannot request password reset with invalid email', function () {
    $email = fake()->safeEmail();

    $this->assertDatabaseCount(User::query()->whereEmail($email), 0);

    $response = apiTest()->post(route('api.v1.user.forgot-password'), [
        'email' => $email
    ]);

    $response->assertStatus(422);

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('user-forgot-password-422.json', $response->content());
});
