<?php

namespace App\Modules\PublicTest\Controllers;

use App\Models\Question;
use App\Models\QuestionOption;
use App\Modules\PublicTest\Models\PublicTestAnswer;
use App\Modules\PublicTest\Models\PublicTestAttempt;
use App\Modules\PublicTest\Models\PublicTestFeedback;
use App\Modules\PublicTest\Models\PublicTestLearningPlan;
use App\Modules\PublicTest\Models\PublicTestQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicTestController
{
    /**
     * Start a new test or resume an existing one.
     */
    public function start()
    {
        $user = auth('public_test')->user();

        $existingAttempt = $user
            ->attempts()
            ->where('status', 'in_progress')
            ->latest('id')
            ->first();

        if ($existingAttempt) {
            return redirect()->route(
                'public-test.question',
                [
                    'attempt' => $existingAttempt->id,
                    'question' => max(
                        1,
                        (int) $existingAttempt->current_position
                    ),
                ]
            );
        }

        /*
         * QUESTIONS COME FROM THE MAIN APPLICATION.
         *
         * Nothing else does.
         */
        $questions = Question::query()
            ->with([
                'options',
                'category',
            ])
            ->inRandomOrder()
            ->take(10)
            ->get();

        if ($questions->count() < 10) {
            abort(
                500,
                'Not enough questions are available for the public test.'
            );
        }

        $attempt = DB::transaction(
            function () use (
                $user,
                $questions
            ) {
                $attempt = PublicTestAttempt::create([
                    'public_test_user_id' =>
                        $user->id,

                    'status' =>
                        'in_progress',

                    'total_questions' =>
                        $questions->count(),

                    'answered_questions' =>
                        0,

                    'current_position' =>
                        1,

                    'correct_answers' =>
                        0,

                    'score_percentage' =>
                        0,

                    'risk_level' =>
                        null,

                    'started_at' =>
                        now(),
                ]);

                foreach (
                    $questions->values()
                    as $index => $question
                ) {
                    PublicTestQuestion::create([
                        'public_test_attempt_id' =>
                            $attempt->id,

                        'question_id' =>
                            $question->id,

                        'question_order' =>
                            $index + 1,
                    ]);
                }

                return $attempt;
            }
        );

        return redirect()->route(
            'public-test.question',
            [
                'attempt' => $attempt->id,
                'question' => 1,
            ]
        );
    }

    /**
     * Display any question.
     *
     * Users are allowed to jump backwards or forwards.
     */
    public function question(
        PublicTestAttempt $attempt,
        int $question
    ) {
        $this->authoriseAttempt($attempt);

        if ($attempt->status !== 'in_progress') {
            return redirect()->route(
                'public-test.result',
                $attempt
            );
        }

        $total = (int) $attempt->total_questions;

        if (
            $question < 1 ||
            $question > $total
        ) {
            abort(404);
        }

        $publicQuestion = $attempt
            ->questions()
            ->with([
                'question.options',
                'question.category',
            ])
            ->where(
                'question_order',
                $question
            )
            ->firstOrFail();

        $answer = $attempt
            ->answers()
            ->where(
                'question_id',
                $publicQuestion->question_id
            )
            ->with('option')
            ->first();

        $answeredCount = $attempt
            ->answers()
            ->count();

        $navigationQuestions = $attempt
            ->questions()
            ->with('question')
            ->get();

        return view(
            'public-test.question',
            [
                'attempt' =>
                    $attempt,

                'currentQuestion' =>
                    $publicQuestion->question,

                'publicTestQuestion' =>
                    $publicQuestion,

                'question' =>
                    $question,

                'total' =>
                    $total,

                'answer' =>
                    $answer,

                'answeredCount' =>
                    $answeredCount,

                'navigationQuestions' =>
                    $navigationQuestions,
            ]
        );
    }

    /**
     * Save an answer.
     *
     * Answers are persisted immediately.
     */
    public function answer(
        Request $request,
        PublicTestAttempt $attempt,
        int $question
    ) {
        $this->authoriseAttempt($attempt);

        abort_unless(
            $attempt->status === 'in_progress',
            403
        );

        $validated = $request->validate([
            'question_option_id' => [
                'required',
                'integer',
            ],
        ]);

        $total = (int) $attempt->total_questions;

        abort_unless(
            $question >= 1 &&
            $question <= $total,
            404
        );

        $publicQuestion = $attempt
            ->questions()
            ->where(
                'question_order',
                $question
            )
            ->firstOrFail();

        $currentQuestion = $publicQuestion->question;

        $option = QuestionOption::query()
            ->where(
                'id',
                $validated['question_option_id']
            )
            ->where(
                'question_id',
                $currentQuestion->id
            )
            ->first();

        abort_unless(
            $option,
            422,
            'The selected answer is not valid for this question.'
        );

        /*
         * Save or update the answer.
         */
        PublicTestAnswer::updateOrCreate(
            [
                'public_test_attempt_id' =>
                    $attempt->id,

                'question_id' =>
                    $currentQuestion->id,
            ],
            [
                'question_option_id' =>
                    $option->id,

                'is_correct' =>
                    (bool) $option->is_correct,
            ]
        );

        /*
         * Recalculate answered count from the database.
         */
        $answeredCount = $attempt
            ->answers()
            ->count();

        /*
         * Do not move the user's saved position backwards.
         */
        $nextPosition = min(
            $question + 1,
            $total
        );

        $currentPosition = max(
            (int) $attempt->current_position,
            $nextPosition
        );

        $attempt->update([
            'answered_questions' =>
                $answeredCount,

            'current_position' =>
                $currentPosition,
        ]);

        /*
         * Last question goes to review.
         */
        if ($question >= $total) {
            return redirect()->route(
                'public-test.review',
                $attempt
            );
        }

        return redirect()->route(
            'public-test.question',
            [
                'attempt' =>
                    $attempt->id,

                'question' =>
                    $nextPosition,
            ]
        );
    }

    /**
     * Review all questions.
     */
    public function review(
        PublicTestAttempt $attempt
    ) {
        $this->authoriseAttempt($attempt);

        abort_unless(
            $attempt->status === 'in_progress',
            403
        );

        $questions = $attempt
            ->questions()
            ->with([
                'question.options',
                'question.category',
            ])
            ->get();

        $answers = $attempt
            ->answers()
            ->with('option')
            ->get()
            ->keyBy('question_id');

        return view(
            'public-test.review',
            compact(
                'attempt',
                'questions',
                'answers'
            )
        );
    }

    /**
     * Submit test.
     *
     * Unanswered questions are allowed.
     */
    public function submit(
        PublicTestAttempt $attempt
    ) {
        $this->authoriseAttempt($attempt);

        if ($attempt->status === 'completed') {
            return redirect()->route(
                'public-test.result',
                $attempt
            );
        }

        abort_unless(
            $attempt->status === 'in_progress',
            403
        );

        DB::transaction(
            function () use ($attempt) {
                $this->completeAttempt($attempt);
            }
        );

        return redirect()->route(
            'public-test.result',
            $attempt
        );
    }

    /**
     * Result.
     */
    public function result(
        PublicTestAttempt $attempt
    ) {
        $this->authoriseAttempt($attempt);

        abort_unless(
            in_array(
                $attempt->status,
                [
                    'completed',
                    'expired',
                ],
                true
            ),
            403
        );

        $attempt->load([
            'questions.question.category',
            'answers.question.category',
            'answers.option',
            'feedback',
            'learningPlans',
        ]);

        $categoryResults = [];

        foreach ($attempt->questions as $publicQuestion) {
            $category =
                $publicQuestion->question?->category;

            if (!$category) {
                continue;
            }

            $categoryId = $category->id;

            if (!isset($categoryResults[$categoryId])) {
                $categoryResults[$categoryId] = [
                    'category' =>
                        $category,

                    'total' =>
                        0,

                    'correct' =>
                        0,
                ];
            }

            $categoryResults[$categoryId]['total']++;

            $answer = $attempt
                ->answers
                ->firstWhere(
                    'question_id',
                    $publicQuestion->question_id
                );

            if (
                $answer &&
                $answer->is_correct
            ) {
                $categoryResults[$categoryId]['correct']++;
            }
        }

        foreach ($categoryResults as &$result) {
            $result['percentage'] =
                $result['total'] > 0
                    ? round(
                        (
                            $result['correct'] /
                            $result['total']
                        ) * 100,
                        1
                    )
                    : 0;
        }

        unset($result);

        /*
         * Create a public-test learning plan once.
         */
        if (
            $attempt
                ->learningPlans()
                ->doesntExist()
        ) {
            $this->createLearningPlan(
                $attempt
            );

            $attempt->load('learningPlans');
        }

        return view(
            'public-test.result',
            [
                'attempt' =>
                    $attempt,

                'categoryResults' =>
                    $categoryResults,
            ]
        );
    }

    /**
     * Feedback page.
     */
    public function feedback(
        PublicTestAttempt $attempt
    ) {
        $this->authoriseAttempt($attempt);

        abort_unless(
            in_array(
                $attempt->status,
                [
                    'completed',
                    'expired',
                ],
                true
            ),
            403
        );

        $feedback = $attempt->feedback;

        return view(
            'public-test.feedback',
            compact(
                'attempt',
                'feedback'
            )
        );
    }

    /**
     * Store feedback.
     */
    public function storeFeedback(
        Request $request,
        PublicTestAttempt $attempt
    ) {
        $this->authoriseAttempt($attempt);

        abort_unless(
            in_array(
                $attempt->status,
                [
                    'completed',
                    'expired',
                ],
                true
            ),
            403
        );

        $validated = $request->validate([
            'organization_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'awareness_test_frequency' => [
                'required',
                'string',
                'max:100',
            ],

            'recommended_for' => [
                'required',
                'array',
                'min:1',
            ],

            'recommended_for.*' => [
                'string',
                'max:100',
            ],

            'organization_size' => [
                'required',
                'string',
                'max:100',
            ],

            'awareness_impact' => [
                'required',
                'string',
                'max:150',
            ],

            'rating' => [
                'required',
                'integer',
                'between:1,5',
            ],

            'comments' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'improvement_suggestions' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        PublicTestFeedback::updateOrCreate(
            [
                'public_test_attempt_id' =>
                    $attempt->id,
            ],
            [
                'public_test_user_id' =>
                    auth('public_test')->id(),

                'organization_name' =>
                    $validated['organization_name']
                    ?? null,

                'awareness_test_frequency' =>
                    $validated['awareness_test_frequency'],

                'recommended_for' =>
                    $validated['recommended_for'],

                'organization_size' =>
                    $validated['organization_size'],

                'awareness_impact' =>
                    $validated['awareness_impact'],

                'rating' =>
                    $validated['rating'],

                'comments' =>
                    $validated['comments']
                    ?? null,

                'improvement_suggestions' =>
                    $validated['improvement_suggestions']
                    ?? null,
            ]
        );

        return redirect()
            ->route(
                'public-test.result',
                $attempt
            )
            ->with(
                'success',
                'Thank you. Your feedback has been submitted successfully.'
            );
    }

    /**
     * Calculate and permanently store result.
     */
    protected function completeAttempt(
        PublicTestAttempt $attempt
    ): void {
        $answers = $attempt
            ->answers()
            ->get();

        $answered = $answers->count();

        $correct = $answers
            ->where(
                'is_correct',
                true
            )
            ->count();

        $total = (int) $attempt->total_questions;

        $score = $total > 0
            ? round(
                (
                    $correct /
                    $total
                ) * 100,
                2
            )
            : 0;

        $risk = match (true) {
            $score >= 80 => 'Low',
            $score >= 60 => 'Medium',
            default => 'High',
        };

        $attempt->update([
            'status' =>
                'completed',

            'answered_questions' =>
                $answered,

            'correct_answers' =>
                $correct,

            'score_percentage' =>
                $score,

            'risk_level' =>
                $risk,

            'completed_at' =>
                now(),

            'current_position' =>
                $total,
        ]);
    }

    /**
     * Create persistent public-test learning plan.
     */
    protected function createLearningPlan(
        PublicTestAttempt $attempt
    ): void {
        $score =
            (float) $attempt->score_percentage;

        $priority = match (true) {
            $score >= 80 => 'low',
            $score >= 60 => 'medium',
            default => 'high',
        };

        PublicTestLearningPlan::create([
            'public_test_user_id' =>
                $attempt->public_test_user_id,

            'public_test_attempt_id' =>
                $attempt->id,

            'title' =>
                'Cybersecurity Awareness Improvement Plan',

            'description' =>
                'Review the cybersecurity areas covered by your assessment, strengthen weaker areas and continue regular security-awareness practice.',

            'priority' =>
                $priority,

            'status' =>
                'not_started',

            'progress_percentage' =>
                0,

            'due_date' =>
                now()->addDays(30),

            'completed_at' =>
                null,
        ]);
    }

    /**
     * Make sure the attempt belongs to the public-test user.
     */
    protected function authoriseAttempt(
        PublicTestAttempt $attempt
    ): void {
        $user = auth('public_test')->user();

        abort_unless(
            $user &&
            (int) $attempt->public_test_user_id ===
                (int) $user->id,
            403
        );
    }
}
