<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Traits\Models\JwtTokens;

use App\Traits\Models\User;

use function request;
use function response;

class UserController extends Controller
{
    use JwtTokens;
    use User;

    /**
     * List all non admin users.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show()
    {
        $tokenDetails = $this->getJwtTokensModel()->getJwtTokenDetails(
            request()->header('Authorization'),
            ['user']
        );

        return response()->json($tokenDetails->user);
    }

    /**
     * Create a new User.
     *
     * @param  \App\Http\Requests\CreateUserRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeUser(CreateUserRequest $request): \Illuminate\Http\JsonResponse
    {
        return response()->json($this->getUserModel()->createUser($request->all()));
    }

    /**
     * Delete a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(): \Illuminate\Http\JsonResponse
    {
        $jwtToken = request()->hasHeader('Authorization')
            ? request()->header('Authorization')
            : request()->get('token');

        $tokenDetails = $this->getJwtTokensModel()->getJwtTokenDetails($jwtToken, ['user']);

        $response = [
            'status' => false,
            'message' => 'User could not be Deleted'
        ];

        if ($this->getUserModel()->deleteUserByUuid($tokenDetails->user->uuid)) {
            $response = [
                'status' => true,
                'message' => 'User Deleted'
            ];
        }

        return response()->json($response);
    }
}
