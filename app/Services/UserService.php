<?php

namespace App\Services;

use Illuminate\Foundation\Http\FormRequest;

class UserService extends AuthService
{
    /**
     * @param  \Illuminate\Foundation\Http\FormRequest  $request
     *
     * @return array|bool
     * @throws \Throwable
     */
    public function login(FormRequest $request)
    {
        $userDetails = $this->getUserModel()->getUserDetailsByEmail($request->get('email'));
        if ($this->getHashService()->verifyHashForString($request->get('password'), $userDetails->password)) {
            $this->getUserModel()->updateLastLoginOfUser($userDetails->id);

            return $this->getJwtService()->generateJwtToken([
                'uuid' => $userDetails->uuid,
                'is_admin' => $userDetails->is_admin,
            ]);
        }

        return false;
    }
}
