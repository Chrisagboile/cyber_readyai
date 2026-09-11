<?php

namespace App\Http\Controllers;

use App\Models\AiAssessmentInsight;
use Illuminate\Http\Request;

class LearningPlanController extends Controller
{
    public function index(Request $request)
    {
        $insights = AiAssessmentInsight::whereHas(
            'assessmentAttempt',
            function ($query) use ($request) {
                $query->where('user_id', $request->user()->id)
                    ->whereIn('status', ['completed', 'expired']);
            }
        )
            ->with([
                'assessmentAttempt.assessment',
            ])
            ->orderByDesc('generated_at')
            ->get();

        return view('employee.learning-plans', compact('insights'));
    }
}