<?php

namespace App\Guards;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\JoinClause;

/**
 *
 */
class JwtAuthGuard
{
    /**
     * @var string
     */
    private string $token;

    /**
     * @var string
     */
    public static string $user_key = 'user_uuid';

    /** @var string */
    public static string $token_key = 'token_id';

    /**
     * @param string $token
     */
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
        return $this->isValidPayload($payload);
    }


    /**
     * @param array $payload
     * @return User|null
     */
    private function isValidPayload(array $payload)
    {
        $user_uuid = $payload[self::$user_key];
        $token_id = $payload[self::$token_key];

        return User::query()->join('jwt_tokens', function (JoinClause $clause) use ($token_id) {
            $clause->on('user_id', '=', 'users.id')
                ->where('jwt_tokens.id', '=', $token_id);
        })->uuid($user_uuid)->first(['users.*']);
    }
}
