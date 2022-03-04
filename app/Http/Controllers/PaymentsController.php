<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePayment;
use App\Http\Requests\UpdatePayment;
use App\Traits\Models\Payments;

class PaymentsController extends Controller
{
    use Payments;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json($this->getPaymentsModel()->getAllPayments());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreatePayment  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreatePayment $request)
    {
        if ($brandDetails = $this->getPaymentsModel()->createPayment($request->all())) {
            return response()->json([
                'status' => true,
                'message' => $brandDetails
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Could not create Payment'
        ])->setStatusCode(500);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($uuid)
    {
        $categoryDetails = $this->getPaymentsModel()->getPaymentByUuid($uuid);

        return response()->json([
            'status' => true,
            'message' => $categoryDetails
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdatePayment  $request
     * @param  string         $uuid
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdatePayment $request, string $uuid)
    {
        $this->getPaymentsModel()->updatePaymentByUuid($uuid, $request->all());

        return response()->json([
            'status' => true,
            'message' => $this->getPaymentsModel()->getPaymentByUuid($uuid)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $uuid)
    {
        $this->getPaymentsModel()->deletePaymentByUuid($uuid);
        return response()->json([
            'status' => false,
            'message' => 'Payment Deleted'
        ]);
    }
}
