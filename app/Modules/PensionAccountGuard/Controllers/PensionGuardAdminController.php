<?php

namespace App\Modules\PensionAccountGuard\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\PensionAccountGuard\Models\PensionGuardAccount;
use App\Modules\PensionAccountGuard\Models\PensionGuardAssessment;
use App\Modules\PensionAccountGuard\Models\PensionGuardOfficialSource;
use App\Modules\PensionAccountGuard\Models\PensionGuardUser;

class PensionGuardAdminController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        $metrics = [
            'users' => PensionGuardUser::count(),
            'accounts' => PensionGuardAccount::count(),
            'assessments' => PensionGuardAssessment::count(),
            'active_sources' => PensionGuardOfficialSource::where(
                'is_active',
                true
            )->count(),
            'inactive_sources' => PensionGuardOfficialSource::where(
                'is_active',
                false
            )->count(),
            'overdue_reviews' => PensionGuardOfficialSource::where(
                'is_active',
                true
            )->whereNotNull('next_review_due')
                ->whereDate('next_review_due', '<', $today)
                ->count(),
            'unreviewed_sources' => PensionGuardOfficialSource::where(
                'is_active',
                true
            )->whereNull('last_reviewed_at')->count(),
        ];

        $upcomingReviews = PensionGuardOfficialSource::query()
            ->where('is_active', true)
            ->whereNotNull('next_review_due')
            ->orderBy('next_review_due')
            ->limit(5)
            ->get();

        return view('pension-guard.admin.index', [
            'metrics' => $metrics,
            'upcomingReviews' => $upcomingReviews,
            'today' => $today,
        ]);
    }
}
