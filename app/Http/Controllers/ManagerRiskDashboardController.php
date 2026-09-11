<?php

namespace App\Http\Controllers;
use App\Models\LearningPlan;
use App\Models\AssessmentAttempt;
use Illuminate\Http\Request;

class ManagerRiskDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        abort_unless(
            $user->hasRole('manager'),
            403
        );

        abort_unless(
            $user->organisation_id && $user->department_id,
            403
        );

        $department = $user->department()
            ->with('organisation')
            ->firstOrFail();

        /*
         * Get employees belonging to this manager's
         * organisation and department.
         */
        $employees = $department->users()
            ->where('organisation_id', $user->organisation_id)
            ->whereHas('role', function ($query) {
                $query->where('slug', 'employee');
            })
            ->orderBy('name')
            ->get();

        $employeeIds = $employees->pluck('id');

        /*
         * Get completed/expired attempts belonging to
         * this department's employees and assessments.
         */
        $attempts = AssessmentAttempt::query()
            ->whereIn('user_id', $employeeIds)
            ->whereIn('status', ['completed', 'expired'])
            ->whereHas('assessment', function ($query) use ($user) {
                $query
                    ->where('organisation_id', $user->organisation_id)
                    ->where('department_id', $user->department_id);
            })
            ->with([
                'user',
                'assessment',
            ])
            ->orderByDesc('completed_at')
            ->get();

        /*
         * Use the latest completed/expired attempt
         * for each employee.
         */
        $latestAttempts = $attempts
            ->groupBy('user_id')
            ->map(function ($employeeAttempts) {
                return $employeeAttempts->first();
            });
/*
        $employeeRisk = $employees->map(function ($employee) use (
            $latestAttempts
        ) {
            $attempt = $latestAttempts->get($employee->id);

            return [
                'user' => $employee,
                'attempt' => $attempt,
                'score' => $attempt?->score_percentage,
                'risk_level' => $attempt?->risk_level,
            ];
        }); */

    $learningPlanCounts =LearningPlan::query()
        ->whereIn('user_id', $employeeIds)
        ->selectRaw('
            user_id,
            COUNT(*) as total_plans,
            SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed_plans,
            SUM(CASE WHEN status = "in_progress" THEN 1 ELSE 0 END) as in_progress_plans,
            AVG(progress_percentage) as average_progress
        ')
        ->groupBy('user_id')
        ->get()
        ->keyBy('user_id');

    $employeeRisk = $employees->map(function ($employee) use (
        $latestAttempts,
        $learningPlanCounts
    ) {
        $attempt = $latestAttempts->get($employee->id);
        $plans = $learningPlanCounts->get($employee->id);

        return [
            'user' => $employee,
            'attempt' => $attempt,
            'score' => $attempt?->score_percentage,
            'risk_level' => $attempt?->risk_level,

            'learning_plan_count' => (int) ($plans?->total_plans ?? 0),
            'completed_plans' => (int) ($plans?->completed_plans ?? 0),
            'in_progress_plans' => (int) ($plans?->in_progress_plans ?? 0),
            'learning_progress' => $plans
                ? round((float) $plans->average_progress)
                : null,
        ];
    });
        $totalEmployees = $employeeRisk->count();

        $assessedEmployees = $employeeRisk
            ->whereNotNull('attempt')
            ->count();

        $notAssessedEmployees =
            $totalEmployees - $assessedEmployees;

        $averageScore = $employeeRisk
            ->whereNotNull('score')
            ->avg('score');

        $highRisk = $employeeRisk
            ->where('risk_level', 'High')
            ->count();

        $mediumRisk = $employeeRisk
            ->where('risk_level', 'Medium')
            ->count();

        $lowRisk = $employeeRisk
            ->where('risk_level', 'Low')
            ->count();

        $assessmentCoverage = $totalEmployees > 0
            ? round(
                ($assessedEmployees / $totalEmployees) * 100
            )
            : 0;

        /*
         * Determine the department's overall risk level.
         */

      /*
        if ($highRisk > 0) {
            $overallRisk = 'High';
        } elseif ($mediumRisk > 0) {
            $overallRisk = 'Medium';
        } elseif ($lowRisk > 0) {
            $overallRisk = 'Low';
        } else {
            $overallRisk = 'Not Assessed';
        }
*/
        $assessedCount = $assessedEmployees;

        if ($assessedCount === 0) {
            $overallRisk = 'Not Assessed';
        } else {
            $highPercentage = ($highRisk / $assessedCount) * 100;
            $mediumPercentage = ($mediumRisk / $assessedCount) * 100;

            if ($highPercentage >= 50) {
                $overallRisk = 'High';
            } elseif (($highPercentage + $mediumPercentage) >= 50) {
                $overallRisk = 'Medium';
            } else {
                $overallRisk = 'Low';
            }
        }
        return view(
            'manager.risk-dashboard',
            compact(
                'department',
                'employeeRisk',
                'totalEmployees',
                'assessedEmployees',
                'notAssessedEmployees',
                'averageScore',
                'highRisk',
                'mediumRisk',
                'lowRisk',
                'assessmentCoverage',
                'overallRisk',
            )
        );
    }
}
