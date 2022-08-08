<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

test('user can change password with valid token', function () {
    $user = User::factory()->create();
    $password = fake()->password();

    $token = Str::random(60);
    $builder = DB::table('password_resets')->where('email', '=', $user->email);

    $builder->delete();

    $builder->insert([
        'email' => $user->email,
        'token' => $token,
        'created_at' => now()
    ]);

    $response = apiTest()->post(route('api.v1.user.reset-password-token'), [
        'token' => $token,
        'email' => $user->email,
        'password' => $password,
        'password_confirmation' => $password
    ]);

    $response->assertStatus(200);

    $this->assertEquals(0, $builder->count());



    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('user-password-reset-200.json', $response->content());
});


test('user cannot reset password with expired token', function () {
    $user = User::factory()->create();
    $password = fake()->password();

    $token = Str::random(60);
    $builder = DB::table('password_resets')->where('email', '=', $user->email);

    $builder->delete();

    $builder->insert([
        'email' => $user->email,
        'token' => $token,
        'created_at' => now()->subHours(2)
    ]);

    $response = apiTest()->post(route('api.v1.user.reset-password-token'), [
        'token' => $token,
        'email' => $user->email,
        'password' => $password,
        'password_confirmation' => $password
    ]);

    $response->assertStatus(403);

    $this->assertEquals(0, $builder->count());


    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('user-password-reset-403.json', $response->content());
});



test('user cannot reset password with non-existing token', function () {
    $user = User::factory()->create();
    $password = fake()->password();

    $token = Str::random(60);

    $response = apiTest()->post(route('api.v1.user.reset-password-token'), [
        'token' => $token,
        'email' => $user->email,
        'password' => $password,
        'password_confirmation' => $password
    ]);

    $response->assertStatus(422);


    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('user-password-reset-422.json', $response->content());
});
