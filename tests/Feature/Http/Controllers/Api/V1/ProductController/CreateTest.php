<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;

test('user can create product', function () {
    $user = User::factory()->create();
    $token = $user->createToken()->plainTextToken;

    $brand = Brand::factory()->create();
    $product = Product::factory()->for(Category::factory())->make([
        'metadata' => ['brand' => $brand->uuid]
    ]);

    $response = apiTest()->withToken($token)->post(
        route('api.v1.product.create'),
        $product->only([
            'title',
            'metadata',
            'category_uuid',
            'price',
            'description'
        ])
    );

    $response->assertStatus(200);
    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('create-product-200.json', $response->content());
});


test('user cannot create product with invalid requirements', function () {
    $user = User::factory()->create();
    $token = $user->createToken()->plainTextToken;

    $uuid = fake()->uuid;

    $brand = Brand::firstWhere('uuid', '=', $uuid);
    $this->assertNull($brand);

    $product = Product::factory()->for(Category::factory())->make([
        'metadata' => ['brand' => $uuid]
    ]);

    $response = apiTest()->withToken($token)->post(
        route('api.v1.product.create'),
        $product->only([
            'title',
            'metadata',
            'category_uuid',
            'price',
            'description'
        ])
    );

    $response->assertStatus(422);
    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('create-product-422.json', $response->content());
});
