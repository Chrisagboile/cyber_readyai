<?php

namespace App\Modules\PensionAccountGuard\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\PensionAccountGuard\Models\PensionGuardAuditLog;
use Illuminate\Http\Request;

class PensionGuardAuditController extends Controller
{
    public function index(Request $request)
    {
        $query = PensionGuardAuditLog::query()
            ->orderByDesc('created_at')
            ->orderByDesc('id');

        if ($request->filled('action')) {
            $query->where(
                'action',
                'like',
                '%' . $request->string('action')->toString() . '%'
            );
        }

        if ($request->filled('actor_id')) {
            $query->where('actor_id', $request->integer('actor_id'));
        }

        $logs = $query->paginate(25)->withQueryString();

        return view('pension-guard.admin.audit.index', [
            'logs' => $logs,
            'actionFilter' => $request->string('action')->toString(),
            'actorFilter' => $request->string('actor_id')->toString(),
        ]);
    }
}
