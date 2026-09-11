<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentAttempt;
use App\Models\LearningPlan;
use Illuminate\Http\Request;

class ManagerDepartmentReportsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | Authorisation
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $user->hasRole('manager'),
            403
        );

        abort_unless(
            $user->organisation_id &&
            $user->department_id,
            403
        );


        /*
        |--------------------------------------------------------------------------
        | Department
        |--------------------------------------------------------------------------
        */

        $department = $user->department()
            ->with('organisation')
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Department Employees
        |--------------------------------------------------------------------------
        |
        | Load employees separately from paginated assessments so that
        | learning-plan reporting always has access to the correct
        | employee record.
        |
        */

        $employees = $department->users()
            ->where(
                'organisation_id',
                $user->organisation_id
            )
            ->whereHas('role', function ($query) {
                $query->where('slug', 'employee');
            })
            ->orderBy('name')
            ->get()
            ->keyBy('id');


        /*
        |--------------------------------------------------------------------------
        | Department Assessments
        |--------------------------------------------------------------------------
        */

        $assessments = Assessment::query()
            ->where(
                'organisation_id',
                $user->organisation_id
            )
            ->where(
                'department_id',
                $user->department_id
            )
            ->with([
                'employee',
                'creator',
            ])
            ->withCount('attempts')
            ->orderByDesc('created_at')
            ->paginate(15);


        /*
        |--------------------------------------------------------------------------
        | Assessment Attempts
        |--------------------------------------------------------------------------
        */

        $assessmentIds = $assessments
            ->getCollection()
            ->pluck('id');


        $attempts = AssessmentAttempt::query()
            ->whereIn(
                'assessment_id',
                $assessmentIds
            )
            ->whereIn(
                'status',
                [
                    'completed',
                    'expired',
                ]
            )
            ->with([
                'user',
                'assessment',
            ])
            ->orderByDesc('completed_at')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Assessment Statistics
        |--------------------------------------------------------------------------
        */

        $totalAssessments = $assessments->total();

        $totalAttempts = $attempts->count();

        $averageScore = $attempts->avg(
            'score_percentage'
        );

        $highRisk = $attempts
            ->where('risk_level', 'High')
            ->count();

        $mediumRisk = $attempts
            ->where('risk_level', 'Medium')
            ->count();

        $lowRisk = $attempts
            ->where('risk_level', 'Low')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Learning Plan Statistics
        |--------------------------------------------------------------------------
        */

        $learningPlans = LearningPlan::query()
            ->whereIn(
                'user_id',
                $employees->keys()
            )
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
                (float) $learningPlans
                    ->avg('progress_percentage')
            )
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Assessment-Level Reports
        |--------------------------------------------------------------------------
        */

        $assessmentReports = $assessments
            ->getCollection()
            ->map(function ($assessment) use ($attempts) {

                $assessmentAttempts = $attempts
                    ->where(
                        'assessment_id',
                        $assessment->id
                    );

                $assessmentAverage = $assessmentAttempts->avg(
                    'score_percentage'
                );

                $assessmentHighRisk = $assessmentAttempts
                    ->where(
                        'risk_level',
                        'High'
                    )
                    ->count();

                $assessmentMediumRisk = $assessmentAttempts
                    ->where(
                        'risk_level',
                        'Medium'
                    )
                    ->count();

                $assessmentLowRisk = $assessmentAttempts
                    ->where(
                        'risk_level',
                        'Low'
                    )
                    ->count();

                return [
                    'assessment' => $assessment,

                    'attempt_count' =>
                        $assessmentAttempts->count(),

                    'average_score' =>
                        $assessmentAverage !== null
                            ? round(
                                (float) $assessmentAverage,
                                1
                            )
                            : null,

                    'high_risk' =>
                        $assessmentHighRisk,

                    'medium_risk' =>
                        $assessmentMediumRisk,

                    'low_risk' =>
                        $assessmentLowRisk,
                ];
            });


        /*
        |--------------------------------------------------------------------------
        | Employee Learning Progress
        |--------------------------------------------------------------------------
        */

        $employeeLearningProgress = LearningPlan::query()
            ->whereIn(
                'user_id',
                $employees->keys()
            )
            ->selectRaw("
                user_id,
                COUNT(*) as total_plans,
                SUM(
                    CASE
                        WHEN status = 'completed'
                        THEN 1
                        ELSE 0
                    END
                ) as completed_plans,
                SUM(
                    CASE
                        WHEN status = 'in_progress'
                        THEN 1
                        ELSE 0
                    END
                ) as in_progress_plans,
                AVG(progress_percentage)
                    as average_progress
            ")
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'manager.department-reports',
            compact(
                'department',
                'employees',
                'assessments',
                'assessmentReports',
                'totalAssessments',
                'totalAttempts',
                'averageScore',
                'highRisk',
                'mediumRisk',
                'lowRisk',
                'totalLearningPlans',
                'completedLearningPlans',
                'inProgressLearningPlans',
                'notStartedLearningPlans',
                'averageLearningProgress',
                'employeeLearningProgress'
            )
        );
    }
}
