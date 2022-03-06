<?php

namespace App\Http\Controllers;

use App\Http\Middleware\BasicAuth;
use App\Http\Requests\CreateCategories;
use App\Http\Requests\UpdateCategories;
use App\Traits\Models\Categories;

class CategoriesController extends Controller
{
    use Categories;

    public function __construct()
    {
        $this->middleware(BasicAuth::class, ['only' => ['store', 'update', 'destroy']]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/categories",
     *     summary="Retrieve All Categories",
     *     operationId="retrieveAllCategories",
     *     tags={"Categories"},
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
        return response()->json($this->getCategoriesModel()->getAllCategories());
    }

    /**
     * @OA\Get(
     *     path="/api/v1/categories/{uuid}",
     *     summary="Retrieve Single Category by UUID",
     *     operationId="retrieveSingleCategory",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         description="Unique Identifier of Category",
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
        return response()->json([
            'status' => true,
            'message' => $this->getCategoriesModel()->getCategoryByUuid($uuid)
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/categories",
     *     summary="Create Category",
     *     operationId="createCategory",
     *     security={{"bearerAuth": {}}},
     *     tags={"Categories"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"title"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string"
     *                 ),
     *                 example={"title": "Test Category"}
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
    public function store(CreateCategories $request)
    {
        if ($categoryDetails = $this->getCategoriesModel()->createCategory($request->all())) {
            return response()->json([
                'status' => true,
                'message' => $categoryDetails
            ]);
        }

        response()->json([
            'status' => false,
            'message' => 'Could not create Category'
        ])->setStatusCode(500);

    }

    /**
     * @OA\Put(
     *     path="/api/v1/categories/{uuid}",
     *     summary="Update Category",
     *     operationId="updateCategory",
     *     security={{"bearerAuth": {}}},
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         description="Unique Identifier of Category",
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
     *                 example={"title": "Test Category"}
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
    public function update(UpdateCategories $request, string $uuid)
    {
        $this->getCategoriesModel()->updateCategoryByUuid($uuid, $request->all());

        return response()->json([
            'status' => true,
            'message' => $this->getCategoriesModel()->getCategoryByUuid($uuid)
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/categories/{uuid}",
     *     summary="Delete Category",
     *     operationId="deleteCategory",
     *     security={{"bearerAuth": {}}},
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         description="Unique Identifier of Category",
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
        $this->getCategoriesModel()->deleteCategoryByUuid($uuid);

        return response()->json([
            'status' => true,
            'message' => 'Category Deleted'
        ]);
    }
}
