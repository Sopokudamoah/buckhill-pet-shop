<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Brand\V1\CreateBrandRequest;
use App\Http\Requests\Brand\V1\UpdateBrandRequest;
use App\Http\Resources\V1\BrandResource;
use App\Models\Brand;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @group Brands endpoint
 *
 * This endpoint handles the CRUD methods for the Brands.
 */
class BrandController extends Controller
{
    /**
     * List all brands
     *
     * @authenticated
     *
     * @responseFile status=200 storage/responses/brands-listing-200.json
     * @responseFile status=400 scenario="when filtered by disallowed field" storage/responses/brands-listing-400.json
     */
    public function index(Request $request)
    {
        $products = QueryBuilder::for(Brand::query())
            ->select(['title', 'uuid', 'slug'])
            ->allowedFilters(['title', 'uuid', 'slug'])
            ->simplePaginate($request->get('per_page', 15));


        return (new BrandResource())->resource($products);
    }


    /**
     * Create brand
     *
     * @authenticated
     *
     * @responseFile status=200 storage/responses/create-brand-200.json
     * @responseFile status=422 scenario="when validation fails" storage/responses/create-brand-422.json
     */
    public function create(CreateBrandRequest $request)
    {
        $data = $request->validated();
        $brand = Brand::create($data);

        return (new BrandResource($brand))->message("Brand created successfully");
    }


    /**
     * Update brand
     *
     * @authenticated
     *
     * @responseFile status=200 storage/responses/update-brand-200.json
     * @responseFile status=422 scenario="when validation fails" storage/responses/update-brand-422.json
     */
    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        $data = $request->validated();

        $brand->fill($data);

        if ($brand->isDirty()) {
            $brand->save();
        }

        return (new BrandResource($brand))->message("Brand updated successfully");
    }


    /**
     * Show brand information
     *
     * @authenticated
     *
     * @responseFile status=200 storage/responses/show-brand-200.json
     * @responseFile status=404 scenario="when uuid is invalid" storage/responses/show-brand-404.json
     */
    public function show(Brand $brand)
    {
        return (new BrandResource($brand));
    }



    /**
     * Delete brand
     *
     * @authenticated
     *
     * @responseFile status=200 storage/responses/delete-brand-200.json
     * @responseFile status=404 scenario="when uuid is invalid" storage/responses/delete-brand-404.json
     */
    public function delete(Brand $brand)
    {
        $brand->delete();
        return (new BrandResource())->message("Brand deleted");
    }
}
