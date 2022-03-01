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
        $uuid = json_decode(json_encode(Str::uuid()), true);
        $data = [
            'uuid' => $uuid,
        ];
        $jwtToken = $this->getJwtService()->generateJwtToken($data, false);

        $status = $this->getJwtService()->verifyJwtToken($jwtToken['token']);
        $decodedTokenDetails = json_decode(
            json_encode(
                $this->getJwtService()->decodeJwtToken(
                    $jwtToken['token'],
                    false
                )
            ),
            true
        );

        $this->assertTrue($status);
        $this->assertEquals($uuid, $decodedTokenDetails['uuid']);
    }

    public function test_jwt_generate_failure()
    {
        $uuid = json_decode(json_encode(Str::uuid()), true);
        $data = [
            'uuid' => json_decode(json_encode(Str::uuid()), true),
        ];

        $dataUpt = $data;
        $dataUpt['email'] = 'test2@buckhill.com';

        $jwtToken = $this->getJwtService()->generateJwtToken($dataUpt, false);

        $status = $this->getJwtService()->verifyJwtToken($jwtToken['token']);
        $decodedTokenDetails = json_decode(
            json_encode(
                $this->getJwtService()->decodeJwtToken(
                    $jwtToken['token'],
                    false
                )
            ),
            true
        );

        $this->assertTrue($status);
        $this->assertNotEquals($uuid, $decodedTokenDetails['uuid']);
    }
}
