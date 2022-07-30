<?php


use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;

/**
 * @param array $payload
 * @param string $algo
 * @return string
 */
function jwt_encode(array $payload, string $algo = 'HS256')
{
    $now = now();
    $domain_name = request()->getSchemeAndHttpHost();

    $expire_at = $now->addSeconds(config('jwt.expiration'));

    $predefined_data = [
        'iss' => $domain_name,
        'aud' => $domain_name,
        'iat' => $now->getTimestamp(),
        'nbf' => $now->getTimestamp(),
        'exp' => $expire_at->getTimestamp(),
    ];

    return JWT::encode(array_merge($payload, $predefined_data), config('jwt.secret'), $algo);
}

/**
 * @param string $jwt
 * @param string $algo
 * @return array
 * @throws SignatureInvalidException|ExpiredException
 */
function jwt_decode(string $jwt, string $algo = 'HS256')
{
    return (array) JWT::decode($jwt, new Key(config('jwt.secret'), $algo));
}
