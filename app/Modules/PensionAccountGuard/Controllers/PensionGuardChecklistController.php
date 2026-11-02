<?php

namespace App\Modules\PensionAccountGuard\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\PensionAccountGuard\Models\PensionGuardAccount;
use App\Modules\PensionAccountGuard\Models\PensionGuardSecurityCheck;
use App\Modules\PensionAccountGuard\Services\PensionGuardNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PensionGuardChecklistController extends Controller
{
    public function index(PensionGuardAccount $account)
    {
        $account = PensionGuardAccount::where(
            'user_id',
            Auth::guard('pension_guard')->id()
        )
            ->with('securityChecks')
            ->findOrFail($account->id);

        return view('pension-guard.checklist.index', compact('account'));
    }

    public function update(
        Request $request,
        PensionGuardAccount $account,
        PensionGuardSecurityCheck $check,
        PensionGuardNotificationService $notificationService
    ) {
        $account = PensionGuardAccount::where(
            'user_id',
            Auth::guard('pension_guard')->id()
        )->findOrFail($account->id);

        abort_unless(
            (int) $check->pension_guard_account_id === (int) $account->id,
            404
        );

        $validated = $request->validate([
            'is_complete' => ['required', 'boolean'],
        ]);

        $complete = (bool) $validated['is_complete'];

        DB::transaction(function () use (
            $check,
            $complete,
            $notificationService
        ) {
            // Lock and refresh the check to prevent duplicate notifications
            // when concurrent requests attempt to complete the same item.
            $lockedCheck = PensionGuardSecurityCheck::whereKey($check->id)
                ->lockForUpdate()
                ->firstOrFail();

            $wasComplete = (bool) $lockedCheck->is_complete;

            $lockedCheck->update([
                'is_complete' => $complete,
                'completed_at' => $complete ? now() : null,
            ]);

            // Notify only when changing from incomplete to complete.
            if ($complete && ! $wasComplete) {
                $user = Auth::guard('pension_guard')->user();

                $notificationService->send(
                    $user,
                    'Security checklist updated',
                    'You marked a security check as complete. Review your checklist to see which other actions may need attention.'
                );
            }
        });

        return redirect()
            ->route('pension-guard.checklist.index', $account)
            ->with('status', 'Checklist updated.');
    }
}
