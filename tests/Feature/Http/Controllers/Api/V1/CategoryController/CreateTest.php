<?php

use App\Models\Category;
use App\Models\User;

test('user can create category', function () {
    $user = User::factory()->isAdmin()->create();
    $token = $user->createToken()->plainTextToken;

    $brand = Category::factory()->make();

    $response = apiTest()->withToken($token)->post(
        route('api.v1.category.create'),
        $brand->only([
            'title',
        ])
    );

    $response->assertStatus(200);
    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('create-category-200.json', $response->content());
});


test('admin cannot create category with invalid requirements', function () {
    $user = User::factory()->isAdmin()->create();
    $token = $user->createToken()->plainTextToken;

    $brand = Category::factory()->make(['title' => '']);

    $response = apiTest()->withToken($token)->post(
        route('api.v1.category.create'),
        $brand->only([
            'title',
        ])
    );

    $response->assertStatus(422);
    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('create-category-422.json', $response->content());
});
