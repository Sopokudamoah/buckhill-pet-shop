<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\OrderCreated;
use App\Http\Controllers\Controller;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Requests\Order\V1\CreateOrderRequest;
use App\Http\Requests\Order\V1\UpdateOrderRequest;
use App\Http\Resources\Order\V1\OrderResource;
use App\Http\Resources\V1\BaseApiResource;
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
    public function __construct()
    {
        $this->middleware(AdminMiddleware::class)->except(['create', 'show']);
    }

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
        $orders = QueryBuilder::for(Order::query())
            ->allowedFilters(['delivery_fee', 'address', 'products', 'uuid', 'payment_id', 'order_status_id'])
            ->simplePaginate($request->get('limit', 15));


        return (new OrderResource())->resource($orders);
    }


    /**
     * Create order
     *
     * @authenticated
     *
     * @responseFile status=200 storage/responses/create-order-200.json
     * @responseFile status=422 scenario="when validation fails" storage/responses/create-order-422.json
     */
    public function create(CreateOrderRequest $request)
    {
        $data = $request->validated();

        $order = Order::create($data);

        //Fire event when order is created
        OrderCreated::dispatch($order);
        return (new OrderResource($order))->message("Order created successfully");
    }


    /**
     * Update order
     *
     * @authenticated
     *
     * @responseFile status=200 storage/responses/update-order-200.json
     * @responseFile status=422 scenario="when validation fails" storage/responses/update-order-422.json
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $data = $request->validated();

        $order->fill($data);

        if ($order->isDirty()) {
            $order->save();
        }

        return (new OrderResource($order))->message("Order updated successfully");
    }


    /**
     * Show order information
     *
     * @authenticated
     *
     * @responseFile status=200 storage/responses/show-order-200.json
     * @responseFile status=404 scenario="when uuid is invalid" storage/responses/show-order-404.json
     */
    public function show($uuid)
    {
        $order = auth()->user()->orders()->uuid($uuid)->firstOrFail();
        return (new BaseApiResource($order));
    }


    /**
     * Delete order
     *
     * @authenticated
     *
     * @responseFile status=200 storage/responses/delete-order-200.json
     * @responseFile status=404 scenario="when uuid is invalid" storage/responses/delete-order-404.json
     */
    public function delete(Order $order)
    {
        $order->delete();
        return (new BaseApiResource())->message("Order deleted");
    }
}
