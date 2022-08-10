<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderStatus\V1\CreateOrderStatusRequest;
use App\Http\Resources\V1\BaseApiResource;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @group Order status endpoint
 *
 * This endpoint handles the CRUD methods for the Payments.
 */
class OrderStatusController extends Controller
{
    /**
     * List all order statuses
     *
     * @authenticated
     *
     * @responseFile status=200 storage/responses/order-statuses-listing-200.json
     */
    public function index(Request $request)
    {
        $order_statuses = QueryBuilder::for(OrderStatus::query())
            ->allowedFilters(['title'])
            ->simplePaginate($request->get('per_page', 15));

        return (new BaseApiResource())->resource($order_statuses);
    }


    /**
     * Create order-status
     *
     * @authenticated
     *
     * @responseFile status=200 storage/responses/create-order-status-200.json
     */
    public function create(CreateOrderStatusRequest $request)
    {
        $data = $request->validated();

        $order_status = OrderStatus::create([
            'title' => $data['title'],
        ]);

        return (new BaseApiResource($order_status))->message("Order status created successfully");
    }


    /**
     * Update order-status
     *
     * @authenticated
     *
     * @responseFile status=200 storage/responses/update-order-status-200.json
     */
    public function update(CreateOrderStatusRequest $request, OrderStatus $order_status)
    {
        $data = $request->validated();

        $order_status->fill($data);

        if ($order_status->isDirty()) {
            $order_status->save();
        }

        return (new BaseApiResource($order_status))->message("Order status updated successfully");
    }


    /**
     * Show order-status information
     *
     * @authenticated
     *
     * @responseFile status=200 storage/responses/show-order-status-200.json
     */
    public function show(OrderStatus $orderStatus)
    {
        return (new BaseApiResource($orderStatus));
    }


    /**
     * Delete order-status
     *
     *
     * @authenticated
     *
     * @responseFile status=200 storage/responses/delete-order-status-200.json
     */
    public function delete(OrderStatus $orderStatus)
    {
        $orderStatus->delete();

        return (new BaseApiResource())->message("Order status deleted");
    }
}
