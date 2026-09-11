<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentAttempt;
use App\Models\Department;
use App\Models\LearningPlan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrganisationReportController extends Controller
{
    /**
     * Confirm the authenticated user is an Organisation Admin
     * and return the organisation ID.
     */
    private function organisationId(Request $request): int
    {
        $user = $request->user();

        abort_unless($user, 403);

        abort_unless(
            $user->hasRole('organisation-admin'),
            403
        );

        abort_unless(
            $user->organisation_id,
            403
        );

        return (int) $user->organisation_id;
    }

    public function index(Request $request)
    {
        $organisationId = $this->organisationId($request);

        /*
        |--------------------------------------------------------------------------
        | Filter inputs
        |--------------------------------------------------------------------------
        */

        $range = $request->input('range', '30');

        $allowedRanges = [
            '7',
            '30',
            '90',
            'year',
            'custom',
        ];

        if (! in_array($range, $allowedRanges, true)) {
            $range = '30';
        }

        $fromInput = $request->input('from');
        $toInput = $request->input('to');

        /*
        |--------------------------------------------------------------------------
        | Determine reporting date range
        |--------------------------------------------------------------------------
        */

        $to = now()->endOfDay();

        if ($range === '7') {
            $from = now()->subDays(6)->startOfDay();
        } elseif ($range === '30') {
            $from = now()->subDays(29)->startOfDay();
        } elseif ($range === '90') {
            $from = now()->subDays(89)->startOfDay();
        } elseif ($range === 'year') {
            $from = now()->startOfYear()->startOfDay();
        } else {
            $from = $fromInput
                ? Carbon::parse($fromInput)->startOfDay()
                : now()->subDays(29)->startOfDay();

            $to = $toInput
                ? Carbon::parse($toInput)->endOfDay()
                : now()->endOfDay();
        }

        /*
        |--------------------------------------------------------------------------
        | Additional filters
        |--------------------------------------------------------------------------
        */

        $departmentId = $request->input('department_id');
        $assessmentId = $request->input('assessment_id');
        $employeeId = $request->input('employee_id');
        $riskLevel = strtolower(
            (string) $request->input('risk_level')
        );
        $assessmentStatus = $request->input('assessment_status');
        $learningStatus = $request->input('learning_status');
        $learningProgress = $request->input('learning_progress');

        /*
        |--------------------------------------------------------------------------
        | Organisation structure
        |--------------------------------------------------------------------------
        */

        $departments = Department::query()
            ->where('organisation_id', $organisationId)
            ->orderBy('name')
            ->get();

        $employees = User::query()
            ->where('organisation_id', $organisationId)
            ->whereHas(
                'role',
                fn ($query) => $query->where('slug', 'employee')
            )
            ->with('department')
            ->orderBy('name')
            ->get();

        $assessments = Assessment::query()
            ->where('organisation_id', $organisationId)
            ->with('department')
            ->orderByDesc('created_at')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Assessment attempts
        |--------------------------------------------------------------------------
        */

        $attemptsQuery = AssessmentAttempt::query()
            ->whereIn(
                'status',
                ['completed', 'expired']
            )
            ->whereBetween(
                'completed_at',
                [$from, $to]
            )
            ->whereHas(
                'assessment',
                function ($query) use (
                    $organisationId,
                    $departmentId,
                    $assessmentId
                ) {
                    $query->where(
                        'organisation_id',
                        $organisationId
                    );

                    if ($departmentId) {
                        $query->where(
                            'department_id',
                            $departmentId
                        );
                    }

                    if ($assessmentId) {
                        $query->whereKey($assessmentId);
                    }
                }
            )
            ->whereHas(
                'user',
                function ($query) use ($organisationId, $employeeId) {
                    $query
                        ->where(
                            'organisation_id',
                            $organisationId
                        )
                        ->whereHas(
                            'role',
                            fn ($roleQuery) =>
                                $roleQuery->where(
                                    'slug',
                                    'employee'
                                )
                        );

                    if ($employeeId) {
                        $query->whereKey($employeeId);
                    }
                }
            )
            ->with([
                'user.department',
                'assessment.department',
            ])
            ->orderByDesc('completed_at');

        /*
        |--------------------------------------------------------------------------
        | Assessment status filter
        |--------------------------------------------------------------------------
        */

        if (
            $assessmentStatus
            && in_array(
                $assessmentStatus,
                ['completed', 'expired'],
                true
            )
        ) {
            $attemptsQuery->where(
                'status',
                $assessmentStatus
            );
        }

        $attempts = $attemptsQuery->get();

        /*
        |--------------------------------------------------------------------------
        | Learning plans
        |--------------------------------------------------------------------------
        */

        $employeeIds = $employees->pluck('id');

        $learningPlans = LearningPlan::query()
            ->whereIn('user_id', $employeeIds)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Build learning-plan summaries per employee
        |--------------------------------------------------------------------------
        */

        $learningSummaries = $learningPlans
            ->groupBy('user_id')
            ->map(function ($plans) {

                $total = $plans->count();

                $completed = $plans
                    ->where('status', 'completed')
                    ->count();

                $inProgress = $plans
                    ->where('status', 'in_progress')
                    ->count();

                $notStarted = $plans
                    ->where('status', 'not_started')
                    ->count();

                $progress = $total > 0
                    ? round(
                        (float) $plans->avg(
                            'progress_percentage'
                        ),
                        1
                    )
                    : 0;

                /*
                 * Overall learning state:
                 *
                 * completed  = all plans completed
                 * in_progress = one or more active plans
                 * not_started = plans exist but none started
                 */
                if ($total === 0) {
                    $status = 'none';
                } elseif ($completed === $total) {
                    $status = 'completed';
                } elseif ($inProgress > 0) {
                    $status = 'in_progress';
                } else {
                    $status = 'not_started';
                }

                return [
                    'total' => $total,
                    'completed' => $completed,
                    'in_progress' => $inProgress,
                    'not_started' => $notStarted,
                    'progress' => $progress,
                    'status' => $status,
                ];
            });

        /*
        |--------------------------------------------------------------------------
        | Apply learning-plan filters
        |--------------------------------------------------------------------------
        */

        if ($learningStatus) {
            $attempts = $attempts
                ->filter(function ($attempt) use (
                    $learningSummaries,
                    $learningStatus
                ) {
                    $summary = $learningSummaries->get(
                        $attempt->user_id
                    );

                    $status = $summary['status'] ?? 'none';

                    return $status === $learningStatus;
                })
                ->values();
        }

        if ($learningProgress) {
            $attempts = $attempts
                ->filter(function ($attempt) use (
                    $learningSummaries,
                    $learningProgress
                ) {
                    $summary = $learningSummaries->get(
                        $attempt->user_id
                    );

                    $progress = $summary['progress'] ?? 0;

                    return match ($learningProgress) {
                        '0' => $progress == 0,
                        '1-49' => $progress >= 1 && $progress <= 49,
                        '50-99' => $progress >= 50 && $progress <= 99,
                        '100' => $progress >= 100,
                        default => true,
                    };
                })
                ->values();
        }

        /*
        |--------------------------------------------------------------------------
        | Risk filter
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $riskLevel,
                ['high', 'medium', 'low'],
                true
            )
        ) {
            $attempts = $attempts
                ->filter(
                    fn ($attempt) =>
                        strtolower(
                            (string) $attempt->risk_level
                        ) === $riskLevel
                )
                ->values();
        }

        /*
        |--------------------------------------------------------------------------
        | Latest filtered attempt per employee
        |--------------------------------------------------------------------------
        */

        $latestAttempts = $attempts
            ->groupBy('user_id')
            ->map(
                fn ($employeeAttempts) =>
                    $employeeAttempts->first()
            );

        /*
        |--------------------------------------------------------------------------
        | Summary KPIs
        |--------------------------------------------------------------------------
        */

        $totalAttempts = $attempts->count();

        $uniqueEmployees = $attempts
            ->pluck('user_id')
            ->unique()
            ->count();

        $averageScore = $attempts->isNotEmpty()
            ? round(
                (float) $attempts->avg(
                    'score_percentage'
                ),
                1
            )
            : 0;

        $highRisk = $attempts
            ->filter(
                fn ($attempt) =>
                    strtolower(
                        (string) $attempt->risk_level
                    ) === 'high'
            )
            ->count();

        $mediumRisk = $attempts
            ->filter(
                fn ($attempt) =>
                    strtolower(
                        (string) $attempt->risk_level
                    ) === 'medium'
            )
            ->count();

        $lowRisk = $attempts
            ->filter(
                fn ($attempt) =>
                    strtolower(
                        (string) $attempt->risk_level
                    ) === 'low'
            )
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Learning summary for filtered employees
        |--------------------------------------------------------------------------
        */

        $filteredEmployeeIds = $attempts
            ->pluck('user_id')
            ->unique();

        $filteredLearningPlans = $learningPlans
            ->whereIn(
                'user_id',
                $filteredEmployeeIds
            );

        $totalLearningPlans =
            $filteredLearningPlans->count();

        $completedLearningPlans =
            $filteredLearningPlans
                ->where('status', 'completed')
                ->count();

        $inProgressLearningPlans =
            $filteredLearningPlans
                ->where('status', 'in_progress')
                ->count();

        $notStartedLearningPlans =
            $filteredLearningPlans
                ->where('status', 'not_started')
                ->count();

        $averageLearningProgress =
            $totalLearningPlans > 0
                ? round(
                    (float) $filteredLearningPlans->avg(
                        'progress_percentage'
                    ),
                    1
                )
                : 0;

        /*
        |--------------------------------------------------------------------------
        | Employee report
        |--------------------------------------------------------------------------
        */

        $employeeReports = $filteredEmployeeIds
            ->map(
                function ($employeeId) use (
                    $employees,
                    $latestAttempts,
                    $learningSummaries
                ) {
                    $employee = $employees->firstWhere(
                        'id',
                        $employeeId
                    );

                    $attempt = $latestAttempts->get(
                        $employeeId
                    );

                    $learning = $learningSummaries->get(
                        $employeeId
                    );

                    return [
                        'employee' => $employee,
                        'attempt' => $attempt,
                        'score' => $attempt
                            ? (float) $attempt->score_percentage
                            : null,
                        'risk' => $attempt?->risk_level,
                        'learning' => $learning ?? [
                            'total' => 0,
                            'completed' => 0,
                            'in_progress' => 0,
                            'not_started' => 0,
                            'progress' => 0,
                            'status' => 'none',
                        ],
                    ];
                }
            )
            ->filter(
                fn ($report) =>
                    $report['employee'] !== null
            )
            ->sortBy(
                fn ($report) =>
                    strtolower(
                        $report['employee']->name
                    )
            )
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Department comparison
        |--------------------------------------------------------------------------
        */

        $departmentReports = $departments
            ->map(function ($department) use (
                $employeeReports
            ) {
                $reports = $employeeReports
                    ->filter(
                        fn ($report) =>
                            (int) $report['employee']->department_id
                            === (int) $department->id
                    );

                $employees = $reports->count();

                $assessed = $reports
                    ->filter(
                        fn ($report) =>
                            $report['attempt'] !== null
                    )
                    ->count();

                $averageScore =
                    $reports
                        ->whereNotNull('score')
                        ->isNotEmpty()
                    ? round(
                        (float) $reports
                            ->whereNotNull('score')
                            ->avg('score'),
                        1
                    )
                    : 0;

                $highRisk = $reports
                    ->filter(
                        fn ($report) =>
                            strtolower(
                                (string) $report['risk']
                            ) === 'high'
                    )
                    ->count();

                $learningProgress =
                    $reports->isNotEmpty()
                    ? round(
                        (float) $reports->avg(
                            fn ($report) =>
                                $report['learning']['progress']
                        ),
                        1
                    )
                    : 0;

                return [
                    'department' => $department,
                    'employees' => $employees,
                    'assessed' => $assessed,
                    'coverage' => $employees > 0
                        ? round(
                            ($assessed / $employees) * 100,
                            1
                        )
                        : 0,
                    'average_score' => $averageScore,
                    'high_risk' => $highRisk,
                    'learning_progress' => $learningProgress,
                ];
            });

        /*
        |--------------------------------------------------------------------------
        | Overall risk
        |--------------------------------------------------------------------------
        */

        $overallRisk = 'Not Assessed';

        if ($highRisk > 0) {
            $overallRisk = 'High';
        } elseif ($mediumRisk > 0) {
            $overallRisk = 'Medium';
        } elseif ($lowRisk > 0) {
            $overallRisk = 'Low';
        }

        /*
        |--------------------------------------------------------------------------
        | Display filter label
        |--------------------------------------------------------------------------
        */

        $rangeLabel = match ($range) {
            '7' => 'Last 7 days',
            '30' => 'Last 30 days',
            '90' => 'Last 90 days',
            'year' => 'This year',
            'custom' => 'Custom range',
            default => 'Last 30 days',
        };

        return view(
            'organisation.reports.index',
            compact(
                'departments',
                'employees',
                'assessments',
                'attempts',
                'employeeReports',
                'departmentReports',
                'learningSummaries',

                'totalAttempts',
                'uniqueEmployees',
                'averageScore',

                'highRisk',
                'mediumRisk',
                'lowRisk',
                'overallRisk',

                'totalLearningPlans',
                'completedLearningPlans',
                'inProgressLearningPlans',
                'notStartedLearningPlans',
                'averageLearningProgress',

                'range',
                'rangeLabel',
                'from',
                'to',

                'departmentId',
                'assessmentId',
                'employeeId',
                'riskLevel',
                'assessmentStatus',
                'learningStatus',
                'learningProgress',
            )
        );
    }
}
