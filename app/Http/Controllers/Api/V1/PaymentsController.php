<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Requests\Payment\V1\CreatePaymentRequest;
use App\Http\Requests\Payment\V1\UpdatePaymentRequest;
use App\Http\Resources\V1\BaseApiResource;
use App\Models\Payment;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @group Payments endpoint
 *
 * This endpoint handles the CRUD methods for the Payments.
 */
class PaymentsController extends Controller
{
    public function __construct()
    {
        $this->middleware(AdminMiddleware::class)->except(['create', 'show']);
    }

    /**
     * List all payments
     *
     * @authenticated
     *
     * @responseFile status=200 storage/responses/payments-listing-200.json
     */
    public function index(Request $request)
    {
        $payments = QueryBuilder::for(Payment::query())
            ->allowedFilters(['details', 'uuid', 'type'])
            ->simplePaginate($request->get('limit', 15));

        return (new BaseApiResource())->resource($payments);
    }


    /**
     * Create payment
     *
     * @authenticated
     *
     * @responseFile status=200 storage/responses/create-payment-200.json
     * @responseFile status=422 scenario="when validation fails" storage/responses/create-payment-422.json
     */
    public function create(CreatePaymentRequest $request)
    {
        $data = $request->validated();

        $payment = Payment::create([
            'type' => $data['type'],
            'details' => $data['details']
        ]);

        return (new BaseApiResource($payment))->message("Payment created successfully");
    }


    /**
     * Update payment
     *
     * @authenticated
     *
     * @responseFile status=200 storage/responses/update-payment-200.json
     * @responseFile status=422 scenario="when validation fails" storage/responses/update-payment-422.json
     */
    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        $data = $request->validated();

        $payment->fill($data);

        if ($payment->isDirty()) {
            $payment->save();
        }

        return (new BaseApiResource($payment))->message("Payment updated successfully");
    }


    /**
     * Show payment information
     *
     * @authenticated
     *
     * @responseFile status=200 storage/responses/show-payment-200.json
     * @responseFile status=404 scenario="when payment doesn't belong to user" storage/responses/show-payment-404.json
     */
    public function show($uuid)
    {
        $payment = auth()->user()->payments()->uuid($uuid)->firstOrFail();
        return (new BaseApiResource($payment));
    }


    /**
     * Delete payment
     *
     *  NOTE: The associated order for this payment will be deleted as well
     *
     * @authenticated
     *
     * @responseFile status=200 storage/responses/delete-payment-200.json
     * @responseFile status=404 scenario="when uuid is invalid" storage/responses/delete-payment-404.json
     */
    public function delete(Payment $payment)
    {
        $payment->order()->delete();
        $payment->delete();

        return (new BaseApiResource())->message("Payment deleted");
    }
}
