<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Traits\Models\JwtTokens;
use App\Traits\Models\User;
use App\Traits\Services\User as UserService;

class UserController extends Controller
{
    use JwtTokens;
    use User;
    use UserService;

    /**
     * @OA\Get(
     *     path="/api/v1/user",
     *     summary="Retrieve logged in User Details",
     *     operationId="retrieveLoginUserDetails",
     *     security={{"bearerAuth": {}}},
     *     tags={"User"},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
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
     *
     */
    public function show()
    {
        $userDetails = $this->getUserModel()->getUserDetailsByUuid(request()->user->uuid);

        return response()->json($userDetails);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/user",
     *     summary="Create User",
     *     operationId="createUser",
     *     tags={"User"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"first_name","last_name","email","password","password_confirmation","avatar","address","phone_number"},
     *                 @OA\Property(
     *                     property="first_name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="last_name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password_confirmation",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="avatar",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="address",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="phone_number",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="marketing",
     *                     type="boolean"
     *                 ),
     *                 example={
     *                      "first_name": "Test",
     *                      "last_name": "User",
     *                      "email": "test@buckhill.co.uk",
     *                      "password": "password123",
     *                      "password_confirmation": "password123",
     *                      "avatar": "82110194-fdc6-4872-9adb-4776e28deac3",
     *                      "address": "11930 Damion Light Suite 642 Brigitteside, AZ 62654",
     *                      "phone_number": "+1-936-301-5409 | (938) 653-8850 | 820.279.3605",
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
     *         response=422,
     *         description="Unprocessable Data"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function storeUser(CreateUserRequest $request): \Illuminate\Http\JsonResponse
    {
        return response()->json($this->getUserModel()->createUser($request->all()));
    }

    /**
     * @OA\Put(
     *     path="/api/v1/user",
     *     summary="Update User",
     *     operationId="updateUser",
     *     security={{"bearerAuth": {}}},
     *     tags={"User"},
     *     @OA\Parameter(
     *         description="Unique Identifier of User",
     *         in="path",
     *         name="uuid",
     *         required=true,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"first_name","last_name","email","password","password_confirmation","avatar","address","phone_number"},
     *                 @OA\Property(
     *                     property="first_name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="last_name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password_confirmation",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="avatar",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="address",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="phone_number",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="marketing",
     *                     type="boolean"
     *                 ),
     *                 example={
     *                      "first_name": "Test",
     *                      "last_name": "Admin",
     *                      "email": "test@buckhill.co.uk",
     *                      "password": "password123",
     *                      "password_confirmation": "password123",
     *                      "avatar": "82110194-fdc6-4872-9adb-4776e28deac3",
     *                      "address": "11930 Damion Light Suite 642 Brigitteside, AZ 62654",
     *                      "phone_number": "+1-936-301-5409 | (938) 653-8850 | 820.279.3605",
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
     *         response=422,
     *         description="Unprocessable Data"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function update(CreateUserRequest $request, string $uuid)
    {
        return response()->json($this->getUserModel()->updateUser($uuid, $request->all()));
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/user",
     *     summary="Delete User",
     *     operationId="deleteNonAdmin",
     *     security={{"bearerAuth": {}}},
     *     tags={"User"},
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
    public function delete(): \Illuminate\Http\JsonResponse
    {
        $response = [
            'status' => false,
            'message' => 'User could not be Deleted'
        ];

        if ($this->getUserModel()->deleteUserByUuid(request()->user->uuid)) {
            $response = [
                'status' => true,
                'message' => 'User Deleted'
            ];
        }

        return response()->json($response);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/user/orders",
     *     summary="Retrieve All User Orders",
     *     operationId="retrieveLoginUserOrders",
     *     security={{"bearerAuth": {}}},
     *     tags={"User"},
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
    public function getOrdersForUser()
    {
        $userDetails = $this->getUserModel()->getUserDetailsByUuid(request()->user->uuid);

        return response()->json(
            $this->getUserService()->getAllOrdersForUser(
                $userDetails->id,
                ['order_status', 'payment', 'order_products']
            )
        );
    }
}
