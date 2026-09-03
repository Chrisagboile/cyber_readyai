<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentAnswer;
use App\Models\AssessmentAttempt;
use App\Models\EmployeeQuestion;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssessmentController extends Controller
{
    public function start(
        Request $request,
        Assessment $assessment
    ) {
        $user = $request->user();

        if (
            $assessment->employee_id
            !== $user->id
        ) {
            abort(403);
        }

        $attempt = AssessmentAttempt::where(
            'assessment_id',
            $assessment->id
        )
        ->where(
            'user_id',
            $user->id
        )
        ->first();

        /*
        |--------------------------------------------------------------------------
        | Already completed
        |--------------------------------------------------------------------------
        */

        if (
            $attempt
            && $attempt->status === 'completed'
        ) {
            return redirect()->route(
                'assessment.result',
                $attempt
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Existing attempt
        |--------------------------------------------------------------------------
        */

        if ($attempt) {

            if (
                $attempt->status === 'expired'
                || (
                    $attempt->expires_at
                    && $attempt->expires_at->isPast()
                )
            ) {
                $this->expireAttempt($attempt);

                return redirect()->route(
                    'assessment.result',
                    $attempt
                );
            }

            $position = max(
                1,
                (int) $attempt->current_position
            );

            return redirect()->route(
                'assessment.question',
                [
                    'attempt' => $attempt->id,
                    'question' => $position,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Get questions belonging to this assessment
        |--------------------------------------------------------------------------
        */

        $employeeQuestions = $assessment
            ->employeeQuestions()
            ->where(
                'employee_id',
                $user->id
            )
            ->get();

        if ($employeeQuestions->isEmpty()) {
            return back()->with(
                'error',
                'This assessment does not contain any questions.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Create attempt
        |--------------------------------------------------------------------------
        */

        $attempt = DB::transaction(
            function () use (
                $assessment,
                $user,
                $employeeQuestions
            ) {
                $startedAt = now();

                $expiresAt = $startedAt->copy()
                    ->addMinutes(
                        $assessment->duration_minutes
                    );

                $attempt = AssessmentAttempt::create([
                    'assessment_id' =>
                        $assessment->id,

                    'user_id' =>
                        $user->id,

                    'current_position' =>
                        1,

                    'status' =>
                        'in_progress',

                    'total_questions' =>
                        $employeeQuestions->count(),

                    'answered_questions' =>
                        0,

                    'correct_answers' =>
                        0,

                    'score_percentage' =>
                        0,

                    'risk_level' =>
                        null,

                    'started_at' =>
                        $startedAt,

                    'expires_at' =>
                        $expiresAt,
                ]);

                foreach (
                    $employeeQuestions
                    as $employeeQuestion
                ) {
                    $employeeQuestion->update([
                        'assessment_attempt_id' =>
                            $attempt->id,
                    ]);
                }

                return $attempt;
            }
        );

        return redirect()->route(
            'assessment.question',
            [
                'attempt' => $attempt->id,
                'question' => 1,
            ]
        );
    }

    public function question(
        Request $request,
        AssessmentAttempt $attempt,
        int $question
    ) {
        $this->authoriseAttempt(
            $request,
            $attempt
        );

        if ($attempt->status !== 'in_progress') {
            return redirect()->route(
                'assessment.result',
                $attempt
            );
        }

        if (
            $attempt->expires_at
            && $attempt->expires_at->isPast()
        ) {
            $this->expireAttempt($attempt);

            return redirect()->route(
                'assessment.result',
                $attempt
            );
        }

        $employeeQuestions = $attempt
            ->employeeQuestions()
            ->get();

        $total = $employeeQuestions->count();

        if (
            $question < 1
            || $question > $total
        ) {
            abort(404);
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent jumping ahead
        |--------------------------------------------------------------------------
        */

        $currentPosition = max(
            1,
            (int) $attempt->current_position
        );

        if ($question > $currentPosition) {
            return redirect()->route(
                'assessment.question',
                [
                    'attempt' => $attempt->id,
                    'question' => $currentPosition,
                ]
            );
        }

        $employeeQuestion =
            $employeeQuestions->get(
                $question - 1
            );

        $currentQuestion = Question::with([
            'options',
            'category',
        ])->findOrFail(
            $employeeQuestion->question_id
        );

        $answer = AssessmentAnswer::where(
            'assessment_attempt_id',
            $attempt->id
        )
        ->where(
            'question_id',
            $currentQuestion->id
        )
        ->first();

        $answeredCount = $attempt
            ->answers()
            ->count();

        return view(
            'assessment.question',
            compact(
                'attempt',
                'currentQuestion',
                'question',
                'total',
                'answer',
                'answeredCount'
            )
        );
    }

    public function answer(
        Request $request,
        AssessmentAttempt $attempt,
        int $question
    ) {
        $this->authoriseAttempt(
            $request,
            $attempt
        );

        if ($attempt->status !== 'in_progress') {
            return redirect()->route(
                'assessment.result',
                $attempt
            );
        }

        if (
            $attempt->expires_at
            && $attempt->expires_at->isPast()
        ) {
            $this->expireAttempt($attempt);

            return redirect()->route(
                'assessment.result',
                $attempt
            );
        }

        $validated = $request->validate([
            'question_option_id' => [
                'required',
                'integer',
                'exists:question_options,id',
            ],
        ]);

        $employeeQuestions = $attempt
            ->employeeQuestions()
            ->get();

        $total = $employeeQuestions->count();

        if (
            $question < 1
            || $question > $total
        ) {
            abort(404);
        }

        $employeeQuestion =
            $employeeQuestions->get(
                $question - 1
            );

        $currentQuestion = Question::findOrFail(
            $employeeQuestion->question_id
        );

        $option = QuestionOption::where(
            'id',
            $validated['question_option_id']
        )
        ->where(
            'question_id',
            $currentQuestion->id
        )
        ->firstOrFail();

        AssessmentAnswer::updateOrCreate(
            [
                'assessment_attempt_id' =>
                    $attempt->id,

                'question_id' =>
                    $currentQuestion->id,
            ],
            [
                'question_option_id' =>
                    $option->id,

                'is_correct' =>
                    $option->is_correct,
            ]
        );

        $answeredQuestions = $attempt
            ->answers()
            ->count();

        $nextPosition = min(
            $question + 1,
            $total
        );

        $attempt->update([
            'answered_questions' =>
                $answeredQuestions,

            'current_position' =>
                $nextPosition,
        ]);

        if ($question < $total) {
            return redirect()->route(
                'assessment.question',
                [
                    'attempt' => $attempt->id,
                    'question' => $question + 1,
                ]
            );
        }

        return redirect()->route(
            'assessment.review',
            $attempt
        );
    }

    public function review(
        Request $request,
        AssessmentAttempt $attempt
    ) {
        $this->authoriseAttempt(
            $request,
            $attempt
        );

        if ($attempt->status !== 'in_progress') {
            return redirect()->route(
                'assessment.result',
                $attempt
            );
        }

        if (
            $attempt->expires_at
            && $attempt->expires_at->isPast()
        ) {
            $this->expireAttempt($attempt);

            return redirect()->route(
                'assessment.result',
                $attempt
            );
        }

        $employeeQuestions = $attempt
            ->employeeQuestions()
            ->get();

        $questionIds = $employeeQuestions
            ->pluck('question_id');

        $questions = Question::with([
            'options',
            'category',
        ])
        ->whereIn(
            'id',
            $questionIds
        )
        ->get()
        ->sortBy(function ($question) use (
            $questionIds
        ) {
            return $questionIds->search(
                $question->id
            );
        })
        ->values();

        $answers = $attempt
            ->answers()
            ->with('option')
            ->get()
            ->keyBy('question_id');

        return view(
            'assessment.review',
            compact(
                'attempt',
                'questions',
                'answers'
            )
        );
    }

    public function submit(
        Request $request,
        AssessmentAttempt $attempt
    ) {
        $this->authoriseAttempt(
            $request,
            $attempt
        );

        if ($attempt->status === 'completed') {
            return redirect()->route(
                'assessment.result',
                $attempt
            );
        }

        if ($attempt->status !== 'in_progress') {
            return redirect()->route(
                'assessment.result',
                $attempt
            );
        }

        if (
            $attempt->expires_at
            && $attempt->expires_at->isPast()
        ) {
            $this->expireAttempt($attempt);

            return redirect()->route(
                'assessment.result',
                $attempt
            );
        }

        $this->completeAttempt($attempt);

        return redirect()->route(
            'assessment.result',
            $attempt
        );
    }

    public function result(
        Request $request,
        AssessmentAttempt $attempt
    ) {
        $this->authoriseAttempt(
            $request,
            $attempt
        );

        $attempt->load([
            'assessment',
            'answers.question.category',
            'answers.option',
        ]);

        return view(
            'assessment.result',
            compact('attempt')
        );
    }

    private function completeAttempt(
        AssessmentAttempt $attempt
    ): void {
        $correctAnswers = $attempt
            ->answers()
            ->where('is_correct', true)
            ->count();

        $answeredQuestions = $attempt
            ->answers()
            ->count();

        $totalQuestions =
            $attempt->total_questions;

        $score = $totalQuestions > 0
            ? round(
                (
                    $correctAnswers
                    / $totalQuestions
                ) * 100,
                2
            )
            : 0;

        $attempt->update([
            'status' =>
                'completed',

            'answered_questions' =>
                $answeredQuestions,

            'correct_answers' =>
                $correctAnswers,

            'score_percentage' =>
                $score,

            'risk_level' =>
                $this->calculateRiskLevel($score),

            'completed_at' =>
                now(),
        ]);
    }

    private function expireAttempt(
        AssessmentAttempt $attempt
    ): void {
        if (
            $attempt->status !== 'in_progress'
        ) {
            return;
        }

        $correctAnswers = $attempt
            ->answers()
            ->where('is_correct', true)
            ->count();

        $answeredQuestions = $attempt
            ->answers()
            ->count();

        $totalQuestions =
            $attempt->total_questions;

        $score = $totalQuestions > 0
            ? round(
                (
                    $correctAnswers
                    / $totalQuestions
                ) * 100,
                2
            )
            : 0;

        $attempt->update([
            'status' =>
                'expired',

            'answered_questions' =>
                $answeredQuestions,

            'correct_answers' =>
                $correctAnswers,

            'score_percentage' =>
                $score,

            'risk_level' =>
                $this->calculateRiskLevel($score),

            'completed_at' =>
                now(),
        ]);
    }

    private function calculateRiskLevel(
        float $score
    ): string {
        if ($score >= 80) {
            return 'Low';
        }

        if ($score >= 60) {
            return 'Medium';
        }

        return 'High';
    }

    private function authoriseAttempt(
        Request $request,
        AssessmentAttempt $attempt
    ): void {
        if (
            $attempt->user_id
            !== $request->user()->id
        ) {
            abort(403);
        }
    }
    public function index(Request $request)
    {
        $user = $request->user();

        $assessments = Assessment::where(
            'employee_id',
            $user->id
        )
        ->with([
            'attempts' => function ($query) use ($user) {
                $query->where(
                    'user_id',
                    $user->id
                );
            },
        ])
        ->orderByDesc('created_at')
        ->paginate(10);

        return view(
            'assessment.index',
            compact('assessments')
        );
    }
}
