<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PensionGuardTrustedContactVerification extends Notification
{
    use Queueable;

    public function __construct(
        public string $verificationUrl
    ) {
    }

    /**
     * Send this notification by email only.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Build the verification email.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Verify your trusted contact email — Pension Account Guard')
            ->greeting('Hello,')
            ->line('Someone has added this email address as a trusted contact in Pension Account Guard.')
            ->line('Please verify that you can access this email address by using the button below.')
            ->action('Verify email address', $this->verificationUrl)
            ->line('This verification link expires after 60 minutes and can only be used once.')
            ->line('Verifying your email does not automatically authorise the sharing of pension information or security alerts.')
            ->line('If you were not expecting this email, you can ignore it.')
            ->salutation('Pension Account Guard');
    }
}
