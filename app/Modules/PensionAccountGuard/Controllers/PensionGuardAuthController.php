<?php

namespace App\Modules\PensionAccountGuard\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\PensionAccountGuard\Models\PensionGuardUser;
use App\Modules\PensionAccountGuard\Services\PensionGuardReminderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Throwable;

class PensionGuardAuthController extends Controller
{
    public function showLogin()
    {
        return view('pension-guard.auth.login');
    }

    public function login(
        Request $request,
        PensionGuardReminderService $reminderService
    ) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::guard('pension_guard')->attempt($credentials)) {
            $request->session()->regenerate();

            // Reminders must not prevent a successful login.
            try {
                $user = Auth::guard('pension_guard')->user();

                if ($user instanceof PensionGuardUser) {
                    $reminderService->remindAboutIncompleteChecks($user);
                }
            } catch (Throwable $exception) {
                report($exception);
            }

            return redirect()->intended(
                route('pension-guard.dashboard')
            );
        }

        return back()
            ->withErrors([
                'email' => 'The email address or password is incorrect.',
            ])
            ->onlyInput('email');
    }

    public function showRegister()
    {
        return view('pension-guard.auth.register');
    }

    public function register(
        Request $request,
        PensionGuardReminderService $reminderService
    ) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('pension_guard_users', 'username'),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('pension_guard_users', 'email'),
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        $user = PensionGuardUser::create($validated);

        Auth::guard('pension_guard')->login($user);

        $request->session()->regenerate();

        // A newly registered user may have no pension accounts yet.
        // The service will return without creating a reminder if
        // there are no incomplete security checks.
        try {
            $reminderService->remindAboutIncompleteChecks($user);
        } catch (Throwable $exception) {
            report($exception);
        }

        return redirect()->route('pension-guard.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('pension_guard')->logout();

        // Preserve other modules' session data.
        $request->session()->regenerate();

        return redirect()->route('pension-guard.dashboard');
    }
}
