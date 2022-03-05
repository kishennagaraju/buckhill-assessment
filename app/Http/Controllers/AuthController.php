<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Traits\Services\Auth;

use function response;

class AuthController extends Controller
{
    use Auth;

    /**
     * Display a listing of the resource.
     *
     * @param \App\Http\Requests\LoginRequest  $request
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
     * @return \Illuminate\Http\JsonResponse|object
     */
    public function logout()
    {
        $response = [
            'status' => false,
            'data' => 'Logout Failed'
        ];

        if ($this->getAuthService()->logout()) {
            $response = [
                'status' => true,
                'data' => 'Logout Success'
            ];

            return response()->json($response)->setStatusCode(200);
        }

        return response()->json($response)->setStatusCode(401);
    }
}
