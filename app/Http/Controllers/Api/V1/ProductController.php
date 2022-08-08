<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\V1\CreateProductRequest;
use App\Http\Resources\User\V1\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @group Products endpoint
 *
 * This endpoint handles the CRUD methods for the Products.
 */
class ProductController extends Controller
{
    /**
     * List all products
     *
     * @authenticated
     *
     * @responseFile status=200 storage/responses/products-listing-200.json
     * @responseFile status=400 scenario="when filtered by disallowed field" storage/responses/products-listing-400.json
     */
    public function index(Request $request)
    {
        $products = QueryBuilder::for(Product::query())
            ->with(['category'])
            ->select(['title', 'uuid', 'category_uuid', 'description', 'metadata'])
            ->allowedFilters(['title', 'uuid', 'category_uuid'])
            ->simplePaginate($request->get('per_page', 15));


        return (new ProductResource())->resource($products);
    }


    /**
     * Create product
     *
     * @authenticated
     *
     * @responseFile status=200 storage/responses/create-product-200.json
     * @responseFile status=422 scenario="when validation fails" storage/responses/create-product-422.json
     */
    public function create(CreateProductRequest $request)
    {
        $data = $request->validated();
        $product = Product::create($data);

        return (new ProductResource($product))->message("Product created successfully");
    }
}
