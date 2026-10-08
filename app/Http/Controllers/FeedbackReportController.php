<?php

namespace App\Http\Controllers;
use App\Modules\PublicTest\Models\PublicTestFeedback;
use Illuminate\Http\Request;

class FeedbackReportController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(
            $request->user()->hasRole('super-admin'),
            403
        );

        $feedback = PublicTestFeedback::query()
            ->with([
                'user',
                'attempt',
            ])
            ->latest()
            ->paginate(20);

        return view(
            'feedback-reports.index',
            compact('feedback')
        );
    }

    public function show(
        Request $request,
        PublicTestFeedback $feedback
    ) {
        abort_unless(
            $request->user()->hasRole('super-admin'),
            403
        );

        $feedback->load([
            'user',
            'attempt.questions.question.category',
            'attempt.answers.question.category',
            'attempt.answers.option',
        ]);

        return view(
            'feedback-reports.show',
            compact('feedback')
        );
    }
}
