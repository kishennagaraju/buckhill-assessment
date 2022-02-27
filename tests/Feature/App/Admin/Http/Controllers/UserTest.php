<?php

namespace Tests\Feature\App\Admin\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\App\AdminBaseTesting;

class UserTest extends AdminBaseTesting
{
    use RefreshDatabase;

    public function test_get_user_listing_for_admin_success()
    {
        $jwtTokenDetails = $this->getJwtTokenForUser($this->getAdminUser()->toArray());

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
        $jwtTokenDetails = $this->getJwtTokenForUser($this->getAdminUser()->toArray());

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
}
