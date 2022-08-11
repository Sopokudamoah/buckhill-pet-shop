<?php


use Carbon\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * @param array $payload
 * @param string|null $algo
 * @param Carbon|null $expiresAt
 * @return string
 */
function jwt_encode(array $payload, Carbon $expiresAt = null, string $algo = null)
{
    $now = now();
    $domain_name = request()->getSchemeAndHttpHost();

    $expire_at = $expiresAt ?? now()->addSeconds(config('jwt.expiration'));

    $predefined_data = [
        'iss' => $domain_name,
        'aud' => $domain_name,
        'iat' => $now->getTimestamp(),
        'nbf' => $now->getTimestamp(),
        'exp' => $expire_at->getTimestamp(),
    ];

    $private_key = @file_get_contents(config('jwt.private_key_path'));

    return JWT::encode(array_merge($predefined_data, $payload), $private_key, $algo ?? config('jwt.algo'));
}

/**
 * @param string $jwt
 * @param string|null $algo
 * @return array
 */
function jwt_decode(string $jwt, string $algo = null)
{
    $public_key = @file_get_contents(config('jwt.public_key_path'));

    return (array) JWT::decode($jwt, new Key($public_key, $algo ?? config('jwt.algo')));
}
