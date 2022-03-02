<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Requests\Auth\LoginRequest;
use App\Traits\Services\User;

class AuthController extends AdminAuthController
{
    use User;

    /**
     * Display a listing of the resource.
     *
     * @param \App\Http\Requests\Auth\LoginRequest  $request
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

        if ($tokenDetails = $this->getUserService()->login($request)) {
            $response = [
                'status' => true,
                'data' => $tokenDetails
            ];

            return response()->json($response)->setStatusCode(200);
        }

        return response()->json($response)->setStatusCode(401);
    }
}
