<?php

use App\Models\Category;
use App\Models\User;

test('user can view category information', function () {
    $user = User::factory()->create();

    $token = $user->createToken()->plainTextToken;

    $brand = Category::factory()->create();

    $this->assertModelExists($brand);
    $response = apiTest()->withToken($token)->get(route('api.v1.category.show', $brand));

    $response->assertStatus(200);
    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('show-category-200.json', $response->content());
});

test('user cannot view product information if uuid is invalid', function () {
    $user = User::factory()->create();

    $token = $user->createToken()->plainTextToken;

    $response = apiTest()->withToken($token)->get(route('api.v1.category.show', fake()->uuid()));

    $response->assertStatus(404);

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('show-category-404.json', $response->content());
});
