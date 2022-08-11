<?php

namespace App\Http\Controllers;

use App\Http\Resources\V1\BaseApiResource;
use App\Models\Post;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @group Main page endpoint
 *
 * This endpoint handles the listing of blogs posts and promotions for the main page.
 */
class MainPageController extends Controller
{
    /**
     * List blog posts
     *
     * @unauthenticated
     *
     * @responseFile status=200 storage/responses/blog-listing-200.json
     */
    public function blog(Request $request)
    {
        $posts = QueryBuilder::for(Post::query())->simplePaginate($request->get('limit', 15));
        return (new BaseApiResource())->resource($posts);
    }


    /**
     * View blog post
     *
     * @unauthenticated
     *
     * @responseFile status=200 storage/responses/blog-show-200.json
     */
    public function showBlog(Post $post)
    {
        return (new BaseApiResource($post));
    }

    /**
     * List promotions
     *
     * @unauthenticated
     *
     * @responseFile status=200 storage/responses/promotion-listing-200.json
     */
    public function promotions(Request $request)
    {
        $promotions = QueryBuilder::for(Promotion::query())->simplePaginate($request->get('limit', 15));
        return (new BaseApiResource())->resource($promotions);
    }
}
