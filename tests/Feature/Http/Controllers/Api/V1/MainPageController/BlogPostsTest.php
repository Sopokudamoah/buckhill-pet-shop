<?php


use App\Models\Post;

test('can view list of blog posts', function () {
    $response = apiTest()->get(route('api.v1.main.blog'));

    $response->assertStatus(200);

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('blog-listing-200.json', $response->content());
});


test('can view blog post', function () {
    $post = Post::factory()->create();
    $response = apiTest()->get(route('api.v1.main.show-blog', $post));

    $response->assertStatus(200);

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('blog-show-200.json', $response->content());
});
