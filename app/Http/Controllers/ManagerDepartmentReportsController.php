<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use Illuminate\Http\Request;

class ManagerDepartmentReportsController extends Controller
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

        $assessments = Assessment::query()
            ->where('organisation_id', $user->organisation_id)
            ->where('department_id', $user->department_id)
            ->with([
                'employee',
                'creator',
            ])
            ->withCount([
                'attempts',
            ])
            ->orderByDesc('created_at')
            ->paginate(15);

        $assessmentIds = $assessments->getCollection()
            ->pluck('id');

        $completedAttempts = \App\Models\AssessmentAttempt::query()
            ->whereIn('assessment_id', $assessmentIds)
            ->whereIn('status', ['completed', 'expired'])
            ->get();

        $totalAssessments = $assessments->total();

        $totalAttempts = $completedAttempts->count();

        $averageScore = $completedAttempts->avg(
            'score_percentage'
        );

        $highRisk = $completedAttempts
            ->where('risk_level', 'High')
            ->count();

        $mediumRisk = $completedAttempts
            ->where('risk_level', 'Medium')
            ->count();

        $lowRisk = $completedAttempts
            ->where('risk_level', 'Low')
            ->count();

        return view(
            'manager.department-reports',
            compact(
                'department',
                'assessments',
                'totalAssessments',
                'totalAttempts',
                'averageScore',
                'highRisk',
                'mediumRisk',
                'lowRisk',
            )
        );
    }
}
