<?php

namespace App\Http\Controllers;

use App\Traits\Models\Promotions;

class PromotionsController extends Controller
{
    use Promotions;

    /**
     * @OA\Get(
     *     path="/api/v1/main/promotions",
     *     summary="Retrieve All Promotions for Main Page",
     *     operationId="retrievePromotions",
     *     tags={"MainPage"},
     *     @OA\Parameter(
     *         description="Pagination Page",
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
     *         description="Sort Descending",
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
        return response()->json($this->getPromotionsModel()->listPromotions())->setStatusCode(200);
    }
}
