<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\User\CreateUserRequest;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Transformers\UserTransformer;
use App\Traits\Models\JwtTokens;


class UserController extends AdminUserController
{
    use JwtTokens;

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

        return response()->json(fractal($tokenDetails->user, new UserTransformer()));
    }

    /**
     * Create a new User.
     *
     * @param  \App\Http\Requests\User\CreateUserRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeUser(CreateUserRequest $request): \Illuminate\Http\JsonResponse
    {
        return response()->json(fractal($this->getUserModel()->createUser($request->all()), new UserTransformer()));
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
