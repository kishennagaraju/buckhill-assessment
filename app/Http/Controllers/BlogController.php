<?php

namespace App\Http\Controllers;

use App\Traits\Models\Post;

class BlogController extends Controller
{
    use Post;

    /**
     * @OA\Get(
     *     path="/api/v1/main/blog",
     *     summary="Retrieve All Blog Posts for Main Page",
     *     operationId="retrieveAllBlogPosts",
     *     tags={"MainPage"},
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
        return response()->json($this->getPostModel()->listPosts())->setStatusCode(200);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/main/blog/{uuid}",
     *     summary="Retrieve Single Blog Posts by UUID",
     *     operationId="retrieveSingleBlogPosts",
     *     tags={"MainPage"},
     *     @OA\Parameter(
     *         description="Unique Identifier of Blog Post",
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
            'message' => $this->getPostModel()->getPostByUuid($uuid)
        ]);
    }
}
