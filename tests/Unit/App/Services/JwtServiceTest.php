<?php

namespace Tests\App\Services;

use App\Traits\Services\Jwt;
use Illuminate\Support\Str;
use Tests\BuckhillBaseTesting;
use Tests\TestCase;

class JwtServiceTest extends BuckhillBaseTesting
{
    use Jwt;

    public function test_jwt_generate_success()
    {
        $uuid = json_decode(json_encode(Str::uuid()), true);
        $data = [
            'uuid' => $uuid,
            'is_admin' => false
        ];

        $jwtToken = $this->getJwtService()->generateJwtToken($data, false);

        $decodedTokenDetails = json_decode(
            json_encode(
                $this->getJwtService()->decodeJwtToken(
                    $jwtToken['token'],
                    false
                )
            ),
            true
        );

        $this->assertEquals($uuid, $decodedTokenDetails['uuid']);
    }

    public function test_jwt_generate_failure()
    {
        $uuid = json_decode(json_encode(Str::uuid()), true);
        $data = [
            'uuid' => json_decode(json_encode(Str::uuid()), true),
            'is_admin' => false
        ];

        $dataUpt = $data;
        $dataUpt['email'] = 'test2@buckhill.com';

        $jwtToken = $this->getJwtService()->generateJwtToken($dataUpt, false);

        $decodedTokenDetails = json_decode(
            json_encode(
                $this->getJwtService()->decodeJwtToken(
                    $jwtToken['token'],
                    false
                )
            ),
            true
        );

        $this->assertNotEquals($uuid, $decodedTokenDetails['uuid']);
    }
}
