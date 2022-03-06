<?php

namespace App\Http\Controllers;

use App\Http\Middleware\BasicAuth;
use App\Http\Requests\CreateBrands;
use App\Http\Requests\UpdateBrands;
use App\Traits\Models\Brands;

class BrandsController extends Controller
{
    use Brands;

    public function __construct()
    {
        $this->middleware(BasicAuth::class, ['only' => ['store', 'update', 'destroy']]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/brands",
     *     summary="Retrieve All Brands",
     *     operationId="retrieveAllBrands",
     *     tags={"Brands"},
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
        return response()->json($this->getBrandsModel()->getAllBrands(['products']));
    }

    /**
     * @OA\Get(
     *     path="/api/v1/brands/{uuid}",
     *     summary="Retrieve Single Brand by UUID",
     *     operationId="retrieveSingleBrand",
     *     tags={"Brands"},
     *     @OA\Parameter(
     *         description="Unique Identifier of Brand",
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
        $categoryDetails = $this->getBrandsModel()->getBrandByUuid($uuid, ['products']);

        return response()->json([
            'status' => true,
            'message' => $categoryDetails
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/brands",
     *     summary="Create Brand",
     *     operationId="createBrand",
     *     security={{"bearerAuth": {}}},
     *     tags={"Brands"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"title"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string"
     *                 ),
     *                 example={"title": "Test Brand"}
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
    public function store(CreateBrands $request)
    {
        if ($brandDetails = $this->getBrandsModel()->createBrand($request->all())) {
            return response()->json([
                'status' => true,
                'message' => $brandDetails
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Could not create Brand'
        ])->setStatusCode(500);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/brands/{uuid}",
     *     summary="Update Brand",
     *     operationId="updateBrand",
     *     security={{"bearerAuth": {}}},
     *     tags={"Brands"},
     *     @OA\Parameter(
     *         description="Unique Identifier of Brand",
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
     *                 example={"title": "Test Brand"}
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
    public function update(UpdateBrands $request, string $uuid)
    {
        $this->getBrandsModel()->updateBrandByUuid($uuid, $request->all());

        return response()->json([
            'status' => true,
            'message' => $this->getBrandsModel()->getBrandByUuid($uuid)
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/brands/{uuid}",
     *     summary="Delete Brand",
     *     operationId="deleteBrand",
     *     security={{"bearerAuth": {}}},
     *     tags={"Brands"},
     *     @OA\Parameter(
     *         description="Unique Identifier of Brand",
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
        $this->getBrandsModel()->deleteBrandByUuid($uuid);
        return response()->json([
            'status' => false,
            'message' => 'Brand Deleted'
        ]);
    }
}
