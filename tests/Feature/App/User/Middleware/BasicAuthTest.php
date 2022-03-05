<?php

namespace Tests\Feature\App\User\Middleware;

use App\Http\Middleware\BasicAuth;
use App\Traits\Services\Jwt;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\BuckhillBaseTesting;

class BasicAuthTest extends BuckhillBaseTesting
{
    use RefreshDatabase;
    use Jwt;

    /**
     * Testing the BasicAuthAdmin Middleware used for checking JWT Tokens.
     *
     * @return void
     */
    public function test_basic_user_middleware_success()
    {
        $this->loginUser();
        $response = $this->decodeResponseJson();

        $request = FormRequest::create('/api/v1/user', 'GET');
        $request->merge([
            'token' => $response['data']['token']
        ]);

        $middleware = new BasicAuth();
        $response = $middleware->handle($request, function() {
            return "SUCCESS";
        });
        $this->assertEquals("SUCCESS", $response);
    }

    /**
     * Testing the BasicAuthAdmin Middleware used for checking JWT Tokens.
     *
     * @return void
     */
    public function test_basic_auth_middleware_failure_expired_token()
    {
        $userDetails = $this->getUserModel()->createUser([
            'first_name' => 'Test',
            'last_name' => 'User',
            'uuid' => Str::uuid(),
            'email' => 'usertest@buckhill.co.uk',
            'password' => Hash::make('testuser'),
            'avatar' => Str::uuid(),
            'address' => '5303 Lubowitz Creek Suite 678 Reingerhaven, ND 62609',
            'phone_number' => '+1.253.273.7280'
        ]);

        $jwtTokenDetails = $this->getJwtTokenForUser(array_merge($userDetails->toArray(), [
            'iat' => time(),
            'exp' => time() - 5000
        ]));

        $this->get('/api/v1/user', ['Authorization' => $jwtTokenDetails['token']]);
        $responseContent = $this->decodeResponseJson();

        $this->assertFalse($responseContent['status']);
        $this->assertEquals("Invalid Token", $responseContent['message']);
        $this->assertResponseStatus(422);
    }

    /**
     * Testing the BasicAuthAdmin Middleware used for checking JWT Tokens.
     *
     * @return void
     */
    public function test_basic_auth_middleware_failure_without_token()
    {
        $request = FormRequest::create('/api/v1/user', 'GET');

        $middleware = new BasicAuth();
        $response = $middleware->handle($request, function() {
            return "SUCCESS";
        });
        $responseContent = json_decode($response->content(), true);

        $this->assertFalse($responseContent['status']);
        $this->assertEquals("Unauthorized", $responseContent['message']);
        $this->assertEquals(401, $response->getStatusCode());
    }

    /**
     * Testing the BasicAuthAdmin Middleware used for checking JWT Tokens.
     *
     * @return void
     */
    public function test_basic_auth_middleware_failure_user_not_found()
    {
        $this->loginUser();
        $responseContent = $this->decodeResponseJson();
        $this->getJwtService()->deleteJwtToken($responseContent['data']['token']);

        $this->get('/api/v1/user', ['Authorization' => $responseContent['data']['token']]);
        $responseContent = $this->decodeResponseJson();


        $this->assertFalse($responseContent['status']);
        $this->assertEquals("User Not Found", $responseContent['message']);
        $this->assertResponseStatus(404);
    }
}
