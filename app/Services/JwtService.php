<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Config;

class JwtService {

    /**
     * Generate a JWT Token for the provided payload.
     *
     * @param array $data
     *
     * @return array
     */
    public function generateJwtToken(array $data): array
    {
        $jwtExpiresAt = Config::get('services.jwt.expiry');
        $payload = array_merge($data, [
            'iat' => time(),
            'exp' => time() + $jwtExpiresAt
        ]);

        return [
            'token' => JWT::encode($payload, $this->getPrivateKey(), 'RS256'),
            'expires_at' => time() + $jwtExpiresAt
        ];
    }

    /**
     * Decode the JWT token for the details
     *
     * @param $jwtToken
     *
     * @throws \Firebase\JWT\ExpiredException
     * @return object
     */
    public function decodeJwtToken($jwtToken): object
    {
        JWT::$timestamp = time();
        return JWT::decode($jwtToken, new Key($this->getPublicKey(), 'RS256'));
    }

    /**
     * Get the Private Key File Contents.
     *
     * @return false|resource
     */
    private function getPrivateKey()
    {
        return openssl_pkey_get_private(
            file_get_contents(Config::get('services.jwt.private_key_file')),
            Config::get('services.jwt.private_key_passphrase')
        );
    }

    /**
     * Get the Public Key File Contents.
     *
     * @return mixed
     */
    private function getPublicKey()
    {
        return openssl_pkey_get_details($this->getPrivateKey())['key'];
    }
}
