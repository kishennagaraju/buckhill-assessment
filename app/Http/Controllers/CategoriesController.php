<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategories;
use App\Http\Requests\UpdateCategories;
use App\Traits\Models\Categories;

class CategoriesController extends Controller
{
    use Categories;

    public function __construct()
    {
        $this->middleware('basic.auth', ['only' => ['store', 'update', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json($this->getCategoriesModel()->getAllCategories());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateCategories  $request
     * @return \Illuminate\Http\JsonResponse
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
     * Display the specified resource.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($uuid)
    {
        return response()->json([
            'status' => true,
            'message' => $this->getCategoriesModel()->getCategoryByUuid($uuid)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCategories  $request
     * @param  string                               $uuid
     *
     * @return \Illuminate\Http\JsonResponse
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
     * Remove the specified resource from storage.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\JsonResponse
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
