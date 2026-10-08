<?php

namespace App\Modules\PublicTest\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\PublicTest\Models\PublicTestAttempt;
use App\Modules\PublicTest\Models\PublicTestLearningPlan;
use Illuminate\Http\Request;

class PublicTestDashboardController extends Controller
{
    /**
     * Public Test Dashboard
     */
    public function index(Request $request)
    {
        $user = auth('public_test')->user();

        /*
        |--------------------------------------------------------------------------
        | All attempts belonging ONLY to this Public Test user
        |--------------------------------------------------------------------------
        */

        $attemptQuery = PublicTestAttempt::query()
            ->where(
                'public_test_user_id',
                $user->id
            );

        /*
        |--------------------------------------------------------------------------
        | Latest attempt
        |--------------------------------------------------------------------------
        */

        $latestAttempt = (clone $attemptQuery)
            ->latest('id')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Completed attempts
        |--------------------------------------------------------------------------
        */

        $completedAttempts = (clone $attemptQuery)
            ->where(
                'status',
                'completed'
            )
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Total attempts
        |--------------------------------------------------------------------------
        */

        $totalAttempts = (clone $attemptQuery)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Average score
        |--------------------------------------------------------------------------
        */

        $averageScore = (clone $attemptQuery)
            ->where(
                'status',
                'completed'
            )
            ->whereNotNull('score_percentage')
            ->avg('score_percentage');

        $averageScore = $averageScore !== null
            ? round((float) $averageScore, 1)
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Best score
        |--------------------------------------------------------------------------
        */

        $bestScore = (clone $attemptQuery)
            ->where(
                'status',
                'completed'
            )
            ->whereNotNull('score_percentage')
            ->max('score_percentage');

        $bestScore = $bestScore !== null
            ? round((float) $bestScore, 1)
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Current/in-progress attempt
        |--------------------------------------------------------------------------
        */

        $inProgressAttempt = (clone $attemptQuery)
            ->where(
                'status',
                'in_progress'
            )
            ->latest('id')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Recent attempts
        |--------------------------------------------------------------------------
        */

        $attempts = (clone $attemptQuery)
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Learning plans
        |--------------------------------------------------------------------------
        */

        $learningPlans = PublicTestLearningPlan::query()
            ->where(
                'public_test_user_id',
                $user->id
            )
            ->latest('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Learning plan statistics
        |--------------------------------------------------------------------------
        */

        $totalLearningPlans = $learningPlans->count();

        $completedLearningPlans = $learningPlans
            ->where(
                'status',
                'completed'
            )
            ->count();

        $activeLearningPlans = $learningPlans
            ->whereNotIn(
                'status',
                [
                    'completed',
                    'cancelled',
                ]
            )
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Average learning progress
        |--------------------------------------------------------------------------
        */

        $averageLearningProgress = $learningPlans->count() > 0
            ? round(
                (float) $learningPlans->avg(
                    'progress_percentage'
                ),
                1
            )
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        return view(
            'public-test.dashboard',
            [
                'user' => $user,

                'latestAttempt' =>
                    $latestAttempt,

                'inProgressAttempt' =>
                    $inProgressAttempt,

                'attempts' =>
                    $attempts,

                'totalAttempts' =>
                    $totalAttempts,

                'completedAttempts' =>
                    $completedAttempts,

                'averageScore' =>
                    $averageScore,

                'bestScore' =>
                    $bestScore,

                'learningPlans' =>
                    $learningPlans,

                'totalLearningPlans' =>
                    $totalLearningPlans,

                'completedLearningPlans' =>
                    $completedLearningPlans,

                'activeLearningPlans' =>
                    $activeLearningPlans,

                'averageLearningProgress' =>
                    $averageLearningProgress,
            ]
        );
    }

    /**
     * Public Test assessment history.
     */
    public function assessments(Request $request)
    {
        $user = auth('public_test')->user();

        $attempts = PublicTestAttempt::query()
            ->where(
                'public_test_user_id',
                $user->id
            )
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view(
            'public-test.assessments',
            [
                'user' => $user,
                'attempts' => $attempts,
            ]
        );
    }

    /**
     * Public Test learning plans.
     */
    public function learning(Request $request)
    {
        $user = auth('public_test')->user();

        $learningPlans = PublicTestLearningPlan::query()
            ->where(
                'public_test_user_id',
                $user->id
            )
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view(
            'public-test.learning',
            [
                'user' => $user,
                'learningPlans' => $learningPlans,
            ]
        );
    }
}
