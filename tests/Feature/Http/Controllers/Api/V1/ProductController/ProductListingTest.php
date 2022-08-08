<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;

test('user can access product list', function () {
    //Seed DB with some products
    Product::factory()->for(Category::factory())->count(30)->create();

    $admin = User::factory()->create();
    $token = $admin->createToken()->plainTextToken;

    $response = apiTest()->withToken($token)->get(route('api.v1.product.index', ['page' => 1]));
    $response->assertStatus(200);

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('products-listing-200.json', $response->content());
});

test('user cannot filter by disallowed fields', function () {
    $admin = User::factory()->create();
    $token = $admin->createToken()->plainTextToken;

    $response = apiTest()->withToken($token)->get(
        route('api.v1.product.index', ['page' => 1, 'filter' => ['deleted_at' => now()->format('Y-m-d')]])
    );
    $response->assertStatus(400);

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('products-listing-400.json', $response->content());
});
