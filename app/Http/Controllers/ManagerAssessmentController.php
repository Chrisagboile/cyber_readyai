<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Category;
use App\Models\EmployeeQuestion;
use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManagerAssessmentController extends Controller
{
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        $employees = User::whereHas('role', function ($query) {
            $query->where('name', 'employee');
        })
        ->orderBy('name')
        ->get();

        return view(
            'manager.assessments.create',
            compact(
                'categories',
                'employees'
            )
        );
    }
    public function index(Request $request)
    {
        $assessments = Assessment::with([
            'employeeQuestions',
        ])
        ->where('created_by', $request->user()->id)
        ->withCount('attempts')
        ->orderByDesc('created_at')
        ->paginate(15);

        return view(
            'manager.assessments.index',
            compact('assessments')
        );
    }
        public function generate(Request $request)
    {
        $validated = $request->validate([
            'assessment_name' => [
                'required',
                'string',
                'max:255',
            ],

            'employee_id' => [
                'required',
                'exists:users,id',
            ],

            'category_ids' => [
                'required',
                'array',
                'min:1',
            ],

            'category_ids.*' => [
                'integer',
                'exists:categories,id',
            ],

            'question_quantity' => [
                'required',
                'integer',
                'min:1',
                'max:200',
            ],

            'duration_minutes' => [
                'required',
                'integer',
                'min:1',
                'max:240',
            ],
        ]);

        $employee = User::findOrFail(
            $validated['employee_id']
        );

        $availableQuestions = Question::whereIn(
            'category_id',
            $validated['category_ids']
        )->count();

        if (
            $validated['question_quantity']
            > $availableQuestions
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'question_quantity' =>
                        "Only {$availableQuestions} questions are "
                        . "available in the selected categories.",
                ]);
        }

        $questions = Question::whereIn(
            'category_id',
            $validated['category_ids']
        )
        ->inRandomOrder()
        ->limit($validated['question_quantity'])
        ->get();

        $assessment = DB::transaction(
            function () use (
                $validated,
                $employee,
                $questions
            ) {
                $assessment = Assessment::create([
                    'name' => $validated['assessment_name'],
                    'created_by' => auth()->id(),
                    'employee_id' => $employee->id,
                    'status' => 'active',
                    'total_questions' => $questions->count(),
                    'duration_minutes' =>
                        $validated['duration_minutes'],
                ]);

                foreach (
                    $questions as $index => $question
                ) {
                    EmployeeQuestion::create([
                        'employee_id' => $employee->id,
                        'assessment_id' => $assessment->id,
                        'question_id' => $question->id,
                        'question_order' => $index + 1,
                        'assessment_attempt_id' => null,
                    ]);
                }

                return $assessment;
            }
        );

        return redirect()
            ->route('manager.assessments.create')
            ->with(
                'success',
                "Assessment '{$assessment->name}' created "
                . "successfully with {$assessment->total_questions} "
                . "questions."
            );
    }
}
