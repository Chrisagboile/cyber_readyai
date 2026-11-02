<?php

namespace App\Modules\PensionAccountGuard\Controllers;
use App\Http\Controllers\Controller;
use App\Modules\PensionAccountGuard\Models\PensionGuardOfficialSource;

class PensionGuardSourceController extends Controller
{
    /**
     * Display active official guidance sources.
     */
    public function index()
    {
        $sources = PensionGuardOfficialSource::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $today = now()->toDateString();

        return view('pension-guard.sources.index', [
            'sources' => $sources,
            'today' => $today,
        ]);
    }
}
