<?php

namespace App\Http\Controllers;

use App\Models\AssessmentAttempt;
use App\Models\AssessmentAnswer;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssessmentController extends Controller
{
    /**
     * Start a new assessment.
     */
    public function start(Request $request)
    {
        $user = $request->user();

        $questions = Question::with('options')
            ->orderBy('id')
            ->get();

        if ($questions->isEmpty()) {
            return back()->with(
                'error',
                'No assessment questions are currently available.'
            );
        }

        $attempt = AssessmentAttempt::create([
            'user_id' => $user->id,
            'status' => 'in_progress',
            'total_questions' => $questions->count(),
            'started_at' => now(),
        ]);

        return redirect()->route(
            'assessment.question',
            [
                'attempt' => $attempt->id,
                'question' => 1,
            ]
        );
    }

    /**
     * Display one question.
     */
    public function question(
        Request $request,
        AssessmentAttempt $attempt,
        int $question
    ) {
        $this->authoriseAttempt($request, $attempt);

        if ($attempt->status !== 'in_progress') {
            return redirect()->route(
                'assessment.result',
                $attempt
            );
        }

        $questions = Question::with([
            'options',
            'category'
        ])
            ->orderBy('id')
            ->get();

        $total = $questions->count();

        if ($question < 1 || $question > $total) {
            abort(404);
        }

        $currentQuestion = $questions->values()->get(
            $question - 1
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

        $answeredCount = AssessmentAnswer::where(
            'assessment_attempt_id',
            $attempt->id
        )->count();

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

    /**
     * Store/update an answer.
     */
    public function answer(
        Request $request,
        AssessmentAttempt $attempt,
        int $question
    ) {
        $this->authoriseAttempt($request, $attempt);

        if ($attempt->status !== 'in_progress') {
            abort(403);
        }

        $request->validate([
            'question_option_id' => [
                'required',
                'integer',
                'exists:question_options,id',
            ],
        ]);

        $questions = Question::orderBy('id')->get();

        $currentQuestion = $questions->values()->get(
            $question - 1
        );

        if (!$currentQuestion) {
            abort(404);
        }

        $option = QuestionOption::where(
            'id',
            $request->question_option_id
        )
            ->where(
                'question_id',
                $currentQuestion->id
            )
            ->firstOrFail();

        AssessmentAnswer::updateOrCreate(
            [
                'assessment_attempt_id' => $attempt->id,
                'question_id' => $currentQuestion->id,
            ],
            [
                'question_option_id' => $option->id,
                'is_correct' => $option->is_correct,
            ]
        );

        $attempt->update([
            'answered_questions' => $attempt->answers()->count(),
        ]);

        $nextQuestion = $question + 1;

        if ($nextQuestion <= $questions->count()) {
            return redirect()->route(
                'assessment.question',
                [
                    'attempt' => $attempt->id,
                    'question' => $nextQuestion,
                ]
            );
        }

        return redirect()->route(
            'assessment.review',
            $attempt
        );
    }

    /**
     * Review answers before submitting.
     */
    public function review(
        Request $request,
        AssessmentAttempt $attempt
    ) {
        $this->authoriseAttempt($request, $attempt);

        $questions = Question::with([
            'options',
            'category'
        ])
            ->orderBy('id')
            ->get();

        $answers = $attempt->answers()
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

    /**
     * Submit and grade assessment.
     */
    public function submit(
        Request $request,
        AssessmentAttempt $attempt
    ) {
        $this->authoriseAttempt($request, $attempt);

        if ($attempt->status !== 'in_progress') {
            return redirect()->route(
                'assessment.result',
                $attempt
            );
        }

        $totalQuestions = $attempt->total_questions;

        $correctAnswers = $attempt->answers()
            ->where('is_correct', true)
            ->count();

        $answeredQuestions = $attempt->answers()->count();

        $score = $totalQuestions > 0
            ? round(
                ($correctAnswers / $totalQuestions) * 100,
                2
            )
            : 0;

        $riskLevel = $this->calculateRiskLevel($score);

        $attempt->update([
            'status' => 'completed',
            'answered_questions' => $answeredQuestions,
            'correct_answers' => $correctAnswers,
            'score_percentage' => $score,
            'risk_level' => $riskLevel,
            'completed_at' => now(),
        ]);

        return redirect()->route(
            'assessment.result',
            $attempt
        );
    }

    /**
     * Display result.
     */
    public function result(
        Request $request,
        AssessmentAttempt $attempt
    ) {
        $this->authoriseAttempt($request, $attempt);

        $attempt->load('answers.question.category');

        return view(
            'assessment.result',
            compact('attempt')
        );
    }

    /**
     * Determine risk level from score.
     */
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

    /**
     * Ensure users can only access their own attempts.
     */
    private function authoriseAttempt(
        Request $request,
        AssessmentAttempt $attempt
    ): void {
        if ($attempt->user_id !== $request->user()->id) {
            abort(403);
        }
    }
}
