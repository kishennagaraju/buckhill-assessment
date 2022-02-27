<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateRequest;
use App\Http\Requests\Admin\LoginRequest;
use App\Traits\Services\Auth;

class AuthController extends Controller
{
    use Auth;

    /**
     * Display a listing of the resource.
     *
     * @param LoginRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function login(LoginRequest $request): \Illuminate\Http\JsonResponse
    {
        $response = [
            'status' => false,
            'data' => 'Login Failed'
        ];

        if ($tokenDetails = $this->getAuthService()->login($request)) {
            $response = [
                'status' => true,
                'data' => $tokenDetails
            ];

            return response()->json($response)->setStatusCode(200);
        }

        return response()->json($response)->setStatusCode(401);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        return response("Logout");
    }

    /**
     * Create a new Admin User.
     *
     * @param  \App\Http\Requests\Admin\CreateRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateRequest $request): \Illuminate\Http\JsonResponse
    {
        return response()->json($this->getAuthService()->createUser($request));
    }
}
