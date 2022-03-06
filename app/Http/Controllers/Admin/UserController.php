<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAdminRequest;
use App\Traits\Models\JwtTokens;
use App\Traits\Models\User;

class UserController extends Controller
{
    use JwtTokens;
    use User;

    /**
     * @OA\Get(
     *     path="/api/v1/admin/user-listing",
     *     summary="Retrieve All Users",
     *     operationId="retrieveAllUsers",
     *     security={{"bearerAuth": {}}},
     *     tags={"Admin"},
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
        return response($this->getUserModel()->listNonAdminUsers());
    }

    /**
     * @OA\Post(
     *     path="/api/v1/admin/create",
     *     summary="Create Admin",
     *     operationId="createAdmin",
     *     tags={"Admin"},
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
    public function store(CreateAdminRequest $request): \Illuminate\Http\JsonResponse
    {
        return response()->json($this->getUserModel()->createUser($request->all()));
    }

    /**
     * @OA\Put(
     *     path="/api/v1/admin/user-edit/{uuid}",
     *     summary="Update Non Admin",
     *     operationId="updateNonAdmin",
     *     security={{"bearerAuth": {}}},
     *     tags={"Admin"},
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
    public function update(CreateAdminRequest $request, string $uuid)
    {
        return response()->json($this->getUserModel()->updateUser($uuid, $request->all()));
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/user-delete/{uuid}",
     *     summary="Delete User",
     *     operationId="deleteUser",
     *     security={{"bearerAuth": {}}},
     *     tags={"Admin"},
     *     @OA\Parameter(
     *         description="Unique Identifier of User",
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
    public function delete($uuid)
    {
        $response = [
            'status' => false,
            'message' => 'User could not be Deleted'
        ];

        if ($this->getUserModel()->deleteUserByUuid($uuid)) {
            $response = [
                'status' => true,
                'message' => 'User Deleted'
            ];
        }

        return response()->json($response);
    }
}
