<?php

namespace App\Http\Controllers;

use App\Models\AssessmentAttempt;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function score(Request $request)
    {
        $attempts = AssessmentAttempt::where('user_id', $request->user()->id)
            ->whereIn('status', ['completed', 'expired'])
            ->with('assessment')
            ->orderByDesc('completed_at')
            ->paginate(10);

        return view('employee.score', compact('attempts'));
    }
}
