<?php

    namespace App\Services;

    use App\Traits\Models\User;
    use App\Traits\Services\Hash;
    use App\Traits\Services\Jwt;
    use Illuminate\Foundation\Http\FormRequest;

    use function request;

    class AuthService {

        use Hash;
        use Jwt;
        use User;

        /**
         * @param  \Illuminate\Foundation\Http\FormRequest  $request
         *
         * @return array|bool
         * @throws \Throwable
         */
        public function login(FormRequest $request, $isAdmin = '1')
        {
            $userDetails = $this->getUserModel()->getLoginUserDetailsByEmail($request->get('email'), $isAdmin);
            if ($this->getHashService()->verifyHashForString($request->get('password'), $userDetails['userDetails']->password)) {
                $this->getUserModel()->updateLastLoginOfUserByUuid($userDetails['userDetails']->uuid);

                return $this->getJwtService()->generateJwtToken([
                    'uuid' => $userDetails['userDetails']->uuid,
                    'is_admin' => $userDetails['is_admin']
                ]);
            }

            return false;
        }

        /**
         * Create User from request.
         *
         * @param  \Illuminate\Foundation\Http\FormRequest  $request
         *
         * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
         */
        public function createUser(FormRequest $request)
        {
            return $this->getUserModel()->createUser($request->all());
        }

        public function logout()
        {
            return $this->getJwtService()->deleteJwtToken(request()->token);
        }
    }
