<?php

namespace App\Http\Controllers;

use App\Http\Middleware\BasicAuth;
use App\Http\Requests\CreateProducts;
use App\Http\Requests\UpdateProducts;
use App\Traits\Models\Products;

class ProductsController extends Controller
{
    use Products;

    public function __construct()
    {
        $this->middleware(BasicAuth::class, ['only' => ['store', 'update', 'destroy']]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/products",
     *     summary="Retrieve All Products",
     *     operationId="retrieveAllProducts",
     *     tags={"Products"},
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
        return response()->json($this->getProductsModel()->getAllProducts(['category', 'brand']));
    }

    /**
     * @OA\Get(
     *     path="/api/v1/products/{uuid}",
     *     summary="Retrieve Single Product by UUID",
     *     operationId="retrieveSingleProduct",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         description="Unique Identifier of Product",
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
    public function show(string $uuid)
    {
        return response()->json([
            'status' => true,
            'message' => $this->getProductsModel()->getProductByUuid($uuid, ['category', 'brand'])
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/products",
     *     summary="Create Product",
     *     operationId="createProduct",
     *     security={{"bearerAuth": {}}},
     *     tags={"Products"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"title"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string"
     *                 ),
     *                 example={"title": "Test Product"}
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
    public function store(CreateProducts $request)
    {
        if ($productDetails = $this->getProductsModel()->createProduct($request->all())) {
            response()->json([
                'status' => true,
                'message' => $productDetails
            ]);
        }

        response()->json([
            'status' => false,
            'message' => 'Could not create product'
        ])->setStatusCode(500);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/products/{uuid}",
     *     summary="Update Product",
     *     operationId="updateProduct",
     *     security={{"bearerAuth": {}}},
     *     tags={"Products"},
     *     @OA\Parameter(
     *         description="Unique Identifier of Product",
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
     *                 example={"title": "Test Product"}
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
    public function update(UpdateProducts $request, string $uuid)
    {
        $this->getProductsModel()->updateProductByUuid($uuid, $request->all());

        return response()->json([
            'status' => true,
            'message' => 'Product Updated'
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/products/{uuid}",
     *     summary="Delete Product",
     *     operationId="deleteProduct",
     *     security={{"bearerAuth": {}}},
     *     tags={"Products"},
     *     @OA\Parameter(
     *         description="Unique Identifier of Product",
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
    public function destroy($uuid)
    {
        $this->getProductsModel()->deleteProductByUuid($uuid);

        return response()->json([
            'status' => true,
            'message' => 'Product Deleted'
        ]);
    }
}
