<?php

namespace App\Modules\PensionAccountGuard\Services;

use App\Modules\PensionAccountGuard\Models\PensionGuardUser;
use App\Modules\PensionAccountGuard\Notifications\SecurityReminderNotification;
use InvalidArgumentException;

class PensionGuardNotificationService
{
    public function send(
        PensionGuardUser $user,
        string $title,
        string $message
    ): void {
        $title = trim($title);
        $message = trim($message);

        if ($title === '' || $message === '') {
            throw new InvalidArgumentException(
                'A notification title and message are required.'
            );
        }

        if (mb_strlen($title) > 120 || mb_strlen($message) > 1000) {
            throw new InvalidArgumentException(
                'The notification title or message is too long.'
            );
        }

        $user->notify(
            new SecurityReminderNotification($title, $message)
        );
    }
}
