<?php

    namespace App\Services\Admin;

    use App\Http\Transformers\UserTransformer;
    use App\Traits\Models\User;
    use App\Traits\Services\Hash;
    use App\Traits\Services\Jwt;
    use Firebase\JWT\ExpiredException;
    use Illuminate\Foundation\Http\FormRequest;

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
        public function login(FormRequest $request)
        {
            $userDetails = $this->getUserModel()->getAdminUserDetailsByEmail($request->get('email'))->firstOrFail();
            if ($this->getHashService()->verifyHashForString($request->get('password'), $userDetails->password)) {
                $this->getUserModel()->updateLastLoginOfUser($userDetails->id);

                return $this->getJwtService()->generateJwtToken($userDetails->toArray());
            }

            return false;
        }

        /**
         * Create User from request.
         *
         * @param  \Illuminate\Foundation\Http\FormRequest  $request
         *
         * @return \Spatie\Fractal\Fractal
         */
        public function createUser(FormRequest $request)
        {
            return fractal($this->getUserModel()->createUser($request->all()), new UserTransformer());
        }
    }
