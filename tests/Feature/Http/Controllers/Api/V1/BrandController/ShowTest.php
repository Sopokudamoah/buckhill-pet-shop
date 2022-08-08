<?php

use App\Models\Brand;
use App\Models\User;

test('user can view brand information', function () {
    $user = User::factory()->create();

    $token = $user->createToken()->plainTextToken;

    $brand = Brand::factory()->create();

    $this->assertModelExists($brand);
    $response = apiTest()->withToken($token)->get(route('api.v1.brand.show', $brand));

    $response->assertStatus(200);
    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('show-brand-200.json', $response->content());
});

test('user cannot view product information if uuid is invalid', function () {
    $user = User::factory()->create();

    $token = $user->createToken()->plainTextToken;

    $response = apiTest()->withToken($token)->get(route('api.v1.brand.show', fake()->uuid()));

    $response->assertStatus(404);

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('show-brand-404.json', $response->content());
});
