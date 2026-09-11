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
    public function create(Request $request)
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

        $categories = Category::orderBy('name')->get();

        $employees = User::where(
            'organisation_id',
            $user->organisation_id
        )
        ->where(
            'department_id',
            $user->department_id
        )
        ->whereHas('role', function ($query) {
            $query->where('slug', 'employee');
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
        $user = $request->user();

        abort_unless(
            $user->hasRole('manager'),
            403
        );

        abort_unless(
            $user->organisation_id && $user->department_id,
            403
        );

        $assessments = Assessment::with([
            'employeeQuestions',
        ])
        ->where(
            'created_by',
            $user->id
        )
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
        $user = $request->user();

        abort_unless(
            $user->hasRole('manager'),
            403
        );

        abort_unless(
            $user->organisation_id && $user->department_id,
            403
        );

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

        /*
         * SECURITY:
         * Do not trust the employee_id supplied by the form.
         * Confirm that the selected employee belongs to this
         * manager's organisation and department.
         */
        $employee = User::where(
            'id',
            $validated['employee_id']
        )
        ->where(
            'organisation_id',
            $user->organisation_id
        )
        ->where(
            'department_id',
            $user->department_id
        )
        ->whereHas('role', function ($query) {
            $query->where('slug', 'employee');
        })
        ->first();

        if (! $employee) {
            return back()
                ->withInput()
                ->withErrors([
                    'employee_id' =>
                        'You can only create assessments for employees in your department.',
                ]);
        }

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
                $questions,
                $user
            ) {
                $assessment = Assessment::create([
                    'name' => $validated['assessment_name'],
                    'created_by' => $user->id,
                    'organisation_id' => $user->organisation_id,
                    'department_id' => $user->department_id,
                    'employee_id' => $employee->id,
                    'status' => 'active',
                    'total_questions' => $questions->count(),
                    'duration_minutes' => $validated['duration_minutes'],
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
