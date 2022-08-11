<?php

namespace App\Helpers;

class AccessToken
{
    public string $plainTextToken;

    public function __construct($jwt)
    {
        $this->plainTextToken = $jwt;
    }
}
