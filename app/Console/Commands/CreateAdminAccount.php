<?php

namespace App\Console\Commands;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Console\Command;

class CreateAdminAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates the initial admin account';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = 'admin@buckhill.co.uk';

        if (!Admin::whereEmail('admin@buckhill.co.uk')->exists()) {
            User::factory()->isAdmin()->create([
                'email' => $email,
                'first_name' => 'Administrator',
                'last_name' => 'System'
            ]);
        }

        $this->components->info('Admin account created');

        return 0;
    }
}
