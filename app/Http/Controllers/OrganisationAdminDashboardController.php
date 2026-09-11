<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentAttempt;
use App\Models\Department;
use App\Models\LearningPlan;
use App\Models\User;
use Illuminate\Http\Request;

class OrganisationAdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        abort_unless($user->hasRole('organisation-admin'), 403);
        abort_unless($user->organisation_id, 403);

        $organisationId = $user->organisation_id;

        /*
        |--------------------------------------------------------------------------
        | Organisation
        |--------------------------------------------------------------------------
        */

        $organisation = $user->organisation()->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Departments
        |--------------------------------------------------------------------------
        */

        $departments = Department::query()
            ->where('organisation_id', $organisationId)
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */

        $organisationUsers = User::query()
            ->where('organisation_id', $organisationId)
            ->with(['role', 'department'])
            ->get();

        $employeeIds = $organisationUsers
            ->filter(fn ($member) => $member->hasRole('employee'))
            ->pluck('id')
            ->values();

        $managerCount = $organisationUsers
            ->filter(fn ($member) => $member->hasRole('manager'))
            ->count();

        $employeeCount = $employeeIds->count();

        /*
        |--------------------------------------------------------------------------
        | Assessments
        |--------------------------------------------------------------------------
        */

        $assessments = Assessment::query()
            ->where('organisation_id', $organisationId)
            ->with(['employee', 'creator'])
            ->withCount('attempts')
            ->latest()
            ->get();

        $assessmentIds = $assessments->pluck('id');

        /*
        |--------------------------------------------------------------------------
        | Assessment attempts
        |--------------------------------------------------------------------------
        */

        $attempts = AssessmentAttempt::query()
            ->whereIn('assessment_id', $assessmentIds)
            ->whereIn('status', ['completed', 'expired'])
            ->with(['user', 'assessment'])
            ->latest('completed_at')
            ->get();

        $completedAssessmentCount = $attempts
            ->filter(fn ($attempt) => $attempt->status === 'completed')
            ->count();

        $assessedEmployeeIds = $attempts
            ->pluck('user_id')
            ->unique()
            ->values();

        $assessmentCoverage = $employeeCount > 0
            ? round(($assessedEmployeeIds->count() / $employeeCount) * 100, 1)
            : 0;

        $averageScore = $attempts->count() > 0
            ? round((float) $attempts->avg('score_percentage'), 1)
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Latest attempt per employee
        |--------------------------------------------------------------------------
        |
        | We use the employee's latest completed/expired assessment for the
        | organisation-wide readiness picture, rather than counting an employee
        | multiple times.
        |
        */

        $latestAttempts = $attempts
            ->groupBy('user_id')
            ->map(fn ($employeeAttempts) => $employeeAttempts->first());

        $highRisk = $latestAttempts
            ->filter(fn ($attempt) => strtolower((string) $attempt->risk_level) === 'high')
            ->count();

        $mediumRisk = $latestAttempts
            ->filter(fn ($attempt) => strtolower((string) $attempt->risk_level) === 'medium')
            ->count();

        $lowRisk = $latestAttempts
            ->filter(fn ($attempt) => strtolower((string) $attempt->risk_level) === 'low')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Overall organisation risk
        |--------------------------------------------------------------------------
        */

        $overallRisk = 'Not Assessed';

        if ($latestAttempts->count() > 0) {
            if ($highRisk > 0) {
                $overallRisk = 'High';
            } elseif ($mediumRisk > 0) {
                $overallRisk = 'Medium';
            } else {
                $overallRisk = 'Low';
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Learning plans
        |--------------------------------------------------------------------------
        */

        $learningPlans = LearningPlan::query()
            ->whereIn('user_id', $employeeIds)
            ->get();

        $learningPlanCount = $learningPlans->count();

        $completedLearningPlans = $learningPlans
            ->where('status', 'completed')
            ->count();

        $inProgressLearningPlans = $learningPlans
            ->where('status', 'in_progress')
            ->count();

        $notStartedLearningPlans = $learningPlans
            ->where('status', 'not_started')
            ->count();

        $averageLearningProgress = $learningPlans->count() > 0
            ? round((float) $learningPlans->avg('progress_percentage'), 1)
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Department readiness
        |--------------------------------------------------------------------------
        */

        $departmentReadiness = $departments->map(function ($department) use (
            $organisationId,
            $employeeIds,
            $latestAttempts,
            $learningPlans
        ) {
            $departmentEmployees = User::query()
                ->where('organisation_id', $organisationId)
                ->where('department_id', $department->id)
                ->whereIn('id', $employeeIds)
                ->get();

            $departmentEmployeeIds = $departmentEmployees->pluck('id');

            $departmentAttempts = $latestAttempts
                ->filter(fn ($attempt) => $departmentEmployeeIds->contains($attempt->user_id));

            $departmentPlans = $learningPlans
                ->filter(fn ($plan) => $departmentEmployeeIds->contains($plan->user_id));

            $departmentAverageScore = $departmentAttempts->count() > 0
                ? round((float) $departmentAttempts->avg('score_percentage'), 1)
                : 0;

            $departmentCoverage = $departmentEmployees->count() > 0
                ? round(
                    ($departmentAttempts->pluck('user_id')->unique()->count()
                        / $departmentEmployees->count()) * 100,
                    1
                )
                : 0;

            $departmentLearningProgress = $departmentPlans->count() > 0
                ? round((float) $departmentPlans->avg('progress_percentage'), 1)
                : 0;

            $departmentHighRisk = $departmentAttempts
                ->filter(
                    fn ($attempt) =>
                        strtolower((string) $attempt->risk_level) === 'high'
                )
                ->count();

            return [
                'department' => $department,
                'employee_count' => $departmentEmployees->count(),
                'assessed_count' => $departmentAttempts->pluck('user_id')->unique()->count(),
                'coverage' => $departmentCoverage,
                'average_score' => $departmentAverageScore,
                'high_risk' => $departmentHighRisk,
                'learning_progress' => $departmentLearningProgress,
            ];
        });

        /*
        |--------------------------------------------------------------------------
        | Recent assessment activity
        |--------------------------------------------------------------------------
        */

        $recentAttempts = $attempts
            ->sortByDesc('completed_at')
            ->take(8)
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */
        return view('dashboard.organisation-admin', compact(
            'organisation',
            'departments',
            'organisationUsers',
            'employeeIds',
            'employeeCount',
            'managerCount',
            'assessmentIds',
            'assessments',
            'attempts',
            'latestAttempts',
            'completedAssessmentCount',
            'assessedEmployeeIds',
            'assessmentCoverage',
            'averageScore',
            'highRisk',
            'mediumRisk',
            'lowRisk',
            'overallRisk',
            'learningPlans',
            'learningPlanCount',
            'completedLearningPlans',
            'inProgressLearningPlans',
            'notStartedLearningPlans',
            'averageLearningProgress',
            'departmentReadiness',
            'recentAttempts',
        ));
    }
}
