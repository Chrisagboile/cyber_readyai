<?php

namespace App\Services;


use App\Models\AiAssessmentInsight;
use App\Models\AssessmentAttempt;


class AiAssessmentInsightService
{
    public function buildAssessmentContext(
        AssessmentAttempt $attempt
    ): array {
        $attempt->load([
            'assessment',
            'answers.question.category',
            'answers.option',
        ]);

        $categoryResults = [];

        foreach ($attempt->answers as $answer) {
            $category = $answer->question?->category;

            if (! $category) {
                continue;
            }

            $categoryId = $category->id;

            if (! isset($categoryResults[$categoryId])) {
                $categoryResults[$categoryId] = [
                    'category' => $category->name,
                    'total' => 0,
                    'correct' => 0,
                ];
            }

            $categoryResults[$categoryId]['total']++;

            if ($answer->option?->is_correct) {
                $categoryResults[$categoryId]['correct']++;
            }
        }

        $categories = [];

        foreach ($categoryResults as $result) {
            $percentage = $result['total'] > 0
                ? round(
                    ($result['correct'] / $result['total']) * 100,
                    2
                )
                : 0;

            $categories[] = [
                'category' => $result['category'],
                'score' => $percentage,
                'questions' => $result['total'],
            ];
        }

        return [
            'assessment' => [
                'name' => $attempt->assessment?->name,
            ],

            'overall' => [
                'score' => (float) $attempt->score_percentage,
                'risk_level' => $attempt->risk_level,
                'total_questions' => $attempt->total_questions,
                'answered_questions' => $attempt->answered_questions,
                'correct_answers' => $attempt->correct_answers,
            ],

            'categories' => $categories,
        ];
    }

    public function generate(
        AssessmentAttempt $attempt
    ): AiAssessmentInsight {
        $context = $this->buildAssessmentContext($attempt);

        $categories = collect($context['categories']);

        $strengths = $categories
            ->filter(fn ($category) => $category['score'] >= 80)
            ->sortByDesc('score')
            ->values()
            ->map(fn ($category) => $category['category'])
            ->all();

        $priorityAreas = $categories
            ->filter(fn ($category) => $category['score'] < 60)
            ->sortBy('score')
            ->values()
            ->map(fn ($category) => [
                'category' => $category['category'],
                'score' => $category['score'],
                'priority' => $category['score'] < 40
                    ? 'high'
                    : 'medium',
            ])
            ->all();

        $summary = match ($attempt->risk_level) {
            'Low' =>
                'Your cybersecurity readiness is strong. '
                . 'Continue reinforcing your existing security habits.',

            'Medium' =>
                'Your cybersecurity readiness is moderate. '
                . 'There are specific areas where targeted learning '
                . 'can improve your overall readiness.',

            default =>
                'Your cybersecurity readiness requires improvement. '
                . 'We recommend focusing on the highest-risk areas '
                . 'identified in this assessment.',
        };

        $recommendations = collect($priorityAreas)
            ->map(function ($area) {
                return [
                    'title' => 'Improve ' . $area['category'],
                    'reason' =>
                        'Your score in this area was '
                        . $area['score']
                        . '%.',
                    'priority' => $area['priority'],
                ];
            })
            ->all();

                return AiAssessmentInsight::updateOrCreate(
            [
                'assessment_attempt_id' => $attempt->id,
            ],
            [
                'summary' => $summary,
                'strengths' => $strengths,
                'priority_areas' => $priorityAreas,
                'recommendations' => $recommendations,
                'model' => 'rule-based-development',
                'prompt_version' => '1.0',
                'generated_at' => now(),
            ]
        );
    }
}
