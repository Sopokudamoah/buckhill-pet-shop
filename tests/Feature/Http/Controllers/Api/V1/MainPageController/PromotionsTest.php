<?php



test('can view list of blog posts', function () {
    $response = apiTest()->get(route('api.v1.main.promotions'));

    $response->assertStatus(200);

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('promotion-listing-200.json', $response->content());
});
