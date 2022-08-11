<?php

use App\Models\Brand;
use App\Models\User;

test('admin can delete brand', function () {
    $user = User::factory()->isAdmin()->create();

    $token = $user->createToken()->plainTextToken;

    $brand = Brand::factory()->create();

    $this->assertModelExists($brand);
    $response = apiTest()->withToken($token)->delete(route('api.v1.brand.delete', $brand));

    $response->assertStatus(200);
    $this->assertNull(Brand::find($brand->id));
    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('delete-brand-200.json', $response->content());
});

test('admin cannot delete brand if uuid is invalid', function () {
    $user = User::factory()->isAdmin()->create();

    $token = $user->createToken()->plainTextToken;

    $response = apiTest()->withToken($token)->delete(route('api.v1.brand.delete', fake()->uuid()));

    $response->assertStatus(404);

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('delete-brand-404.json', $response->content());
});
