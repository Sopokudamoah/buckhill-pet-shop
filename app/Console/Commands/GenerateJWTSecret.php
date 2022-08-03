<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Console\KeyGenerateCommand;
use Illuminate\Support\Str;

class GenerateJWTSecret extends KeyGenerateCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jwt:generate
                            {--force : Force the operation to run when in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new secret key for JWT implementation';

    protected string $env_key = 'JWT_SECRET';

    /**
     * @return int|void
     */
    public function handle()
    {
        $key = Str::random(64);


        if (! $this->setKeyInEnvironmentFile($key)) {
            return;
        }

        $this->generateKeyPairs();

        $this->components->info('JWT secret key set successfully.');

        return 0;
    }

    /**
     * @return string
     */
    protected function keyReplacementPattern()
    {
        $escaped = preg_quote('='.$this->laravel['config']['jwt.secret'], '/');

        return "/^$this->env_key{$escaped}/m";
    }

    /**
     * Set the application key in the environment file.
     *
     * @param  string  $key
     * @return bool
     */
    protected function setKeyInEnvironmentFile($key)
    {
        $currentKey = $this->laravel['config']['jwt.secret'];

        if (strlen($currentKey) !== 0 && (! $this->confirmToProceed())) {
            return false;
        }

        $this->writeNewEnvironmentFileWith($key);

        return true;
    }

    /**
     * @param $key
     * @return void
     */
    protected function writeNewEnvironmentFileWith($key)
    {
        file_put_contents($this->laravel->environmentFilePath(), preg_replace(
            $this->keyReplacementPattern(),
            "{$this->env_key}={$key}",
            file_get_contents($this->laravel->environmentFilePath())
        ));
    }

    private function generateKeyPairs()
    {
        $privateKeyResource = openssl_pkey_new([
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA
        ]);

        // Save the private key to a file. Never share this file with anyone.
        openssl_pkey_export_to_file($privateKeyResource, $this->laravel['config']['jwt.private_key_path']);

        // Generate the public key for the private key
        $privateKeyDetailsArray = openssl_pkey_get_details($privateKeyResource);

        // Save the public key to another file. Make this file available to anyone (especially anyone who wants to send you encrypted data).
        file_put_contents($this->laravel['config']['jwt.public_key_path'], $privateKeyDetailsArray['key']);
    }
}
