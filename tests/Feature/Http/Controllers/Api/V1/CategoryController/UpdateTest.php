<?php

use App\Models\Category;
use App\Models\User;

test('user can update category', function () {
    $user = User::factory()->create();
    $token = $user->createToken()->plainTextToken;

    $brand = Category::factory()->create();

    $this->assertModelExists($brand);

    $new_title = fake()->words(2, true);
    $this->assertNotEquals($brand->title, $new_title);

    $brand->title = $new_title;

    $response = apiTest()->withToken($token)->put(
        route('api.v1.category.update', $brand),
        $brand->only([
            'title',
        ])
    );

    $response->assertStatus(200);

    $brand->refresh();

    $this->assertEquals($brand->title, $new_title);
    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('update-category-200.json', $response->content());
});


test('user cannot update category with invalid requirements', function () {
    $user = User::factory()->create();
    $token = $user->createToken()->plainTextToken;

    $brand = Category::factory()->create(['title'=> '']);

    $response = apiTest()->withToken($token)->put(
        route('api.v1.category.update', $brand),
        $brand->only([
            'title',
        ])
    );

    $response->assertStatus(422);
    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('update-category-422.json', $response->content());
});
