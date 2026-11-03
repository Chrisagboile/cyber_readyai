<?php

namespace App\Console\Commands;

use App\Modules\PensionAccountGuard\Models\PensionGuardUser;
use Illuminate\Console\Command;

class PromotePensionGuardAdmin extends Command
{
    protected $signature = 'pension-guard:promote-admin {username}';

    protected $description = 'Promote an existing Pension Guard user to administrator';

    public function handle(): int
    {
        $username = (string) $this->argument('username');

        $user = PensionGuardUser::where('username', $username)->first();

        if (! $user) {
            $this->error('Pension Guard user not found.');

            return self::FAILURE;
        }

        if ($user->is_admin) {
            $this->info('This user is already a Pension Guard administrator.');

            return self::SUCCESS;
        }

        if (! $this->confirm(
            "Promote {$user->username} ({$user->email}) to Pension Guard administrator?"
        )) {
            $this->info('No changes made.');

            return self::SUCCESS;
        }

        $user->forceFill(['is_admin' => true])->save();

        $this->info("{$user->username} is now a Pension Guard administrator.");

        return self::SUCCESS;
    }
}
