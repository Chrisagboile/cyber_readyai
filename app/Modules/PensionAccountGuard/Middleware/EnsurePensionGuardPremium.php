<?php

namespace App\Modules\PensionAccountGuard\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePensionGuardPremium
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('pension_guard')->user();

        if (!$user) {
            return redirect()->route('pension-guard.login');
        }

        $hasPremiumAccess = $user->subscriptions()
            ->get()
            ->contains(function ($subscription) {
                return $subscription->hasPremiumAccess();
            });

        if (!$hasPremiumAccess) {
            return redirect()
                ->route('pension-guard.subscriptions.index')
                ->with('error', 'A Premium subscription is required to access this feature.');
        }

        return $next($request);
    }
}
