<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\V1\CreateProductRequest;
use App\Http\Requests\Product\V1\UpdateProductRequest;
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
        $products = QueryBuilder::for(Product::query()->join('categories', 'categories.uuid', '=', 'category_uuid'))
            ->select(['products.title', 'products.uuid', 'category_uuid', 'description', 'metadata'])
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


    /**
     * Update product
     *
     * @authenticated
     *
     * @responseFile status=200 storage/responses/update-product-200.json
     * @responseFile status=422 scenario="when validation fails" storage/responses/update-product-422.json
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();

        $product->fill($data);

        if ($product->isDirty()) {
            $product->save();
        }

        return (new ProductResource($product))->message("Product updated successfully");
    }


    /**
     * Show product information
     *
     * @authenticated
     *
     * @responseFile status=200 storage/responses/show-product-200.json
     * @responseFile status=404 scenario="when uuid is invalid" storage/responses/show-product-404.json
     */
    public function show(Product $product)
    {
        return (new ProductResource($product));
    }



    /**
     * Delete product
     *
     * @authenticated
     *
     * @responseFile status=200 storage/responses/delete-product-200.json
     * @responseFile status=404 scenario="when uuid is invalid" storage/responses/delete-product-404.json
     */
    public function delete(Product $product)
    {
        $product->delete();
        return (new ProductResource())->message("Product deleted");
    }
}
