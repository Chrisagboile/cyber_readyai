<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentAttempt;
use App\Models\LearningPlan;
use App\Models\Organisation;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SuperAdminAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(
            $request->user()->hasRole('super-admin'),
            403
        );

        $period = $request->input('period', '30');

        if (! in_array(
            $period,
            ['7', '30', '90', '365', 'all'],
            true
        )) {
            $period = '30';
        }

        $periodStart = match ($period) {
            '7' => now()->subDays(6)->startOfDay(),
            '30' => now()->subDays(29)->startOfDay(),
            '90' => now()->subDays(89)->startOfDay(),
            '365' => now()->subDays(364)->startOfDay(),
            'all' => null,
        };

        /*
        |--------------------------------------------------------------------------
        | Platform totals
        |--------------------------------------------------------------------------
        */

        $totalOrganisations = Organisation::count();

        $activeOrganisations = Organisation::where(
            'status',
            'active'
        )->count();

        $inactiveOrganisations = Organisation::where(
            'status',
            'inactive'
        )->count();

        $totalUsers = User::count();

        $activeUsers = User::where(
            'status',
            'active'
        )->count();

        $totalAssessments = Assessment::count();

        $totalAttempts = AssessmentAttempt::count();

        $completedAttempts = AssessmentAttempt::where(
            'status',
            'completed'
        )->count();

        $expiredAttempts = AssessmentAttempt::where(
            'status',
            'expired'
        )->count();

        $inProgressAttempts = AssessmentAttempt::where(
            'status',
            'in_progress'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Assessment performance
        |--------------------------------------------------------------------------
        */

        $scoredAttempts = AssessmentAttempt::whereNotNull(
            'score_percentage'
        );

        $averageScore = $scoredAttempts->avg(
            'score_percentage'
        );

        $lowRisk = AssessmentAttempt::where(
            'risk_level',
            'low'
        )->count();

        $mediumRisk = AssessmentAttempt::where(
            'risk_level',
            'medium'
        )->count();

        $highRisk = AssessmentAttempt::where(
            'risk_level',
            'high'
        )->count();

        $assessedUsers = AssessmentAttempt::whereNotNull(
            'user_id'
        )
            ->distinct('user_id')
            ->count('user_id');

        $assessmentCoverage = $totalUsers > 0
            ? round(($assessedUsers / $totalUsers) * 100, 1)
            : 0;


        $completionRate = $totalAttempts > 0
            ? round(
                ($completedAttempts / $totalAttempts) * 100,
                1
            )
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Learning plans
        |--------------------------------------------------------------------------
        */

        $totalLearningPlans = LearningPlan::count();

        $completedLearningPlans = LearningPlan::where(
            'status',
            'completed'
        )->count();

        $inProgressLearningPlans = LearningPlan::where(
            'status',
            'in_progress'
        )->count();

        $notStartedLearningPlans = LearningPlan::where(
            'status',
            'not_started'
        )->count();

        $averageLearningProgress = LearningPlan::avg(
            'progress_percentage'
        );

        $overdueLearningPlans = LearningPlan::whereNotNull(
            'due_date'
        )
            ->whereDate('due_date', '<', today())
            ->where('status', '!=', 'completed')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Subscription / commercial analytics
        |--------------------------------------------------------------------------
        */

        $activeSubscriptions = Subscription::where(
            'status',
            'active'
        )->count();

        $trialSubscriptions = Subscription::where(
            'status',
            'trial'
        )->count();

        $monthlyRecurringValue = Subscription::whereIn(
            'status',
            ['active', 'trial']
        )
            ->get()
            ->sum(function ($subscription) {
                if ($subscription->billing_interval === 'monthly') {
                    return (float) $subscription->price;
                }

                return (float) $subscription->price / 12;
            });

        $annualRecurringValue = Subscription::whereIn(
            'status',
            ['active', 'trial']
        )
            ->get()
            ->sum(function ($subscription) {
                if ($subscription->billing_interval === 'monthly') {
                    return (float) $subscription->price * 12;
                }

                return (float) $subscription->price;
            });


        /*
        |--------------------------------------------------------------------------
        | Period analytics
        |--------------------------------------------------------------------------
        */

        $periodAttemptsQuery = AssessmentAttempt::query();

        if ($periodStart) {
            $periodAttemptsQuery->where(
                'created_at',
                '>=',
                $periodStart
            );
        }

        $periodAttempts = $periodAttemptsQuery
            ->with('user.organisation')
            ->get();

        $periodAttemptCount = $periodAttempts->count();

        $periodCompletedCount = $periodAttempts
            ->where('status', 'completed')
            ->count();

        $periodAverageScore = $periodAttempts
            ->whereNotNull('score_percentage')
            ->avg('score_percentage');

        $periodHighRisk = $periodAttempts
            ->where('risk_level', 'high')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Daily activity
        |--------------------------------------------------------------------------
        */

        $dailyActivityQuery = AssessmentAttempt::query()
            ->selectRaw('DATE(created_at) as activity_date')
            ->selectRaw('COUNT(*) as total')
            ->groupByRaw('DATE(created_at)')
            ->orderBy('activity_date');

        if ($periodStart) {
            $dailyActivityQuery->where(
                'created_at',
                '>=',
                $periodStart
            );
        }

        $dailyActivity = $dailyActivityQuery
            ->get()
            ->map(function ($row) {
                return [
                    'date' => $row->activity_date,
                    'total' => (int) $row->total,
                ];
            });


        /*
        |--------------------------------------------------------------------------
        | Organisation performance
        |--------------------------------------------------------------------------
        */

        $organisationIds = Organisation::query()
            ->pluck('id');

        $organisationUsers = User::query()
            ->selectRaw('organisation_id, COUNT(*) as total')
            ->whereNotNull('organisation_id')
            ->whereIn(
                'organisation_id',
                $organisationIds
            )
            ->groupBy('organisation_id')
            ->pluck('total', 'organisation_id');

        $organisationAttempts = AssessmentAttempt::query()
            ->selectRaw('users.organisation_id as organisation_id')
            ->selectRaw('COUNT(assessment_attempts.id) as total_attempts')
            ->selectRaw(
                'AVG(assessment_attempts.score_percentage) as average_score'
            )
            ->selectRaw(
                "SUM(
                    CASE
                        WHEN assessment_attempts.risk_level = 'high'
                        THEN 1
                        ELSE 0
                    END
                ) as high_risk"
            )
            ->join(
                'users',
                'users.id',
                '=',
                'assessment_attempts.user_id'
            )
            ->whereNotNull('users.organisation_id')
            ->when(
                $periodStart,
                fn ($query) => $query->where(
                    'assessment_attempts.created_at',
                    '>=',
                    $periodStart
                )
            )
            ->groupBy('users.organisation_id')
            ->get()
            ->keyBy('organisation_id');


        $organisationAnalytics = Organisation::query()
            ->orderBy('name')
            ->get()
            ->map(function ($organisation) use (
                $organisationUsers,
                $organisationAttempts
            ) {
                $attemptStats = $organisationAttempts->get(
                    $organisation->id
                );

                return [
                    'organisation' => $organisation,
                    'users' => (int) (
                        $organisationUsers->get(
                            $organisation->id,
                            0
                        )
                    ),
                    'attempts' => (int) (
                        $attemptStats?->total_attempts ?? 0
                    ),
                    'average_score' => $attemptStats?->average_score !== null
                        ? round(
                            (float) $attemptStats->average_score,
                            1
                        )
                        : null,
                    'high_risk' => (int) (
                        $attemptStats?->high_risk ?? 0
                    ),
                ];
            })
            ->sortByDesc('attempts')
            ->values();


        /*
        |--------------------------------------------------------------------------
        | Risk percentage
        |--------------------------------------------------------------------------
        */

        $riskTotal = $lowRisk
            + $mediumRisk
            + $highRisk;

        $lowRiskPercentage = $riskTotal > 0
            ? round(($lowRisk / $riskTotal) * 100, 1)
            : 0;

        $mediumRiskPercentage = $riskTotal > 0
            ? round(($mediumRisk / $riskTotal) * 100, 1)
            : 0;

        $highRiskPercentage = $riskTotal > 0
            ? round(($highRisk / $riskTotal) * 100, 1)
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Recent activity
        |--------------------------------------------------------------------------
        */

        $recentAttempts = AssessmentAttempt::with([
            'user.organisation',
            'assessment',
        ])
            ->latest()
            ->limit(8)
            ->get();


        $recentOrganisations = Organisation::latest()
            ->limit(5)
            ->get();


        return view('analytics.index', compact(
            'period',
            'totalOrganisations',
            'activeOrganisations',
            'inactiveOrganisations',
            'totalUsers',
            'activeUsers',
            'totalAssessments',
            'totalAttempts',
            'completedAttempts',
            'expiredAttempts',
            'inProgressAttempts',
            'averageScore',
            'lowRisk',
            'mediumRisk',
            'highRisk',
            'assessedUsers',
            'assessmentCoverage',
            'completionRate',
            'totalLearningPlans',
            'completedLearningPlans',
            'inProgressLearningPlans',
            'notStartedLearningPlans',
            'averageLearningProgress',
            'overdueLearningPlans',
            'activeSubscriptions',
            'trialSubscriptions',
            'monthlyRecurringValue',
            'annualRecurringValue',
            'periodAttemptCount',
            'periodCompletedCount',
            'periodAverageScore',
            'periodHighRisk',
            'dailyActivity',
            'organisationAnalytics',
            'lowRiskPercentage',
            'mediumRiskPercentage',
            'highRiskPercentage',
            'recentAttempts',
            'recentOrganisations'
        ));
    }
}
