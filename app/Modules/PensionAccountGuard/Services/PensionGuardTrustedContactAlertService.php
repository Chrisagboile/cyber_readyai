<?php

namespace App\Modules\PensionAccountGuard\Services;

use App\Modules\PensionAccountGuard\Models\PensionGuardTrustedContact;
use App\Modules\PensionAccountGuard\Models\PensionGuardUser;
use App\Modules\PensionAccountGuard\Notifications\TrustedContactAlertNotification;
use Illuminate\Support\Facades\Notification;
use InvalidArgumentException;

class PensionGuardTrustedContactAlertService
{
    private const ALLOWED_CATEGORIES = [
        'security_reminders',
        'breach_alerts',
        'provider_incidents',
        'fraud_warnings',
    ];

    public function send(
        PensionGuardUser $user,
        PensionGuardTrustedContact $contact,
        string $category,
        string $title,
        string $message
    ): bool {
        $title = trim($title);
        $message = trim($message);

        if (! in_array($category, self::ALLOWED_CATEGORIES, true)) {
            throw new InvalidArgumentException(
                'The trusted-contact alert category is invalid.'
            );
        }

        if ($title === '' || $message === '') {
            throw new InvalidArgumentException(
                'An alert title and message are required.'
            );
        }

        if (mb_strlen($title) > 120 || mb_strlen($message) > 1000) {
            throw new InvalidArgumentException(
                'The alert title or message is too long.'
            );
        }

        // A contact must belong to the account holder initiating the alert.
        if ((string) $contact->user_id !== (string) $user->getKey()) {
            return false;
        }

        // Refresh the record so that we do not rely on stale consent state.
        $contact->refresh();

        if (! $contact->canReceiveAlert($category)) {
            return false;
        }

        Notification::route('mail', $contact->email)->notify(
            new TrustedContactAlertNotification(
                $title,
                $message,
                $category
            )
        );

        return true;
    }
}
