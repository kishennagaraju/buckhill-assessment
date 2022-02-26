<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserDeleteRequest;
use App\Traits\Models\User;

class UserController extends Controller
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
     * Delete non admin user.
     *
     * @param $uuid
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteUser($uuid)
    {
        $response = [
            'status' => false,
            'message' => 'User could not be Deleted'
        ];

        if ($this->getUserModel()->deleteUserByUuid($uuid)) {
            $response = [
                'status' => true,
                'message' => 'User Deleted'
            ];
        }

        return response()->json($response);
    }
}
