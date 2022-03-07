<?php

namespace Tests\Feature\App\Admin\Controllers;

use Faker\Factory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\BuckhillBaseTesting;

class UserTest extends BuckhillBaseTesting
{
    use RefreshDatabase;

    public function test_get_user_listing_for_admin_success()
    {
        $jwtTokenDetails = $this->getJwtTokenForUser($this->getAdminUser()->toArray(), true);

        $this->call('GET', 'api/v1/admin/user-listing?token=' . $jwtTokenDetails['token']);

        $this->assertResponseStatus(200);
    }

    public function test_get_user_listing_for_admin_without_token()
    {
        $this->call('GET', 'api/v1/admin/user-listing');

        $this->assertResponseStatus(401);
    }

    public function test_delete_user_success()
    {
        $userDetails = $this->getUser();
        $jwtTokenDetails = $this->getJwtTokenForUser($this->getAdminUser()->toArray(), true);

        $this->call('DELETE', 'api/v1/admin/user-delete/' . $userDetails->uuid . '?token=' . $jwtTokenDetails['token']);

        $this->assertResponseStatus(200);
        $this->assertDatabaseMissing('users', $userDetails->toArray());
    }

    public function test_delete_user_failure()
    {
        $userDetails = $this->getUser();

        $this->call('DELETE', 'api/v1/admin/user-delete/' . $userDetails->uuid);

        $this->assertResponseStatus(401);
        $this->assertDatabaseHas('users', ['email' => $userDetails->email]);
    }

    /**
     * Admin Login Success Test.
     *
     * @return void
     */
    public function test_admin_create_success()
    {
        $faker = Factory::create();
        $data = [
            'email' => $faker->email(),
            'password' => 'password',
            'first_name' => $faker->firstName(),
            'last_name' => $faker->lastName(),
            'password_confirmation' => 'password',
            'avatar' => Str::uuid(),
            'address' => $faker->address(),
            'phone_number' => $faker->phoneNumber()
        ];

        $this->call('POST', 'api/v1/admin/create', $data);

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

    /**
     * Admin Login Success Test.
     *
     * @return void
     */
    public function test_admin_user_edit_success()
    {
        $userDetails = $this->getUser()->toArray();

        $this->put('/api/v1/admin/user-edit/' . $userDetails['uuid'], [
            'first_name' => 'Test',
            'last_name' => 'Update User',
            'email' => $userDetails['email'],
            'password' => 'userpass123444',
            'password_confirmation' => 'userpass123444',
            'avatar' => $userDetails['avatar'],
            'address' => $userDetails['address'],
            'phone_number' => $userDetails['phone_number'],
        ], ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);

        $this->assertResponseStatus(200);
    }

    /**
     * Admin Login Success Test.
     *
     * @return void
     */
    public function test_admin_user_edit_unauthorized()
    {
        $userDetails = $this->getUser()->toArray();

        $this->put('/api/v1/admin/user-edit/' . $userDetails['uuid'], [
            'first_name' => 'Test',
            'last_name' => 'Update User',
            'email' => $userDetails['email'],
            'password' => 'userpass123444',
            'password_confirmation' => 'userpass123444',
            'avatar' => $userDetails['avatar'],
            'address' => $userDetails['address'],
            'phone_number' => $userDetails['phone_number'],
        ]);

        $this->assertResponseStatus(401);
    }

    /**
     * Admin Login Success Test.
     *
     * @return void
     */
    public function test_admin_user_edit_not_found()
    {
        $this->expectException(ModelNotFoundException::class);
        $userDetails = $this->getUser()->toArray();

        $this->put('/api/v1/admin/user-edit/23423424234', [
            'first_name' => 'Test',
            'last_name' => 'Update User',
            'email' => $userDetails['email'],
            'password' => 'userpass123444',
            'password_confirmation' => 'userpass123444',
            'avatar' => $userDetails['avatar'],
            'address' => $userDetails['address'],
            'phone_number' => $userDetails['phone_number'],
        ], ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);
    }

    /**
     * Admin Login Success Test.
     *
     * @return void
     */
    public function test_admin_user_delete_success()
    {
        $userDetails = $this->getUser()->toArray();
        $this->delete('/api/v1/admin/user-delete/' . $userDetails['uuid'] . '?token=' . $this->getAuthTokenForAdmin());

        $this->assertResponseStatus(200);
    }

    /**
     * Admin Login Success Test.
     *
     * @return void
     */
    public function test_admin_user_delete_unauthorized()
    {
        $userDetails = $this->getUser()->toArray();

        $this->delete('/api/v1/admin/user-delete/' . $userDetails['uuid']);

        $this->assertResponseStatus(401);
    }

    /**
     * Admin Login Success Test.
     *
     * @return void
     */
    public function test_admin_user_delete_not_found()
    {
        $this->expectException(ModelNotFoundException::class);
        $userDetails = $this->getUser()->toArray();

        $this->delete('/api/v1/admin/user-delete/23423424234?token=' . $this->getAuthTokenForAdmin());
    }
}
