<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;

test('user can update product', function () {
    $user = User::factory()->create();
    $token = $user->createToken()->plainTextToken;

    $brand = Brand::factory()->create();
    $product = Product::factory()->for(Category::factory())->create([
        'metadata' => ['brand' => $brand->uuid]
    ]);

    $this->assertModelExists($product);

    $new_title = fake()->words(2, true);
    $this->assertNotEquals($product->title, $new_title);

    $product->title = $new_title;

    $response = apiTest()->withToken($token)->put(
        route('api.v1.product.update', $product),
        $product->only([
            'title',
            'metadata',
            'category_uuid',
            'price',
            'description'
        ])
    );

    $response->assertStatus(200);

    $product->refresh();

    $this->assertEquals($product->title, $new_title);

    $this->assertNotNull($product->brand());
    $this->assertTrue($product->brand() instanceof Brand);
    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('update-product-200.json', $response->content());
});


test('user cannot create product with invalid requirements', function () {
    $user = User::factory()->create();
    $token = $user->createToken()->plainTextToken;

    $uuid = fake()->uuid;

    $brand = Brand::firstWhere('uuid', '=', $uuid);
    $this->assertNull($brand);

    $product = Product::factory()->for(Category::factory())->create([
        'metadata' => ['brand' => $uuid]
    ]);

    $response = apiTest()->withToken($token)->put(
        route('api.v1.product.update', $product),
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
//    Storage::drive('responses')->put('update-product-422.json', $response->content());
});
