<?php

namespace App\Guards;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;

/**
 *
 */
class JwtAuthGuard implements Guard
{

    /**
     * @var UserProvider|null
     */
    private ?UserProvider $provider;
    /**
     * @var Authenticatable|null
     */
    private ?Authenticatable $user;

    /**
     * @param UserProvider|null $provider
     */
    public function __construct(?UserProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @return bool
     */
    public function check()
    {
        return isset($this->user);
    }

    /**
     * @return bool
     */
    public function guest()
    {
        return !isset($this->user);
    }

    /**
     * @return Authenticatable|null
     */
    public function user()
    {
        return $this->user;
    }

    /**
     * @return int|mixed|string|null
     */
    public function id()
    {
        return $this->user->getAuthIdentifier();
    }

    /**
     * @param array $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        if (empty($credentials['username']) || empty($credentials['password'])) {
            return false;
        }

        $user = $this->provider->retrieveById($credentials['username']);

        if (!isset($user)) {
            return false;
        }

        if ($this->provider->validateCredentials($user, $credentials)) {
            $this->setUser($user);
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param Authenticatable $user
     * @return void
     */
    public function setUser(Authenticatable $user)
    {
        $this->user = $user;
    }

    /**
     * @return bool
     */
    public function hasUser()
    {
        return $this->check();
    }
}
