<?php

namespace App\Http\Controllers;

use App\Http\Middleware\BasicAuth;
use App\Http\Requests\CreateOrder;
use App\Traits\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class OrdersController extends Controller
{
    use Order;

    public function __construct()
    {
        $this->middleware(BasicAuth::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json($this->getOrderModel()
            ->getAllOrders(['user', 'order_status', 'payment', 'order_products']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateOrder  $request
     * @return \Illuminate\Http\JsonResponse
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
     * Display the specified resource.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\JsonResponse
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
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOrders      $request
     * @param  string                               $uuid
     *
     * @return \Illuminate\Http\JsonResponse
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
     * Remove the specified resource from storage.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\JsonResponse|bool
     */
    public function destroy(string $uuid)
    {
        $this->getOrderModel()->deleteOrderByUuid($uuid);
        return response()->json([
            'status' => false,
            'message' => 'Order Deleted'
        ]);
    }

    public function download($uuid)
    {
        $orderDetails = $this->getOrderModel()->getOrderByUuid($uuid, ['order_products', 'order_status']);

        view()->share('order', $orderDetails);
        $pdf_doc = Pdf::loadView('invoices.invoice', ['order' => $orderDetails]);
        $fileName = storage_path('invoices/') . $orderDetails->uuid . '.pdf';
        $pdf = $pdf_doc->download($fileName);

        $headers = [
            'Content-Disposition' => 'attachment; filename='. $orderDetails->uuid . '.pdf' . ';'
        ];

        return response()->download(
            storage_path('app/public/') . $fileDetails->path,
            $fileDetails->file_name,
            $headers,
            'attachment'
        );
    }
}
