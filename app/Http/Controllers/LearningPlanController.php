<?php

namespace App\Http\Controllers;

use App\Models\LearningPlan;
use Illuminate\Http\Request;
use App\Models\User;

class LearningPlanController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        abort_unless($user->hasRole('employee'), 403);

        $learningPlans = LearningPlan::where('user_id', $user->id)
            ->with('assessmentAttempt.assessment')
            ->orderByRaw("
                CASE priority
                    WHEN 'high' THEN 1
                    WHEN 'medium' THEN 2
                    WHEN 'low' THEN 3
                    ELSE 4
                END
            ")
            ->orderBy('due_date')
            ->paginate(10);

        $totalPlans = LearningPlan::where('user_id', $user->id)->count();

        $completedPlans = LearningPlan::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        $inProgressPlans = LearningPlan::where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->count();

        $notStartedPlans = LearningPlan::where('user_id', $user->id)
            ->where('status', 'not_started')
            ->count();

        return view('employee.learning-plans', compact(
            'learningPlans',
            'totalPlans',
            'completedPlans',
            'inProgressPlans',
            'notStartedPlans'
        ));
    }

    public function managerIndex(Request $request, User $employee)
    {
        $user = $request->user();

        abort_unless($user->hasRole('manager'), 403);

        abort_unless(
            $user->organisation_id &&
            $user->department_id,
            403
        );

        abort_unless(
            (int) $employee->organisation_id === (int) $user->organisation_id &&
            (int) $employee->department_id === (int) $user->department_id &&
            $employee->hasRole('employee'),
            403
        );

        $learningPlans = LearningPlan::where('user_id', $employee->id)
            ->with('assessmentAttempt.assessment')
            ->orderByRaw("
                CASE priority
                    WHEN 'high' THEN 1
                    WHEN 'medium' THEN 2
                    WHEN 'low' THEN 3
                    ELSE 4
                END
            ")
            ->orderBy('due_date')
            ->get();

        $totalPlans = $learningPlans->count();

        $completedPlans = $learningPlans
            ->where('status', 'completed')
            ->count();

        $inProgressPlans = $learningPlans
            ->where('status', 'in_progress')
            ->count();

        $notStartedPlans = $learningPlans
            ->where('status', 'not_started')
            ->count();

        $averageProgress = $totalPlans > 0
            ? round((float) $learningPlans->avg('progress_percentage'))
            : 0;

        return view('manager.learning-plans', compact(
            'employee',
            'learningPlans',
            'totalPlans',
            'completedPlans',
            'inProgressPlans',
            'notStartedPlans',
            'averageProgress'
        ));
    }

    public function updateStatus(Request $request, LearningPlan $learningPlan)
    {
        $user = $request->user();

        abort_unless($user->hasRole('employee'), 403);
        abort_unless((int) $learningPlan->user_id === (int) $user->id, 403);

        $validated = $request->validate([
            'status' => ['required', 'in:not_started,in_progress,completed'],
        ]);

        $status = $validated['status'];

        $progress = match ($status) {
            'not_started' => 0,
            'in_progress' => 50,
            'completed' => 100,
        };

        $learningPlan->update([
            'status' => $status,
            'progress_percentage' => $progress,
            'completed_at' => $status === 'completed' ? now() : null,
        ]);

        return back()->with('success', 'Learning plan updated successfully.');
    }
}
