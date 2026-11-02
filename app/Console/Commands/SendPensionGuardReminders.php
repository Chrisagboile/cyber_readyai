<?php

namespace App\Console\Commands;

use App\Modules\PensionAccountGuard\Models\PensionGuardUser;
use App\Modules\PensionAccountGuard\Services\PensionGuardReminderService;
use Illuminate\Console\Command;
use Throwable;

class SendPensionGuardReminders extends Command
{
    protected $signature = 'pension-guard:send-reminders';

    protected $description =
        'Send due Pension Account Guard security checklist reminders';

    public function handle(
        PensionGuardReminderService $reminderService
    ): int {
        $processed = 0;
        $failed = 0;

        PensionGuardUser::query()
            ->select('id')
            ->chunkById(100, function ($users) use (
                $reminderService,
                &$processed,
                &$failed
            ) {
                foreach ($users as $userRecord) {
                    try {
                        $user = PensionGuardUser::find($userRecord->id);

                        if (! $user) {
                            continue;
                        }

                        $reminderService->remindAboutIncompleteChecks(
                            $user
                        );

                        $processed++;
                    } catch (Throwable $exception) {
                        $failed++;

                        report($exception);

                        $this->warn(
                            "A reminder failed for user ID {$userRecord->id}."
                        );
                    }
                }
            });

        $this->info("Users processed: {$processed}");
        $this->info("Failures: {$failed}");

        return $failed > 0
            ? self::FAILURE
            : self::SUCCESS;
    }
}
