<?php

namespace App\Libraries;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class JWTService
{
    private $key;
    private $alg;
    private $ttl; // Time to live in seconds (e.g., 900 for 15 mins)

    public function __construct()
    {
        // Typically, this key should be in .env (e.g., JWT_SECRET_KEY)
        $this->key = getenv('JWT_SECRET_KEY') ?: 'super-secret-default-key-change-me';
        $this->alg = getenv('JWT_ALG') ?: 'HS256';
        $this->ttl = (int)(getenv('JWT_TTL') ?: 900);
    }

    /**
     * Generate a new JWT token for a given user array.
     */
    public function generateToken(array $user): string
    {
        $issuedAt   = time();
        $expire     = $issuedAt + $this->ttl;
        
        $payload = [
            'iat'  => $issuedAt,
            'exp'  => $expire,
            'sub'  => $user['id'],
            'role' => $user['role_id'],
            'status' => $user['status']
        ];

        return JWT::encode($payload, $this->key, $this->alg);
    }

    /**
     * Decode a token and return the payload. Throws Exception if invalid.
     */
    public function decodeToken(string $token): object
    {
        return JWT::decode($token, new Key($this->key, $this->alg));
    }
}
