<?php


return [
    # Lifespan of token in seconds
    'expiration' => 3600,  //default 1 hour


    'private_key_path' => storage_path('jwt-private-key.key'),

    'public_key_path' => storage_path('jwt-public-key.key'),

    'algo' => 'RS256'
];
