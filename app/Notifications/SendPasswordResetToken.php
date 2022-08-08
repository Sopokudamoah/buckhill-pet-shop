<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;

class SendPasswordResetToken extends ResetPassword
{
    use Queueable;


    /**
     * @inheritDoc
     */
    protected function resetUrl($notifiable)
    {
        return url(route('api.v1.user.password-reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));
    }
}
