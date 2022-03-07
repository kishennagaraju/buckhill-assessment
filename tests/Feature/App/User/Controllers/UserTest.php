<?php

namespace Tests\Feature\App\User\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\BuckhillBaseTesting;

class UserTest extends BuckhillBaseTesting
{
    use RefreshDatabase;

    public function test_get_user_details_success()
    {
        $this->loginUser();
        $responseContent = $this->decodeResponseJson();

        $this->get('api/v1/user', [
            'Authorization' => $responseContent['data']['token']
        ]);

        $responseContent = $this->decodeResponseJson();

        $this->assertResponseStatus(200);
        $this->assertNotEmpty($responseContent);
    }

    public function test_get_user_details_without_token()
    {
        $this->call('GET', 'api/v1/user');

        $this->assertResponseStatus(401);
    }

    public function test_delete_user_success()
    {
        $user = $this->getUser();

        $this->delete('api/v1/user/' . $user->uuid . '?token=' . $this->getAuthTokenForAdmin());

        $responseContent = $this->decodeResponseJson();

        $this->assertResponseStatus(200);
        $this->assertTrue($responseContent['status']);
        $this->assertEquals($responseContent['message'], 'User Deleted');
    }

    public function test_delete_user_failure()
    {
        $user = $this->getUser();
        $this->call('DELETE', 'api/v1/user/' . $user->uuid);

        $this->assertResponseStatus(401);
    }

    /**
     * Admin Login Success Test.
     *
     * @return void
     */
    public function test_user_create_success()
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

        $this->call('POST', 'api/v1/user/create', $data);

        $response = $this->decodeResponseJson();

        $this->assertDatabaseHas('users', ['email' => $response['email']]);
        $this->assertEquals($data['email'], $response['email']);
        $this->assertResponseStatus(200);
    }

    /**
     * Admin Login Success Test.
     *
     * @return void
     */
    public function test_user_create_failure()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $data = [
            'email' => 'test@buckhill.com',
            'password' => 'password',
            'first_name' => 'Test',
            'last_name' => 'User'
        ];

        $this->call('POST', 'api/v1/user/create', $data);
    }

    /**
     * Admin Login Success Test.
     *
     * @return void
     */
    public function test_user_edit_success()
    {
        $user = $this->getUser()->toArray();

        $data = [
            'email' => 'test2131@buckhill.com',
            'password' => 'password',
            'first_name' => 'Test',
            'last_name' => 'User',
            'password_confirmation' => 'password',
            'avatar' => Str::uuid(),
            'address' => '5303 Lubowitz Creek Suite 678 Reingerhaven, ND 62609',
            'phone_number' => '+1.253.273.7280'
        ];

        $this->put('api/v1/user/'. $user['uuid'], $data, ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);
        $this->assertResponseStatus(200);
    }

    /**
     * Admin Login Success Test.
     *
     * @return void
     */
    public function test_user_edit_unauthorized()
    {
        $user = $this->getUser()->toArray();

        $data = [
            'email' => 'test2131@buckhill.com',
            'password' => 'password',
            'first_name' => 'Test',
            'last_name' => 'User',
            'password_confirmation' => 'password',
            'avatar' => Str::uuid(),
            'address' => '5303 Lubowitz Creek Suite 678 Reingerhaven, ND 62609',
            'phone_number' => '+1.253.273.7280'
        ];

        $this->put('api/v1/user/'. $user['uuid'], $data);
        $this->assertResponseStatus(401);
    }

    /**
     * Admin Login Success Test.
     *
     * @return void
     */
    public function test_user_edit_not_found()
    {
        $this->expectException(ModelNotFoundException::class);
        $data = [
            'email' => 'test2131@buckhill.com',
            'password' => 'password',
            'first_name' => 'Test',
            'last_name' => 'User',
            'password_confirmation' => 'password',
            'avatar' => Str::uuid(),
            'address' => '5303 Lubowitz Creek Suite 678 Reingerhaven, ND 62609',
            'phone_number' => '+1.253.273.7280'
        ];

        $this->put('api/v1/user/23234234234', $data, ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);
    }

    /**
     * Admin Login Success Test.
     *
     * @return void
     */
    public function test_user_get_orders_success()
    {
        $token = $this->getAuthTokenForUser();
        $order = $this->storeOrder($token);

        $this->get('api/v1/user/orders', ['Authorization' => 'Bearer ' . $token]);
        $this->assertResponseStatus(200);
    }

    /**
     * Admin Login Success Test.
     *
     * @return void
     */
    public function test_user_get_orders_unauthorized()
    {
        $token = $this->getAuthTokenForUser();
        $order = $this->storeOrder($token);

        $this->get('api/v1/user/orders');
        $this->assertResponseStatus(401);
    }
}
