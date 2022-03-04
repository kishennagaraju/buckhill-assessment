<?php

namespace App\Http\Controllers;

use App\Traits\Models\Promotions;

class PromotionsController extends Controller
{
    use Promotions;

    public function index()
    {
        return response()->json($this->getPromotionsModel()->listPromotions())->setStatusCode(200);
    }
}
