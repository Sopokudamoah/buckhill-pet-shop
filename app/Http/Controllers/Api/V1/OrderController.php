<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Order\V1\OrderResource;
use App\Http\Resources\V1\CategoryResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @group Orders endpoint
 *
 * This endpoint handles the CRUD methods for the Orders.
 */
class OrderController extends Controller
{
    /**
     * List all orders
     *
     * @authenticated
     *
     * @responseFile status=200 storage/responses/orders-listing-200.json
     * @responseFile status=400 scenario="when filtered by disallowed field" storage/responses/orders-listing-400.json
     */
    public function index(Request $request)
    {
        $products = QueryBuilder::for(auth()->user()->orders())
            ->allowedFilters(['delivery_fee', 'address', 'products', 'uuid', 'payment_id', 'order_status_id'])
            ->simplePaginate($request->get('per_page', 15));


        return (new OrderResource())->resource($products);
    }


    /**
     * Create order
     *
     * @authenticated
     *
     * @responseFile status=200 storage/responses/create-order-200.json
     * @responseFile status=422 scenario="when validation fails" storage/responses/create-order-422.json
     */
    public function create(CreateCategoryRequest $request)
    {
        $data = $request->validated();
        $category = Order::create($data);

        return (new CategoryResource($category))->message("Order created successfully");
    }


    /**
     * Update order
     *
     * @authenticated
     *
     * @responseFile status=200 storage/responses/update-order-200.json
     * @responseFile status=422 scenario="when validation fails" storage/responses/update-order-422.json
     */
    public function update(UpdateCategoryRequest $request, Order $category)
    {
        $data = $request->validated();

        $category->fill($data);

        if ($category->isDirty()) {
            $category->save();
        }

        return (new CategoryResource($category))->message("Order updated successfully");
    }


    /**
     * Show order information
     *
     * @authenticated
     *
     * @responseFile status=200 storage/responses/show-order-200.json
     * @responseFile status=404 scenario="when uuid is invalid" storage/responses/show-order-404.json
     */
    public function show(Order $category)
    {
        return (new CategoryResource($category));
    }


    /**
     * Delete order
     *
     * @authenticated
     *
     * @responseFile status=200 storage/responses/delete-order-200.json
     * @responseFile status=404 scenario="when uuid is invalid" storage/responses/delete-order-404.json
     */
    public function delete(Order $category)
    {
        $category->delete();
        return (new CategoryResource())->message("Order deleted");
    }
}
