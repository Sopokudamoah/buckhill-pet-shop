<?php

use App\Models\Brand;
use App\Models\User;

test('user can access brand list', function () {
    //Seed DB with some products
    Brand::factory()->count(30)->create();

    $admin = User::factory()->create();
    $token = $admin->createToken()->plainTextToken;

    $response = apiTest()->withToken($token)->get(route('api.v1.brand.index', ['page' => 1]));
    $response->assertStatus(200);

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('brands-listing-200.json', $response->content());
});

test('user cannot filter by disallowed fields', function () {
    $admin = User::factory()->create();
    $token = $admin->createToken()->plainTextToken;

    $response = apiTest()->withToken($token)->get(
        route('api.v1.brand.index', ['page' => 1, 'filter' => ['deleted_at' => now()->format('Y-m-d')]])
    );
    $response->assertStatus(400);

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('brands-listing-400.json', $response->content());
});
