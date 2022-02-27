<?php

namespace Tests\App\Services;

use App\Traits\Services\Jwt;
use Illuminate\Support\Str;
use Tests\TestCase;

class JwtServiceTest extends TestCase
{
    use Jwt;

    public function test_jwt_generate_success()
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

        $jwtToken = $this->getJwtService()->generateJwtToken($data);

        $decodedTokenDetails = $this->getJwtService()->decodeJwtToken($jwtToken['token']);

        $this->assertEquals($decodedTokenDetails->email, $data['email']);
    }

    public function test_jwt_generate_failure()
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

        $dataUpt = $data;
        $dataUpt['email'] = 'test2@buckhill.com';

        $jwtToken = $this->getJwtService()->generateJwtToken($dataUpt);

        $decodedTokenDetails = $this->getJwtService()->decodeJwtToken($jwtToken['token']);

        $this->assertNotEquals($decodedTokenDetails->email, $data['email']);
    }
}
