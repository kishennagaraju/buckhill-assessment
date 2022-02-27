<?php

namespace Tests\Feature\App\Admin\Controllers;

use App\Traits\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\Feature\App\AdminBaseTesting;

class AuthTest extends AdminBaseTesting
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
        $userDetails = DB::table('users')->where('is_admin', '=', 1)->first();
        DB::table('users')->where('id', '=', $userDetails->id)->update([
            'password' => Hash::make('admin')
        ]);

        $this->call('POST', 'api/v1/admin/login', [
            'email' => $userDetails->email,
            'password' => 'admin'
        ]);

        $this->assertResponseStatus(200);
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


    /**
     * Admin Login Success Test.
     *
     * @return void
     */
    public function test_admin_create_success()
    {
        $data = [
            'email' => 'test@buckhill.com',
            'password' => 'password',
            'first_name' => 'Test',
            'last_name' => 'User',
            'password_confirmation' => 'password',
            'avatar' => Str::uuid(),
            'address' => '5303 Lubowitz Creek Suite 678 Reingerhaven, ND 62609',
            'phone_number' => '+1.253.273.7280'
        ];

        $this->call('POST', 'api/v1/admin/create', $data);

        $response = $this->decodeResponseJson();

        $this->assertDatabaseHas('users', ['email' => $response['data']['email']]);
        $this->assertEquals($data['email'], $response['data']['email']);
        $this->assertResponseStatus(200);
    }

    /**
     * Admin Login Success Test.
     *
     * @return void
     */
    public function test_admin_create_failure()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $data = [
            'email' => 'test@buckhill.com',
            'password' => 'password',
            'first_name' => 'Test',
            'last_name' => 'User'
        ];

        $this->call('POST', 'api/v1/admin/create', $data);
    }

}

