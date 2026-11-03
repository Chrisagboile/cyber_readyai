<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PensionGuardAdminMiddleware
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        if (! Auth::guard('pension_guard')->check()) {
            abort(403, 'Pension Guard administrator access required.');
        }

        $user = Auth::guard('pension_guard')->user();

        if (! $user || ! $user->is_admin) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
