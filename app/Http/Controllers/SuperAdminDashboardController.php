<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentAttempt;
use App\Models\LearningPlan;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Http\Request;

class SuperAdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        abort_unless(
            $user && $user->hasRole('super-admin'),
            403
        );

        /*
        |--------------------------------------------------------------------------
        | Platform-wide users
        |--------------------------------------------------------------------------
        */

        $totalUsers = User::count();

        $activeUsers = User::query()
            ->where(function ($query) {
                $query
                    ->whereNull('status')
                    ->orWhere('status', 'active');
            })
            ->count();

        $organisationAdminCount = User::whereHas('role', function ($query) {
            $query->where('slug', 'organisation-admin');
        })->count();

        $managerCount = User::whereHas('role', function ($query) {
            $query->where('slug', 'manager');
        })->count();

        $employeeCount = User::whereHas('role', function ($query) {
            $query->where('slug', 'employee');
        })->count();

        /*
        |--------------------------------------------------------------------------
        | Organisations
        |--------------------------------------------------------------------------
        */

        $organisations = Organisation::query()
            ->withCount([
                'users as total_users_count',
                'users as employee_count' => function ($query) {
                    $query->whereHas('role', function ($roleQuery) {
                        $roleQuery->where('slug', 'employee');
                    });
                },
                'users as manager_count' => function ($query) {
                    $query->whereHas('role', function ($roleQuery) {
                        $roleQuery->where('slug', 'manager');
                    });
                },
                'assessments',
            ])
            ->orderBy('name')
            ->get();

        $totalOrganisations = $organisations->count();

        $activeOrganisations = $organisations
            ->filter(function ($organisation) {
                return strtolower((string) $organisation->status) === 'active';
            })
            ->count();

        $inactiveOrganisations = max(
            0,
            $totalOrganisations - $activeOrganisations
        );

        /*
        |--------------------------------------------------------------------------
        | Assessments
        |--------------------------------------------------------------------------
        */

        $totalAssessments = Assessment::count();

        $completedAttempts = AssessmentAttempt::query()
            ->where('status', 'completed')
            ->count();

        $expiredAttempts = AssessmentAttempt::query()
            ->where('status', 'expired')
            ->count();

        $completedOrExpiredAttempts = AssessmentAttempt::query()
            ->whereIn('status', ['completed', 'expired'])
            ->get();

        $averageScore = $completedOrExpiredAttempts->count() > 0
            ? round(
                $completedOrExpiredAttempts->avg(function ($attempt) {
                    return (float) $attempt->score_percentage;
                }),
                1
            )
            : 0;

        $highRisk = $completedOrExpiredAttempts
            ->filter(function ($attempt) {
                return strtolower((string) $attempt->risk_level) === 'high';
            })
            ->count();

        $mediumRisk = $completedOrExpiredAttempts
            ->filter(function ($attempt) {
                return strtolower((string) $attempt->risk_level) === 'medium';
            })
            ->count();

        $lowRisk = $completedOrExpiredAttempts
            ->filter(function ($attempt) {
                return strtolower((string) $attempt->risk_level) === 'low';
            })
            ->count();

        $assessedEmployees = $completedOrExpiredAttempts
            ->pluck('user_id')
            ->unique()
            ->count();

        $assessmentCoverage = $employeeCount > 0
            ? round(
                ($assessedEmployees / $employeeCount) * 100,
                1
            )
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Learning Plans
        |--------------------------------------------------------------------------
        */

        $learningPlans = LearningPlan::query()
            ->with('user')
            ->get();

        $totalLearningPlans = $learningPlans->count();

        $completedLearningPlans = $learningPlans
            ->where('status', 'completed')
            ->count();

        $inProgressLearningPlans = $learningPlans
            ->where('status', 'in_progress')
            ->count();

        $notStartedLearningPlans = $learningPlans
            ->where('status', 'not_started')
            ->count();

        $averageLearningProgress = $totalLearningPlans > 0
            ? round(
                $learningPlans->avg(function ($plan) {
                    return (float) $plan->progress_percentage;
                }),
                1
            )
            : 0;

        $overdueLearningPlans = $learningPlans
            ->filter(function ($plan) {
                return $plan->due_date
                    && $plan->status !== 'completed'
                    && $plan->due_date->isPast();
            })
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Organisation-level reporting
        |--------------------------------------------------------------------------
        */

        $organisationReports = $organisations->map(
            function ($organisation) {
                $organisationAttempts = AssessmentAttempt::query()
                    ->whereIn('status', ['completed', 'expired'])
                    ->whereHas('assessment', function ($query) use ($organisation) {
                        $query->where('organisation_id', $organisation->id);
                    })
                    ->get();

                $assessedEmployees = $organisationAttempts
                    ->pluck('user_id')
                    ->unique()
                    ->count();

                $employees = (int) $organisation->employee_count;

                $averageScore = $organisationAttempts->count() > 0
                    ? round(
                        $organisationAttempts->avg(function ($attempt) {
                            return (float) $attempt->score_percentage;
                        }),
                        1
                    )
                    : 0;

                $highRisk = $organisationAttempts
                    ->filter(function ($attempt) {
                        return strtolower((string) $attempt->risk_level) === 'high';
                    })
                    ->pluck('user_id')
                    ->unique()
                    ->count();

                $coverage = $employees > 0
                    ? round(
                        ($assessedEmployees / $employees) * 100,
                        1
                    )
                    : 0;

                return (object) [
                    'organisation' => $organisation,
                    'employees' => $employees,
                    'managers' => (int) $organisation->manager_count,
                    'assessments' => (int) $organisation->assessments_count,
                    'assessed' => $assessedEmployees,
                    'coverage' => $coverage,
                    'average_score' => $averageScore,
                    'high_risk' => $highRisk,
                    'attempts' => $organisationAttempts->count(),
                ];
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Recent assessment activity
        |--------------------------------------------------------------------------
        */

        $recentAttempts = AssessmentAttempt::query()
            ->with([
                'user',
                'assessment',
            ])
            ->whereIn('status', ['completed', 'expired'])
            ->orderByDesc('completed_at')
            ->limit(8)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Recent organisations
        |--------------------------------------------------------------------------
        */

        $recentOrganisations = Organisation::query()
            ->withCount('users')
            ->latest('created_at')
            ->limit(6)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Overall platform risk
        |--------------------------------------------------------------------------
        */

        if ($highRisk > 0) {
            $overallRisk = 'High';
        } elseif ($mediumRisk > 0) {
            $overallRisk = 'Medium';
        } elseif ($lowRisk > 0) {
            $overallRisk = 'Low';
        } else {
            $overallRisk = 'Not Assessed';
        }

        return view('dashboard.super-admin', compact(
            'organisations',
            'organisationReports',
            'totalOrganisations',
            'activeOrganisations',
            'inactiveOrganisations',
            'totalUsers',
            'activeUsers',
            'organisationAdminCount',
            'managerCount',
            'employeeCount',
            'totalAssessments',
            'completedAttempts',
            'expiredAttempts',
            'averageScore',
            'highRisk',
            'mediumRisk',
            'lowRisk',
            'assessedEmployees',
            'assessmentCoverage',
            'totalLearningPlans',
            'completedLearningPlans',
            'inProgressLearningPlans',
            'notStartedLearningPlans',
            'averageLearningProgress',
            'overdueLearningPlans',
            'recentAttempts',
            'recentOrganisations',
            'overallRisk'
        ));
    }
}
