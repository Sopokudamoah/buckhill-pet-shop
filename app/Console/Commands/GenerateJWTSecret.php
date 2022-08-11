<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Console\ConfirmableTrait;

class GenerateJWTSecret extends Command
{
    use ConfirmableTrait;

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

    /**
     * @return int
     */
    public function handle()
    {
        if ((!$this->confirmToProceed())) {
            return 0;
        }

        $this->generateKeyPairs();

        $this->components->info('JWT secret key set successfully.');

        return 0;
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
