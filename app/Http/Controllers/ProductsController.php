<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProducts;
use App\Http\Requests\UpdateProducts;
use App\Traits\Models\Products;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    use Products;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json($this->getProductsModel()->getAllProducts(['category', 'brand']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateProducts  $request
     * @return \Illuminate\Http\JsonResponse
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
     * Display the specified resource.
     *
     * @param  string  $uuid
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $uuid)
    {
        return response()->json([
            'status' => true,
            'message' => $this->getProductsModel()->getProductByUuid($uuid, ['category', 'brand'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateProducts  $request
     * @param  string          $uuid
     *
     * @return \Illuminate\Http\JsonResponse
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
     * Remove the specified resource from storage.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\JsonResponse
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
