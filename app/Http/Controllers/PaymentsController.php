<?php

namespace App\Http\Controllers;

use App\Http\Middleware\BasicAuth;
use App\Http\Requests\CreatePayment;
use App\Http\Requests\UpdatePayment;
use App\Traits\Models\Payments;

class PaymentsController extends Controller
{
    use Payments;

    /**
     * @OA\Get(
     *     path="/api/v1/payments",
     *     summary="Retrieve All Payments",
     *     operationId="retrieveAllPayments",
     *     security={{"bearerAuth": {}}},
     *     tags={"Payments"},
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
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function index()
    {
        return response()->json($this->getPaymentsModel()->getAllPayments());
    }

    /**
     * @OA\Get(
     *     path="/api/v1/payments/{uuid}",
     *     summary="Retrieve Single Payment by UUID",
     *     operationId="retrieveSinglePayment",
     *     security={{"bearerAuth": {}}},
     *     tags={"Payments"},
     *     @OA\Parameter(
     *         description="Unique Identifier of Payment",
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
    public function show($uuid)
    {
        $categoryDetails = $this->getPaymentsModel()->getPaymentByUuid($uuid);

        return response()->json([
            'status' => true,
            'message' => $categoryDetails
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/payments",
     *     summary="Create Payment",
     *     operationId="createPayment",
     *     security={{"bearerAuth": {}}},
     *     tags={"Payments"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"type","details"},
     *                 @OA\Property(
     *                     property="type",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="details",
     *                     type="object"
     *                 ),
     *                 example={
     *                     "type": "credit_card|cash_on_delivery|bank_transfer",
     *                     "details": {
     *                         {"cvv": 249, "number": "4556720478426", "expire_date": "12/23", "holder_name": "Dr. Sheridan Nienow"},
     *                         {"address": "48705 Christa Forest Jasttown, WI 95221-8736", "last_name": "Schultz", "first_name": "Mack"},
     *                         {"iban": "PL65327539024464368422961701", "name": "Ken Schimmel", "swift": "HYUPWASK"},
     *                         {"Any one of the above formats as per type"}
     *                     }
     *                 }
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
    public function store(CreatePayment $request)
    {
        if ($paymentDetails = $this->getPaymentsModel()->createPayment($request->all())) {
            return response()->json([
                'status' => true,
                'message' => $paymentDetails
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Could not create Payment'
        ])->setStatusCode(500);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/payments/{uuid}",
     *     summary="Update Payment",
     *     operationId="updatePayment",
     *     security={{"bearerAuth": {}}},
     *     tags={"Payments"},
     *     @OA\Parameter(
     *         description="Unique Identifier of Payment",
     *         in="path",
     *         name="uuid",
     *         required=true,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"type","details"},
     *                 @OA\Property(
     *                     property="type",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="details",
     *                     type="object"
     *                 ),
     *                 example={
     *                     "type": "credit_card|cash_on_delivery|bank_transfer",
     *                     "details": {
     *                         {"cvv": 249, "number": "4556720478426", "expire_date": "12/23", "holder_name": "Dr. Sheridan Nienow"},
     *                         {"address": "48705 Christa Forest Jasttown, WI 95221-8736", "last_name": "Schultz", "first_name": "Mack"},
     *                         {"iban": "PL65327539024464368422961701", "name": "Ken Schimmel", "swift": "HYUPWASK"},
     *                         {"Any one of the above formats as per type"}
     *                     }
     *                 }
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
    public function update(UpdatePayment $request, string $uuid)
    {
        $this->getPaymentsModel()->updatePaymentByUuid($uuid, $request->all());

        return response()->json([
            'status' => true,
            'message' => $this->getPaymentsModel()->getPaymentByUuid($uuid)
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/payments/{uuid}",
     *     summary="Delete Payment",
     *     operationId="deletePayment",
     *     security={{"bearerAuth": {}}},
     *     tags={"Payments"},
     *     @OA\Parameter(
     *         description="Unique Identifier of Payment",
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
        $this->getPaymentsModel()->deletePaymentByUuid($uuid);
        return response()->json([
            'status' => false,
            'message' => 'Payment Deleted'
        ]);
    }
}
