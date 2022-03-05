<?php

namespace Tests\Feature\App\User\Controllers;

use App\Traits\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\BuckhillBaseTesting;

class AuthTest extends BuckhillBaseTesting
{
    use RefreshDatabase;
    use User;

    /**
     * Admin Login Success Test.
     *
     * @return void
     */
    public function test_user_login_success()
    {
        $this->loginUser();
        $response = $this->decodeResponseJson();

        $this->assertResponseStatus(200);
        $this->assertDatabaseHas('jwt_tokens', ['unique_id' => $response['data']['token']]);
    }

    /**
     * Admin Login Success Test.
     *
     * @return void
     */
    public function test_user_login_failure()
    {
        $userDetails = DB::table('users')->where('is_admin', '=', 0)->first();

        $this->call('POST', 'api/v1/user/login', [
            'email' => $userDetails->email,
            'password' => 'password1234'
        ]);

        $this->assertResponseStatus(401);
    }


    public function test_user_logout_success()
    {
        $this->loginUser();
        $responseContent = $this->decodeResponseJson();

        $this->get('/api/v1/user/logout', ['Authorization' => $responseContent['data']['token']]);
        $responseContent = $this->decodeResponseJson();

        $this->assertTrue($responseContent['status']);
        $this->assertEquals("Logout Success", $responseContent['data']);
        $this->assertResponseStatus(200);
    }

}

