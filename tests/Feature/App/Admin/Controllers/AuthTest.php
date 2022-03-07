<?php

namespace Tests\Feature\App\Admin\Controllers;

use App\Traits\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
    public function test_admin_login_success()
    {
        $userDetails = $this->getUserModel()->newQuery()->where('is_admin', '=', 1)->first();
        $this->getUserModel()->newQuery()->where('id', '=', $userDetails->id)->update([
            'password' => Hash::make('admin')
        ]);

        $this->call('POST', 'api/v1/admin/login', [
            'email' => $userDetails->email,
            'password' => 'admin'
        ]);

        $response = $this->decodeResponseJson();

        $this->assertResponseStatus(200);
        $this->assertDatabaseHas('jwt_tokens', ['unique_id' => $response['data']['token']]);
    }

    /**
     * Admin Login Success Test.
     *
     * @return void
     */
    public function test_admin_login_failure()
    {
        $userDetails = DB::table('users')->where('is_admin', '=', 1)->first();

        $this->call('POST', 'api/v1/admin/login', [
            'email' => $userDetails->email,
            'password' => 'userpassword'
        ]);

        $this->assertResponseStatus(401);
    }


    public function test_admin_logout_success()
    {
        $adminUserDetails = $this->getAdminUser();
        $this->getUserModel()->newQuery()->where('is_admin', '=', 1)->update([
            'password' => Hash::make('admin')
        ]);
        $this->post('/api/v1/admin/login', [
            'email' => $adminUserDetails->email,
            'password' => 'admin'
        ]);

        $responseContent = $this->decodeResponseJson();

        $this->get('/api/v1/admin/logout', ['Authorization' => $responseContent['data']['token']]);
        $responseContent = $this->decodeResponseJson();

        $this->assertTrue($responseContent['status']);
        $this->assertEquals("Logout Success", $responseContent['data']);
        $this->assertResponseStatus(200);
    }
}

