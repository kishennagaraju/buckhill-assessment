<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\UserController as BaseUserController;
use App\Http\Requests\CreateAdminRequest;
use App\Traits\Models\User;

class UserController extends BaseUserController
{
    use User;

    /**
     * List all non admin users.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function index()
    {
        return response($this->getUserModel()->listNonAdminUsers());
    }

    /**
     * Create a new Admin User.
     *
     * @param  \App\Http\Requests\CreateAdminRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateAdminRequest $request): \Illuminate\Http\JsonResponse
    {
        return response()->json($this->getUserModel()->createUser($request->all()));
    }
}
