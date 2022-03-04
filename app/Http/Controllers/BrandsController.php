<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBrands;
use App\Http\Requests\UpdateBrands;
use App\Traits\Models\Brands;

class BrandsController extends Controller
{
    use Brands;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json($this->getBrandsModel()->getAllBrands());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateBrands  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateBrands $request)
    {
        $response = [
            'status' => false,
            'message' => "Cannot create Brand"
        ];

        if ($brandDetails = $this->getBrandsModel()->createBrand($request->all())) {
            $response = [
                'status' => true,
                'message' => $brandDetails
            ];
        } else {
            return response()->json($response)->setStatusCode(500);
        }

        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($uuid)
    {
        $categoryDetails = $this->getBrandsModel()->getBrandByUuid($uuid);
        return response()->json([
            'status' => true,
            'message' => $categoryDetails
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBrands      $request
     * @param  string                               $uuid
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateBrands $request, string $uuid)
    {
        if ($this->getBrandsModel()->updateBrandByUuid($uuid, $request->all())) {
            return response()->json([
                'status' => true,
                'message' => $this->getBrandsModel()->getBrandByUuid($uuid)
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\JsonResponse|bool
     */
    public function destroy(string $uuid)
    {
        if ($this->getBrandsModel()->deleteBrandByUuid($uuid)) {
            return response()->json([
                'status' => false,
                'message' => 'Brand Deleted'
            ]);
        }

        return false;
    }
}
