<?php


test('new public and private keys are created', function () {
    $public = @file_get_contents(config('jwt.public_key_path'));
    $private = @file_get_contents(config('jwt.private_key_path'));

    $this->artisan('jwt:generate');

    $new_public = @file_get_contents(config('jwt.public_key_path'));
    $new_private = @file_get_contents(config('jwt.private_key_path'));

    $this->assertNotEquals($public, $new_public);
    $this->assertNotEquals($private, $new_private);
});


test('confirm key generation on production', function () {
    $this->app->detectEnvironment(function () {
        return 'production';
    });

    $command = $this->artisan('jwt:generate');
    $command->expectsConfirmation('Do you really wish to run this command?');
});
