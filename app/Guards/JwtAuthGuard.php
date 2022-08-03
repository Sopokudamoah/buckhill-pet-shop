<?php

namespace App\Guards;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 *
 */
class JwtAuthGuard
{
    private string $token;

    public static $user_key = 'user_uuid';

    public function __construct(string $token)
    {
        $this->token = $token;
    }


    /**
     * @return Model|null
     */
    public function getUserFromToken()
    {
        $payload = jwt_decode($this->token);

        return User::findByUuid($payload[self::$user_key]);
    }


    private function isValidPayload(array $payload)
    {
        return isset($payload[self::$user_key]);
    }
}
