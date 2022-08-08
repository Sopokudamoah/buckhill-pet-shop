<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;

test('user can view product information', function () {
    $user = User::factory()->create();

    $token = $user->createToken()->plainTextToken;

    $product = Product::factory()->for(Category::factory())->create();

    $this->assertModelExists($product);
    $response = apiTest()->withToken($token)->get(route('api.v1.product.show', $product));

    $response->assertStatus(200);
    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('show-product-200.json', $response->content());
});

test('user cannot view product information if uuid is invalid', function () {
    $user = User::factory()->create();

    $token = $user->createToken()->plainTextToken;

    $response = apiTest()->withToken($token)->get(route('api.v1.product.show', fake()->uuid()));

    $response->assertStatus(404);

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('show-product-404.json', $response->content());
});
