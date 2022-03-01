<?php

namespace App\Services;

use App\Traits\Models\JwtTokens;
use App\Traits\Models\User;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Config;

class JwtService {

    use JwtTokens;
    use User;

    /**
     * Generate a JWT Token for the provided payload.
     *
     * @param array $data
     *
     * @return array
     */
    public function generateJwtToken(array $data, bool $storeToken = true): array
    {
        $jwtExpiresAt = Config::get('services.jwt.expiry');
        $expiresAt = time() + $jwtExpiresAt;

        $payload = [
            'user' => $data['uuid'],
        ];

        if (empty($data['iat'])) {
            $payload = array_merge($data, [
                'iat' => time()
            ]);
        } else {
            $payload['iat'] = $data['iat'];
        }

        if (empty($data['exp'])) {
            $payload = array_merge($data, [
                'exp' => $expiresAt
            ]);
        } else {
            $expiresAt = $data['exp'];
            $payload['exp'] = $data['exp'];
        }

        $jwtToken = JWT::encode($payload, $this->getPrivateKey(), 'RS256');

        if ($storeToken) {
            $this->getUserModel()->storeJwtTokenDetailsForUser($data['uuid'], [
                'unique_id' => $jwtToken,
                'expires_at' => date('Y-m-d h:i:s', $expiresAt),
                'token_title' => $data['is_admin'] ? 'Admin Token' : 'User Token'
            ]);
        }

        return [
            'token' => $jwtToken,
            'expires_at' => $expiresAt
        ];
    }

    /**
     * Decode the JWT token for the details
     *
     * @param  string  $jwtToken
     *
     * @throws \Firebase\JWT\ExpiredException
     * @return object|bool
     */
    public function decodeJwtToken(string $jwtToken, bool $updateUser = true): object
    {
        try {
            JWT::$timestamp = time();
            $decodedTokenDetails = JWT::decode($jwtToken, new Key($this->getPublicKey(), 'RS256'));
            if ($updateUser) {
                $this->getJwtTokensModel()->updateLastUsedDateForToken($jwtToken);
            }
            return $decodedTokenDetails;
        } catch (ExpiredException $ex) {
            // do nothing
        }

        return false;
    }

    /**
     * Decode the JWT token for the details
     *
     * @param  string  $jwtToken
     *
     * @throws \Firebase\JWT\ExpiredException
     * @return bool
     */
    public function verifyJwtToken(string $jwtToken): bool
    {
        $status = false;
        try {
            JWT::$timestamp = time();
            JWT::decode($jwtToken, new Key($this->getPublicKey(), 'RS256'));
            $status = true;
        } catch (ExpiredException $ex) {
            // do nothing
        }

        return $status;
    }

    /**
     * Get the Private Key File Contents.
     *
     * @return mixed
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

    /**
     * @param  string  $token
     * @param  array   $relationships
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function getJwtTokenDetails(string $token, array $relationships = [])
    {
        return $this->getJwtTokensModel()->getJwtTokenDetails($token, $relationships);
    }

    /**
     * @param  string  $token
     *
     * @return bool
     */
    public function updateJwtTokenUsage(string $token): bool
    {
        return $this->getJwtTokensModel()->updateJwtToken($token, ['last_used_at' => now()]);
    }

    /**
     * @param  string  $token
     *
     * @return bool
     */
    public function deleteJwtToken(string $token): bool
    {
        return $this->getJwtTokensModel()->deleteToken($token);
    }
}
