<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Requests\Category\V1\CreateCategoryRequest;
use App\Http\Requests\Category\V1\UpdateCategoryRequest;
use App\Http\Resources\V1\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @group Categories endpoint
 *
 * This endpoint handles the CRUD methods for the Categories.
 */
class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(AdminMiddleware::class)->except(['index', 'show']);
    }

    /**
     * List all categories
     *
     * @authenticated
     *
     * @responseFile status=200 storage/responses/categories-listing-200.json
     * @responseFile status=400 scenario="when filtered by disallowed field" storage/responses/categories-listing-400.json
     */
    public function index(Request $request)
    {
        $products = QueryBuilder::for(Category::query())
            ->select(['title', 'uuid', 'slug'])
            ->allowedFilters(['title', 'uuid', 'slug'])
            ->simplePaginate($request->get('per_page', 15));


        return (new CategoryResource())->resource($products);
    }


    /**
     * Create category
     *
     * @authenticated
     *
     * @responseFile status=200 storage/responses/create-category-200.json
     * @responseFile status=422 scenario="when validation fails" storage/responses/create-category-422.json
     */
    public function create(CreateCategoryRequest $request)
    {
        $data = $request->validated();
        $category = Category::create($data);

        return (new CategoryResource($category))->message("Category created successfully");
    }


    /**
     * Update category
     *
     * @authenticated
     *
     * @responseFile status=200 storage/responses/update-category-200.json
     * @responseFile status=422 scenario="when validation fails" storage/responses/update-category-422.json
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $data = $request->validated();

        $category->fill($data);

        if ($category->isDirty()) {
            $category->save();
        }

        return (new CategoryResource($category))->message("Category updated successfully");
    }


    /**
     * Show category information
     *
     * @authenticated
     *
     * @responseFile status=200 storage/responses/show-category-200.json
     * @responseFile status=404 scenario="when uuid is invalid" storage/responses/show-category-404.json
     */
    public function show(Category $category)
    {
        return (new CategoryResource($category));
    }


    /**
     * Delete category
     *
     * @authenticated
     *
     * @responseFile status=200 storage/responses/delete-category-200.json
     * @responseFile status=404 scenario="when uuid is invalid" storage/responses/delete-category-404.json
     */
    public function delete(Category $category)
    {
        $category->delete();
        return (new CategoryResource())->message("Category deleted");
    }
}
