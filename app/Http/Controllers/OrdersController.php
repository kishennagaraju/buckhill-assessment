<?php

namespace App\Http\Controllers;

use App\Http\Middleware\BasicAuth;
use App\Http\Requests\CreateOrder;
use App\Traits\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

class OrdersController extends Controller
{
    use Order;

    public function __construct()
    {
        $this->middleware(BasicAuth::class);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/order",
     *     summary="Retrieve All Orders",
     *     operationId="retrieveAllOrders",
     *     security={{"bearerAuth": {}}},
     *     tags={"Orders"},
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
     */
    public function index()
    {
        return response()->json($this->getOrderModel()
            ->getAllOrders(['user', 'order_status', 'payment', 'order_products']));
    }

    /**
     * @OA\Post(
     *     path="/api/v1/order",
     *     summary="Create Order",
     *     operationId="createOrder",
     *     security={{"bearerAuth": {}}},
     *     tags={"Orders"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"title"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string"
     *                 ),
     *                 example={"title": "Test Order"}
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
    public function store(CreateOrder $request)
    {
        if ($orderDetails = $this->getOrderModel()->createOrder($request->all())) {
            return response()->json([
                'status' => true,
                'message' => $orderDetails
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Could not create Order'
        ])->setStatusCode(500);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/order/{uuid}",
     *     summary="Retrieve Single Order by UUID",
     *     operationId="retrieveSingleOrder",
     *     security={{"bearerAuth": {}}},
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         description="Unique Identifier of Order",
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
        $categoryDetails = $this->getOrderModel()->getOrderByUuid($uuid, ['user', 'order_status', 'payment', 'order_products']);

        return response()->json([
            'status' => true,
            'message' => $categoryDetails
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/order/{uuid}",
     *     summary="Update Order",
     *     operationId="updateOrder",
     *     security={{"bearerAuth": {}}},
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         description="Unique Identifier of Order",
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
     *                 example={"title": "Test Order"}
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
    public function update(UpdateOrders $request, string $uuid)
    {
        $this->getOrderModel()->updateOrderByUuid($uuid, $request->all());

        return response()->json([
            'status' => true,
            'message' => $this->getOrderModel()->getOrderByUuid($uuid)
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/order/{uuid}",
     *     summary="Delete Order",
     *     operationId="deleteOrder",
     *     security={{"bearerAuth": {}}},
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         description="Unique Identifier of Order",
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
        $this->getOrderModel()->deleteOrderByUuid($uuid);
        return response()->json([
            'status' => false,
            'message' => 'Order Deleted'
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/order/{uuid}/download",
     *     summary="Download Order Pdf by UUID",
     *     operationId="downloadOrderPdf",
     *     security={{"bearerAuth": {}}},
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         description="Unique Identifier of Order",
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
    public function download($uuid)
    {
        $orderDetails = $this->getOrderModel()->getOrderByUuid($uuid, ['order_products', 'order_status']);

        view()->share('order', $orderDetails);
        $pdf_doc = Pdf::loadView('invoices.invoice', ['order' => $orderDetails]);
        $fileName = storage_path('invoices/') . $orderDetails->uuid . '.pdf';
        $pdf = $pdf_doc->download($fileName);

        $headers = [
            'Content-Disposition' => 'inline; filename='. $orderDetails->uuid . '.pdf' . ';'
        ];

        return Response::make($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $orderDetails->uuid . '.pdf' . '"'
        ]);
    }
}
