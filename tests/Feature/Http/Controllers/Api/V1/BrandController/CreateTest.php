<?php

use App\Models\Brand;
use App\Models\User;

test('user can create brand', function () {
    $user = User::factory()->create();
    $token = $user->createToken()->plainTextToken;

    $brand = Brand::factory()->make();

    $response = apiTest()->withToken($token)->post(
        route('api.v1.brand.create'),
        $brand->only([
            'title',
        ])
    );

    $response->assertStatus(200);
    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('create-brand-200.json', $response->content());
});


test('user cannot create brand with invalid requirements', function () {
    $user = User::factory()->create();
    $token = $user->createToken()->plainTextToken;

    $brand = Brand::factory()->make(['title' => '']);

    $response = apiTest()->withToken($token)->post(
        route('api.v1.brand.create'),
        $brand->only([
            'title',
        ])
    );

    $response->assertStatus(422);
    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('create-brand-422.json', $response->content());
});
