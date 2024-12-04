<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPasswordNotification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends BaseResetPasswordNotification
{
    /**
     * Build the reset password email message.
     */
    public function toMail($notifiable)
    {
        $frontendUrl = config('app.frontend_url'); // Add this in your .env file
        $resetUrl = "{$frontendUrl}/reset-password?email={$notifiable->email}&token={$this->token}";

        return (new MailMessage)
            ->subject('Reset Your Password')
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->action('Reset Password', $resetUrl)
            ->line('If you did not request a password reset, no further action is required.');
    }
}
