<?php

namespace App\Modules\PensionAccountGuard\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\PensionAccountGuard\Models\PensionGuardAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PensionGuardAssessmentController extends Controller
{
    /**
     * Security questions used to calculate the educational assessment score.
     *
     * Higher answer values indicate more security actions to review.
     */
    private function questions(): array
    {
        return [
            'mfa' => [
                'question' => 'Do you use multi-factor authentication where available?',
                'options' => [
                    0 => 'Yes, on relevant accounts',
                    1 => 'On some accounts or unsure',
                    2 => 'No',
                ],
            ],

            'passwords' => [
                'question' => 'Do you use unique passwords for your important accounts?',
                'options' => [
                    0 => 'Yes',
                    1 => 'Sometimes or unsure',
                    2 => 'I reuse passwords',
                ],
            ],

            'verification' => [
                'question' => 'How do you verify unexpected pension-related messages?',
                'options' => [
                    0 => 'I independently verify the contact details',
                    1 => 'I sometimes verify them',
                    2 => 'I usually rely on the message',
                ],
            ],

            'pressure' => [
                'question' => 'What do you do when an offer or call pressures you to act quickly?',
                'options' => [
                    0 => 'Pause and verify independently',
                    1 => 'I may respond before checking',
                    2 => 'I usually follow the instructions',
                ],
            ],

            'review' => [
                'question' => 'Do you regularly review security and recovery settings?',
                'options' => [
                    0 => 'Yes',
                    1 => 'Occasionally or unsure',
                    2 => 'Rarely or never',
                ],
            ],
        ];
    }

    /**
     * Age groups collected for product research.
     *
     * These responses do not contribute to the security score.
     */
    private function ageGroups(): array
    {
        return [
            '50-54' => '50–54',
            '55-59' => '55–59',
            '60-64' => '60–64',
            '65-69' => '65–69',
            '70-74' => '70–74',
            '75-79' => '75–79',
            '80-plus' => '80 or over',
            'prefer-not-to-say' => 'Prefer not to say',
        ];
    }

    /**
     * Monthly payment interest collected for product research.
     */
    private function paymentOptions(): array
    {
        return [
            'yes' => 'Yes, I would consider it',
            'maybe' => 'Maybe — I would like to know more first',
            'no' => 'No, I would prefer a free service',
            'unsure' => "I'm not sure yet",
        ];
    }

    /**
     * Display the assessment form.
     */
    public function create()
    {
        return view('pension-guard.assessments.create', [
            'questions' => $this->questions(),
            'ageGroups' => $this->ageGroups(),
            'paymentOptions' => $this->paymentOptions(),
        ]);
    }

    /**
     * Validate answers, calculate the score and save or return the result.
     */
    public function store(Request $request)
    {
        $questions = $this->questions();
        $rules = [];

        foreach ($questions as $key => $question) {
            $rules["answers.{$key}"] = [
                'required',
                'integer',
                Rule::in(array_keys($question['options'])),
            ];
        }

        // Require exactly the five expected security-answer keys.
        $rules['answers'] = [
            'required',
            'array',
            Rule::in([]),
        ];

        // The answers array is validated separately below to permit
        // the five expected keys and reject unexpected answer fields.
        unset($rules['answers']);

        $rules['age_group'] = [
            'required',
            'string',
            Rule::in(array_keys($this->ageGroups())),
        ];

        $rules['monthly_payment_interest'] = [
            'required',
            'string',
            Rule::in(array_keys($this->paymentOptions())),
        ];

        $validated = $request->validate($rules);

        $submittedAnswers = $validated['answers'];

        // Reject missing or unexpected security-answer fields.
        if (
            count($submittedAnswers) !== count($questions)
            || array_diff(array_keys($submittedAnswers), array_keys($questions))
        ) {
            return back()
                ->withErrors([
                    'answers' => 'Please answer all five security questions.',
                ])
                ->withInput();
        }

        // Only the five security answers contribute to the score.
        $answers = [];

        foreach ($questions as $key => $question) {
            $answers[$key] = (int) $submittedAnswers[$key];
        }

        $score = array_sum($answers);

        $riskBand = match (true) {
            $score <= 3 => 'Lower',
            $score <= 6 => 'Moderate',
            default => 'Higher',
        };

        // Store product research separately from the scored answers.
        $research = [
            'age_group' => $validated['age_group'],
            'monthly_payment_interest' =>
                $validated['monthly_payment_interest'],
        ];

        $result = [
            'answers' => [
                ...$answers,
                '_research' => $research,
            ],
            'score' => $score,
            'risk_band' => $riskBand,
        ];

        if (Auth::guard('pension_guard')->check()) {
            $assessment = PensionGuardAssessment::create([
                'user_id' => Auth::guard('pension_guard')->id(),
                'answers' => $result['answers'],
                'score' => $score,
                'risk_band' => $riskBand,
            ]);

            // Clear any previous public-demo result after saving a
            // signed-in assessment, so old guest data is not displayed.
            $request->session()->forget('pension_guard.demo_result');

            return redirect()->route(
                'pension-guard.assessments.show',
                ['assessment' => $assessment->id]
            );
        }

        // Store guest results in the session only.
        $request->session()->put(
            'pension_guard.demo_result',
            $result
        );

        return redirect()->route(
            'pension-guard.assessments.result'
        );
    }

    /**
     * Display the current guest-demo result.
     */
    public function result(Request $request)
    {
        // A signed-in user should view saved assessments through show().
        if (Auth::guard('pension_guard')->check()) {
            return redirect()->route(
                'pension-guard.assessments.history'
            );
        }

        $result = $request->session()->get(
            'pension_guard.demo_result'
        );

        if (! is_array($result)) {
            return redirect()->route(
                'pension-guard.assessments.create'
            );
        }

        return view('pension-guard.assessments.result', [
            'assessment' => $result,
            'questions' => $this->questions(),
            'ageGroups' => $this->ageGroups(),
            'paymentOptions' => $this->paymentOptions(),
            'saved' => false,
        ]);
    }

    /**
     * Display the authenticated user's assessment history.
     */
    public function history()
    {
        $userId = Auth::guard('pension_guard')->id();

        abort_unless($userId, 403);

        $assessments = PensionGuardAssessment::query()
            ->where('user_id', $userId)
            ->latest()
            ->paginate(10);

        return view(
            'pension-guard.assessments.history',
            compact('assessments')
        );
    }

    /**
     * Display one saved assessment, belonging only to its owner.
     */
    public function show(PensionGuardAssessment $assessment)
    {
        abort_unless(
            Auth::guard('pension_guard')->check()
            && (int) $assessment->user_id
                === (int) Auth::guard('pension_guard')->id(),
            404
        );

        return view('pension-guard.assessments.result', [
            'assessment' => $assessment,
            'questions' => $this->questions(),
            'ageGroups' => $this->ageGroups(),
            'paymentOptions' => $this->paymentOptions(),
            'saved' => true,
        ]);
    }
}
