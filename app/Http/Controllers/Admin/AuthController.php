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
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function login(LoginRequest $request): \Illuminate\Http\Response
    {
        $response = [
            'status' => false,
            'data' => null
        ];

        if ($tokenDetails = $this->getAuthService()->login($request)) {
            $response['status'] = true;
            $response['data'] = $tokenDetails;
        }

        return response($response);
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
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request): \Illuminate\Http\Response
    {
        return response($this->getAuthService()->createUser($request));
    }
}
