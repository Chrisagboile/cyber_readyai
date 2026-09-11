<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Category;
use App\Models\EmployeeQuestion;
use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrganisationAssessmentController extends Controller
{
    /**
     * Confirm the authenticated user is an Organisation Admin
     * belonging to an organisation.
     */
    private function organisationId(Request $request): int
    {
        $user = $request->user();

        abort_unless($user, 403);

        abort_unless(
            $user->hasRole('organisation-admin'),
            403
        );

        abort_unless(
            $user->organisation_id,
            403
        );

        return (int) $user->organisation_id;
    }

    /**
     * Display organisation assessments.
     */
    public function index(Request $request)
    {
        $organisationId = $this->organisationId($request);

        $assessments = Assessment::query()
            ->where('organisation_id', $organisationId)
            ->with([
                'employee',
                'creator',
                'department',
            ])
            ->withCount('attempts')
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $totalAssessments = Assessment::query()
            ->where('organisation_id', $organisationId)
            ->count();

        $activeAssessments = Assessment::query()
            ->where('organisation_id', $organisationId)
            ->where('status', 'active')
            ->count();

        $completedAttempts = \App\Models\AssessmentAttempt::query()
            ->whereIn(
                'assessment_id',
                Assessment::query()
                    ->where('organisation_id', $organisationId)
                    ->select('id')
            )
            ->where('status', 'completed')
            ->count();

        $employeeCount = User::query()
            ->where('organisation_id', $organisationId)
            ->where('status', 'active')
            ->whereHas(
                'role',
                fn ($query) => $query->where('slug', 'employee')
            )
            ->count();

        return view(
            'organisation.assessments.index',
            compact(
                'assessments',
                'totalAssessments',
                'activeAssessments',
                'completedAttempts',
                'employeeCount',
            )
        );
    }

    /**
     * Show assessment creation form.
     */
    public function create(Request $request)
    {
        $organisationId = $this->organisationId($request);

        $categories = Category::query()
            ->orderBy('name')
            ->get();

        $employees = User::query()
            ->where('organisation_id', $organisationId)
            ->where('status', 'active')
            ->whereHas(
                'role',
                fn ($query) => $query->where('slug', 'employee')
            )
            ->with('department')
            ->orderBy('name')
            ->get();

        return view(
            'organisation.assessments.create',
            compact(
                'categories',
                'employees',
            )
        );
    }

    /**
     * Generate and assign an organisation assessment.
     */
    public function generate(Request $request)
    {
        $organisationId = $this->organisationId($request);

        $user = $request->user();

        $validated = $request->validate([
            'assessment_name' => [
                'required',
                'string',
                'max:255',
            ],

            'employee_id' => [
                'required',
                'integer',
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
        |--------------------------------------------------------------------------
        | SECURITY: employee must belong to this organisation
        |--------------------------------------------------------------------------
        */

        $employee = User::query()
            ->where('id', $validated['employee_id'])
            ->where('organisation_id', $organisationId)
            ->where('status', 'active')
            ->whereHas(
                'role',
                fn ($query) => $query->where('slug', 'employee')
            )
            ->first();

        if (! $employee) {
            return back()
                ->withInput()
                ->withErrors([
                    'employee_id' =>
                        'You can only create assessments for active employees in your organisation.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Check question availability
        |--------------------------------------------------------------------------
        */

        $availableQuestions = Question::query()
            ->whereIn(
                'category_id',
                $validated['category_ids']
            )
            ->count();

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

        /*
        |--------------------------------------------------------------------------
        | Select random questions
        |--------------------------------------------------------------------------
        */

        $questions = Question::query()
            ->whereIn(
                'category_id',
                $validated['category_ids']
            )
            ->inRandomOrder()
            ->limit($validated['question_quantity'])
            ->get();

        if ($questions->isEmpty()) {
            return back()
                ->withInput()
                ->withErrors([
                    'question_quantity' =>
                        'No questions are available for the selected categories.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Create assessment and employee questions atomically
        |--------------------------------------------------------------------------
        */

        $assessment = DB::transaction(
            function () use (
                $validated,
                $employee,
                $questions,
                $user,
                $organisationId
            ) {
                $assessment = Assessment::create([
                    'name' => $validated['assessment_name'],
                    'created_by' => $user->id,

                    /*
                     * Organisation Admin assessments belong to the
                     * Organisation Admin's organisation.
                     */
                    'organisation_id' => $organisationId,

                    /*
                     * The assessment department follows the
                     * employee being assessed.
                     */
                    'department_id' => $employee->department_id,

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
            ->route('organisation.assessments')
            ->with(
                'success',
                "Assessment '{$assessment->name}' was created "
                . "successfully for {$employee->name} with "
                . "{$assessment->total_questions} questions."
            );
    }
}
