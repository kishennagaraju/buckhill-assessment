<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderStatus;
use App\Http\Requests\UpdateOrderStatus;
use App\Traits\Models\OrderStatuses;

class OrderStatusesController extends Controller
{
    use OrderStatuses;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json($this->getOrderStatusesModel()->getAllOrderStatuses());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateOrderStatus  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateOrderStatus $request)
    {
        if ($brandDetails = $this->getOrderStatusesModel()->createOrderStatus($request->all())) {
            return response()->json([
                'status' => true,
                'message' => $brandDetails
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Could not create OrderStatus'
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
        $categoryDetails = $this->getOrderStatusesModel()->getOrderStatusByUuid($uuid, ['products']);

        return response()->json([
            'status' => true,
            'message' => $categoryDetails
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateOrderStatus  $request
     * @param  string             $uuid
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateOrderStatus $request, string $uuid)
    {
        $this->getOrderStatusesModel()->updateOrderStatusByUuid($uuid, $request->all());

        return response()->json([
            'status' => true,
            'message' => $this->getOrderStatusesModel()->getOrderStatusByUuid($uuid)
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
        $this->getOrderStatusesModel()->deleteOrderStatusByUuid($uuid);
        return response()->json([
            'status' => false,
            'message' => 'OrderStatus Deleted'
        ]);
    }
}
