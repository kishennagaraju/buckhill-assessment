<?php

namespace App\Http\Controllers;

use App\Http\Middleware\BasicAuth;
use App\Http\Requests\CreateOrderStatus;
use App\Http\Requests\UpdateOrderStatus;
use App\Traits\Models\OrderStatuses;

class OrderStatusesController extends Controller
{
    use OrderStatuses;

    public function __construct()
    {
        $this->middleware(BasicAuth::class, ['only' => ['store', 'update', 'destroy']]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/order-status",
     *     summary="Retrieve All Order Statuses",
     *     operationId="retrieveAllOrderStatuses",
     *     tags={"Order Status"},
     *     @OA\Parameter(
     *         description="Page Number",
     *         in="query",
     *         name="page",
     *         required=false,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Parameter(
     *         description="Pagination Limit Per Page",
     *         in="query",
     *         name="limit",
     *         required=false,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Parameter(
     *         description="Pagination Sort",
     *         in="query",
     *         name="sortBy",
     *         required=false,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Parameter(
     *         description="Sort in Descending",
     *         in="query",
     *         name="desc",
     *         required=false,
     *         @OA\Schema(type="boolean"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     *
     */
    public function index()
    {
        return response()->json($this->getOrderStatusesModel()->getAllOrderStatuses());
    }

    /**
     * @OA\Get(
     *     path="/api/v1/order-status/{uuid}",
     *     summary="Retrieve Single Order Status by UUID",
     *     operationId="retrieveSingleOrderStatus",
     *     tags={"Order Status"},
     *     @OA\Parameter(
     *         description="Unique Identifier of Order Status",
     *         in="path",
     *         name="uuid",
     *         required=true,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     *
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
     * @OA\Post(
     *     path="/api/v1/order-status",
     *     summary="Create Order Status",
     *     operationId="createOrderStatus",
     *     security={{"bearerAuth": {}}},
     *     tags={"Order Status"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"title"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string"
     *                 ),
     *                 example={"title": "Shipped"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
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
     * @OA\Put(
     *     path="/api/v1/order-status/{uuid}",
     *     summary="Update Order Status",
     *     operationId="updateOrderStatus",
     *     security={{"bearerAuth": {}}},
     *     tags={"Order Status"},
     *     @OA\Parameter(
     *         description="Unique Identifier of Order Status",
     *         in="path",
     *         name="uuid",
     *         required=true,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"title"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string"
     *                 ),
     *                 example={"title": "Active"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     *
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
     * @OA\Delete(
     *     path="/api/v1/order-status/{uuid}",
     *     summary="Delete OrderStatus",
     *     operationId="deleteOrderStatus",
     *     security={{"bearerAuth": {}}},
     *     tags={"Order Status"},
     *     @OA\Parameter(
     *         description="Unique Identifier of Order Status",
     *         in="path",
     *         name="uuid",
     *         required=true,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     *
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
