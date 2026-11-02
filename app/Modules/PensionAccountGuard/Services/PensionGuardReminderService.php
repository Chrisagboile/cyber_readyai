<?php

namespace App\Modules\PensionAccountGuard\Services;

use App\Modules\PensionAccountGuard\Models\PensionGuardAccount;
use App\Modules\PensionAccountGuard\Models\PensionGuardUser;
use App\Modules\PensionAccountGuard\Notifications\SecurityReminderNotification;
use Illuminate\Support\Facades\DB;

class PensionGuardReminderService
{
    public function remindAboutIncompleteChecks(
        PensionGuardUser $user
    ): void {
        $hasIncompleteChecks = PensionGuardAccount::where(
            'user_id',
            $user->id
        )
            ->whereHas('securityChecks', function ($query) {
                $query->where('is_complete', false);
            })
            ->exists();

        if (! $hasIncompleteChecks) {
            return;
        }

        // Avoid another reminder of this type within seven days.
        $recentReminderExists = $user->notifications()
            ->where('type', SecurityReminderNotification::class)
            ->where('data->title', 'Security checklist reminder')
            ->where('created_at', '>=', now()->subDays(7))
            ->exists();

        if ($recentReminderExists) {
            return;
        }

        DB::transaction(function () use ($user) {
            $user->notify(
                new SecurityReminderNotification(
                    'Security checklist reminder',
                    'You have unfinished pension security checks. Review your checklist and consider completing the remaining actions.'
                )
            );
        });
    }
}
