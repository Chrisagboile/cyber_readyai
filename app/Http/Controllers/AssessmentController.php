<?php

namespace App\Http\Controllers;

use App\Models\AssessmentAnswer;
use App\Models\AssessmentAttempt;
use App\Models\EmployeeQuestion;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    /**
     * Start a new assessment.
     */
    public function start(Request $request)
    {
        $user = $request->user();

        // Get questions assigned to this employee.
        $questions = Question::whereIn('id', function ($query) use ($user) {
            $query->select('question_id')
                ->from('employee_questions')
                ->where('employee_id', $user->id);
        })
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
            'answered_questions' => 0,
            'correct_answers' => 0,
            'score_percentage' => 0,
            'started_at' => now(),
        ]);

        /*
         * Create the questions for this particular attempt.
         */
        foreach ($questions as $index => $question) {

            EmployeeQuestion::updateOrCreate(
                [
                    'employee_id' => $user->id,
                    'question_id' => $question->id,
                    'assessment_attempt_id' => $attempt->id,
                ],
                [
                    'question_order' => $index + 1,
                ]
            );
        }

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

        /*
         * Get the employee's questions for THIS attempt
         * in the correct order.
         */
        $employeeQuestions = EmployeeQuestion::where(
            'employee_id',
            $request->user()->id
        )
        ->where(
            'assessment_attempt_id',
            $attempt->id
        )
        ->orderBy('question_order')
        ->get();

        $total = $employeeQuestions->count();

        if ($question < 1 || $question > $total) {
            abort(404);
        }

        /*
         * Get the EmployeeQuestion at the current position.
         */
        $employeeQuestion = $employeeQuestions->get(
            $question - 1
        );

        /*
         * Get the actual Question.
         */
        $currentQuestion = Question::with([
            'options',
            'category',
        ])->findOrFail(
            $employeeQuestion->question_id
        );

        /*
         * Get previously saved answer.
         */
        $answer = AssessmentAnswer::where(
            'assessment_attempt_id',
            $attempt->id
        )
        ->where(
            'question_id',
            $currentQuestion->id
        )
        ->first();

        /*
         * Number of questions already answered.
         */
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
     * Store/update an answer and move to next question.
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

        /*
         * Get employee questions in the correct order.
         */
        $employeeQuestions = EmployeeQuestion::where(
            'employee_id',
            $request->user()->id
        )
        ->where(
            'assessment_attempt_id',
            $attempt->id
        )
        ->orderBy('question_order')
        ->get();

        $total = $employeeQuestions->count();

        if ($question < 1 || $question > $total) {
            abort(404);
        }

        /*
         * Get the current EmployeeQuestion.
         */
        $employeeQuestion = $employeeQuestions->get(
            $question - 1
        );

        /*
         * Get the actual question.
         */
        $currentQuestion = Question::findOrFail(
            $employeeQuestion->question_id
        );

        /*
         * Make sure the selected option belongs
         * to the current question.
         */
        $option = QuestionOption::where(
            'id',
            $request->question_option_id
        )
        ->where(
            'question_id',
            $currentQuestion->id
        )
        ->firstOrFail();

        /*
         * Save/update answer.
         */
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

        /*
         * Update answered count.
         */
        $answeredQuestions = AssessmentAnswer::where(
            'assessment_attempt_id',
            $attempt->id
        )->count();

        $attempt->update([
            'answered_questions' => $answeredQuestions,
        ]);

        /*
         * Move to next question.
         */
        $nextQuestion = $question + 1;

        if ($nextQuestion <= $total) {
            return redirect()->route(
                'assessment.question',
                [
                    'attempt' => $attempt->id,
                    'question' => $nextQuestion,
                ]
            );
        }

        /*
         * No more questions.
         * Go to review page.
         */
        return redirect()->route(
            'assessment.review',
            $attempt
        );
    }


    /**
     * Go to previous question.
     */
    public function previous(
        Request $request,
        AssessmentAttempt $attempt,
        int $question
    ) {
        $this->authoriseAttempt($request, $attempt);

        $previousQuestion = $question - 1;

        /*
         * If already on question 1,
         * stay on question 1.
         */
        if ($previousQuestion < 1) {
            return redirect()->route(
                'assessment.question',
                [
                    'attempt' => $attempt->id,
                    'question' => 1,
                ]
            );
        }

        return redirect()->route(
            'assessment.question',
            [
                'attempt' => $attempt->id,
                'question' => $previousQuestion,
            ]
        );
    }


    /**
     * Go to next question without saving.
     *
     * Useful if you have a separate Next button.
     */
    public function next(
        Request $request,
        AssessmentAttempt $attempt,
        int $question
    ) {
        $this->authoriseAttempt($request, $attempt);

        $total = EmployeeQuestion::where(
            'employee_id',
            $request->user()->id
        )
        ->where(
            'assessment_attempt_id',
            $attempt->id
        )
        ->count();

        $nextQuestion = $question + 1;

        if ($nextQuestion > $total) {
            return redirect()->route(
                'assessment.review',
                $attempt
            );
        }

        return redirect()->route(
            'assessment.question',
            [
                'attempt' => $attempt->id,
                'question' => $nextQuestion,
            ]
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

        $employeeQuestions = EmployeeQuestion::where(
            'employee_id',
            $request->user()->id
        )
        ->where(
            'assessment_attempt_id',
            $attempt->id
        )
        ->orderBy('question_order')
        ->pluck('question_id');

        $questions = Question::with([
            'options',
            'category',
        ])
        ->whereIn('id', $employeeQuestions)
        ->get()
        ->sortBy(function ($question) use ($employeeQuestions) {
            return $employeeQuestions->search(
                $question->id
            );
        })
        ->values();

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

        $riskLevel = $this->calculateRiskLevel(
            $score
        );

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

        $attempt->load(
            'answers.question.category'
        );

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
