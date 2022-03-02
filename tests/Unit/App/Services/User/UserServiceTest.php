<?php

namespace Tests\Unit\App\Services\User;

use App\Traits\Services\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\BuckhillBaseTesting;

class UserServiceTest extends BuckhillBaseTesting
{
    use User;

    public function test_user_login_success()
    {
        DB::table('users')->where('is_admin', '=', 0)->update([
            'password' => Hash::make('user')
        ]);

        $userDetails = $this->getUserModel()->newQuery()->where('is_admin', '=', 0)->first();

        $request = new FormRequest();
        $request->replace([
            'email' => $userDetails->email,
            'password' => 'user'
        ]);

        $login = $this->getUserService()->login($request);

        $this->assertIsArray($login);
        $this->assertNotEmpty($login['token']);
    }

    public function test_user_login_failure()
    {
        DB::table('users')->where('is_admin', '=', 0)->update([
            'password' => Hash::make('user')
        ]);

        $userDetails = $this->getUserModel()->newQuery()->where('is_admin', '=', 0)->first();

        $request = new FormRequest();
        $request->replace([
            'email' => $userDetails->email,
            'password' => 'password'
        ]);

        $login = $this->getUserService()->login($request);

        $this->assertFalse($login);
    }
}
