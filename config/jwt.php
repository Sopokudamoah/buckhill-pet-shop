<?php


return [

    'secret' => env('JWT_SECRET'),

    # Lifespan of token in seconds
    'expiration' => 10800,  //default 3 hours


    'private_key_path' => storage_path('jwt-private-key.key'),

    'public_key_path' => storage_path('jwt-public-key.key'),
];
