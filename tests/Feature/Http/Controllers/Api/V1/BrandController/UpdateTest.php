<?php

use App\Models\Brand;
use App\Models\User;

test('admin can update brand', function () {
    $user = User::factory()->isAdmin()->create();
    $token = $user->createToken()->plainTextToken;

    $brand = Brand::factory()->create();

    $this->assertModelExists($brand);

    $new_title = fake()->words(2, true);
    $this->assertNotEquals($brand->title, $new_title);

    $brand->title = $new_title;

    $response = apiTest()->withToken($token)->put(
        route('api.v1.brand.update', $brand),
        $brand->only([
            'title',
        ])
    );

    $response->assertStatus(200);

    $brand->refresh();

    $this->assertEquals($brand->title, $new_title);
    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('update-brand-200.json', $response->content());
});


test('admin cannot update brand with invalid requirements', function () {
    $user = User::factory()->isAdmin()->create();
    $token = $user->createToken()->plainTextToken;

    $brand = Brand::factory()->create(['title'=> '']);

    $response = apiTest()->withToken($token)->put(
        route('api.v1.brand.update', $brand),
        $brand->only([
            'title',
        ])
    );

    $response->assertStatus(422);
    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('update-brand-422.json', $response->content());
});
