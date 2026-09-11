<?php

namespace App\Http\Controllers;

use App\Models\AssessmentAttempt;
use Illuminate\Http\Request;

class ManagerTeamReadinessController extends Controller
{
    public function index(Request $request)
    {
       /* dd([
    'user_id' => $request->user()?->id,
    'name' => $request->user()?->name,
    'role' => $request->user()?->role?->slug,
    'verified' => $request->user()?->hasVerifiedEmail(),
    'organisation_id' => $request->user()?->organisation_id,
    'department_id' => $request->user()?->department_id,
    ]);*/

    $user = $request->user();

        abort_unless($user->hasRole('manager'), 403);
        abort_unless($user->organisation_id && $user->department_id, 403);

        $department = $user->department()
            ->with([
                'organisation',
                'users.role',
            ])
            ->firstOrFail();

        // Only employees belonging to this manager's department.
        $employees = $department->users
            ->filter(fn ($member) => $member->hasRole('employee'));

        $employeeIds = $employees->pluck('id');

        /*
         * Get completed/expired attempts for this department's employees.
         * Latest attempt per employee is used for the dashboard.
         */
        $attempts = AssessmentAttempt::query()
            ->whereIn('user_id', $employeeIds)
            ->whereIn('status', ['completed', 'expired'])
            ->with([
                'user',
                'assessment',
            ])
            ->orderByDesc('completed_at')
            ->get();

        $latestAttempts = $attempts
            ->groupBy('user_id')
            ->map(fn ($employeeAttempts) => $employeeAttempts->first());

        $employeeReadiness = $employees->map(function ($employee) use ($latestAttempts) {
            $attempt = $latestAttempts->get($employee->id);

            return [
                'user' => $employee,
                'attempt' => $attempt,
                'score' => $attempt?->score_percentage,
                'risk_level' => $attempt?->risk_level,
            ];
        });

        $totalEmployees = $employeeReadiness->count();

        $assessedEmployees = $employeeReadiness
            ->filter(fn ($employee) => $employee['attempt'] !== null)
            ->count();

        $notAssessedEmployees = $totalEmployees - $assessedEmployees;

        $averageScore = $employeeReadiness
            ->whereNotNull('score')
            ->avg('score');

        $highRisk = $employeeReadiness
            ->where('risk_level', 'High')
            ->count();

        $mediumRisk = $employeeReadiness
            ->where('risk_level', 'Medium')
            ->count();

        $lowRisk = $employeeReadiness
            ->where('risk_level', 'Low')
            ->count();

        $assessmentCoverage = $totalEmployees > 0
            ? round(($assessedEmployees / $totalEmployees) * 100)
            : 0;

        return view('manager.team-readiness', compact(
            'department',
            'employeeReadiness',
            'totalEmployees',
            'assessedEmployees',
            'notAssessedEmployees',
            'averageScore',
            'highRisk',
            'mediumRisk',
            'lowRisk',
            'assessmentCoverage',
        ));
    }
}
