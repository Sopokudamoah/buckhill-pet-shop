<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;

test('admin can delete product', function () {
    $user = User::factory()->isAdmin()->create();

    $token = $user->createToken()->plainTextToken;

    $product = Product::factory()->for(Category::factory())->create();

    $this->assertModelExists($product);
    $response = apiTest()->withToken($token)->delete(route('api.v1.product.delete', $product));

    $response->assertStatus(200);
    $this->assertNull(Product::find($product->id));
    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('delete-product-200.json', $response->content());
});

test('user cannot delete product if uuid is invalid', function () {
    $user = User::factory()->isAdmin()->create();

    $token = $user->createToken()->plainTextToken;

    $response = apiTest()->withToken($token)->delete(route('api.v1.product.delete', fake()->uuid()));

    $response->assertStatus(404);

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('delete-product-404.json', $response->content());
});
