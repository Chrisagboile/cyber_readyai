<?php

namespace App\Modules\PensionAccountGuard\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\PensionAccountGuard\Models\PensionGuardThreatRule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PensionGuardThreatController extends Controller
{
    /**
     * Display the educational pension scam checker.
     */
    public function create()
    {
        return $this->renderChecker();
    }

    /**
     * Validate selections and display an educational result.
     *
     * The selected answers are not saved to the database.
     */
    public function store(Request $request)
    {
        $scenarios = $this->scenarios();
        $warningSigns = $this->warningSigns();

        $validated = $request->validate([
            'scenario' => [
                'required',
                'string',
                Rule::in(array_keys($scenarios)),
            ],

            'warning_signs' => [
                'nullable',
                'array',
                'max:8',
            ],

            'warning_signs.*' => [
                'required',
                'string',
                'distinct',
                Rule::in(array_keys($warningSigns)),
            ],
        ]);

        $selectedSigns = array_values(
            $validated['warning_signs'] ?? []
        );

        $matchedSigns = [];

        foreach ($selectedSigns as $key) {
            if (isset($warningSigns[$key])) {
                $matchedSigns[$key] = $warningSigns[$key];
            }
        }

        $count = count($matchedSigns);

        if ($count >= 3) {
            $level = 'Multiple warning signs';

            $message = 'Several warning signs have been selected. Do not proceed until you have independently verified the offer or contact.';
        } elseif ($count >= 1) {
            $level = 'Warning signs identified';

            $message = 'One or more warning signs have been selected. Pause and verify the situation independently before taking action.';
        } else {
            $level = 'No listed warning signs selected';

            $message = 'Your answers did not identify any of the warning signs listed here. This does not mean the offer or contact is genuine or safe.';
        }

        $result = [
            'scenario' => $validated['scenario'],
            'warning_signs' => $selectedSigns,
            'level' => $level,
            'message' => $message,
            'matched_signs' => $matchedSigns,
        ];

        return view('pension-guard.threat-checker', [
            'scenarios' => $scenarios,
            'warningSigns' => $warningSigns,
            'result' => $result,
        ]);
    }

    /**
     * Render the checker with a supplied result or no result.
     */
    private function renderChecker(?array $result = null)
    {
        return view('pension-guard.threat-checker', [
            'scenarios' => $this->scenarios(),
            'warningSigns' => $this->warningSigns(),
            'result' => $result,
        ]);
    }

    /**
     * Retrieve active warning rules from the database.
     */
    private function warningSigns(): array
    {
        return PensionGuardThreatRule::query()
            ->where('is_active', true)
            ->orderBy('id')
            ->get()
            ->mapWithKeys(function (PensionGuardThreatRule $rule) {
                return [
                    $rule->rule_key => [
                        'title' => $rule->title,
                        'description' => $rule->description,
                        'severity' => $rule->severity,
                        'recommended_action' => $rule->recommended_action,
                        'official_source_url' => $rule->official_source_url,
                    ],
                ];
            })
            ->all();
    }

    /**
     * Supported pension-related scenarios.
     */
    private function scenarios(): array
    {
        return [
            'unexpected_contact' =>
                'An unexpected pension call, email or message',

            'investment_offer' =>
                'A pension investment or transfer offer',

            'transfer_request' =>
                'A request to transfer or release pension money',

            'provider_change' =>
                'A message claiming to be from my pension provider',

            'other' =>
                'Another pension-related situation',
        ];
    }
}
