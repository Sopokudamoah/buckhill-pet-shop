<?php

namespace App\Listeners;

use App\Events\UserLoggedIn;

class UserLoggedInListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param UserLoggedIn $event
     * @return void
     */
    public function handle(UserLoggedIn $event)
    {
        $user = $event->user;
        $user->last_login_at = now();
        $user->saveQuietly();
    }
}
