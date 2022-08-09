<?php

use App\Models\Category;
use App\Models\User;

test('user can delete category', function () {
    $user = User::factory()->create();

    $token = $user->createToken()->plainTextToken;

    $brand = Category::factory()->create();

    $this->assertModelExists($brand);
    $response = apiTest()->withToken($token)->delete(route('api.v1.category.delete', $brand));

    $response->assertStatus(200);
    $this->assertNull(Category::find($brand->id));
    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('delete-category-200.json', $response->content());
});

test('user cannot delete category if uuid is invalid', function () {
    $user = User::factory()->create();

    $token = $user->createToken()->plainTextToken;

    $response = apiTest()->withToken($token)->delete(route('api.v1.category.delete', fake()->uuid()));

    $response->assertStatus(404);

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('delete-category-404.json', $response->content());
});
