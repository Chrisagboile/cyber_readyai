@extends('layouts.app')

@section('title', 'Feedback Reports')

@section('content')

<div class="feedback-report-page">

    <div class="feedback-report-header">

        <div>
            <h1>Feedback Reports</h1>

            <p>
                Review feedback submitted after completed public
                cybersecurity awareness assessments.
            </p>
        </div>

    </div>


    <div class="feedback-report-card">

        <div class="feedback-report-card-header">

            <div>
                <h2>Submitted Feedback</h2>

                <p>
                    {{ $feedback->total() }} feedback submission(s)
                </p>
            </div>

        </div>


        @if($feedback->isEmpty())

            <div class="feedback-report-empty">
                No feedback submissions have been received yet.
            </div>

        @else

            <div class="feedback-report-table-wrap">

                <table class="feedback-report-table">

                    <thead>
                        <tr>
                            <th>Submitted</th>
                            <th>Respondent</th>
                            <th>Organisation</th>
                            <th>Score</th>
                            <th>Risk</th>
                            <th>Rating</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>

                    @foreach($feedback as $item)

                        @php
                            $attempt = $item->attempt;

                            $risk = strtolower(
                                (string) $attempt?->risk_level
                            );

                            $riskClass = match ($risk) {
                                'high' => 'high',
                                'medium' => 'medium',
                                'low' => 'low',
                                default => 'neutral',
                            };

                            $riskLabel = match ($risk) {
                                'high' => 'High',
                                'medium' => 'Medium',
                                'low' => 'Low',
                                default => 'N/A',
                            };
                        @endphp

                        <tr>

                            <td>
                                {{ $item->created_at?->format('d M Y') }}
                            </td>

                            <td>

                                <strong>
                                    {{ $item->user?->name ?? 'Unknown' }}
                                </strong>

                                @if($item->user?->email)
                                    <small>
                                        {{ $item->user->email }}
                                    </small>
                                @endif

                            </td>

                            <td>
                                {{ $item->organization_name ?: 'Not provided' }}
                            </td>

                            <td>

                                @if($attempt)

                                    <strong>
                                        {{ number_format((float) $attempt->score_percentage, 1) }}%
                                    </strong>

                                @else
                                    —
                                @endif

                            </td>

                            <td>

                                <span class="feedback-risk {{ $riskClass }}">
                                    {{ $riskLabel }}
                                </span>

                            </td>

                            <td>

                                <span class="feedback-rating">
                                    {{ $item->rating }}/5
                                </span>

                            </td>

                            <td class="feedback-report-action">

                                <a
                                    href="{{ route('feedback-reports.show', $item) }}"
                                >
                                    View Report
                                </a>

                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>


            @if($feedback->hasPages())

                <div class="feedback-report-pagination">
                    {{ $feedback->links() }}
                </div>

            @endif

        @endif

    </div>

</div>


<style>

.feedback-report-page {
    width: 100%;
    max-width: 1500px;
    margin: 0 auto;
    padding: 30px 32px 50px;
}

.feedback-report-header {
    margin-bottom: 25px;
}

.feedback-report-header h1 {
    margin: 0 0 7px;
    font-size: 30px;
    color: #172033;
}

.feedback-report-header p {
    margin: 0;
    font-size: 14px;
    color: #667085;
}

.feedback-report-card {
    overflow: hidden;
    border: 1px solid rgba(15,23,42,.08);
    border-radius: 16px;
    background: #fff;
    box-shadow: 0 6px 24px rgba(15,23,42,.05);
}

.feedback-report-card-header {
    padding: 22px 24px;
    border-bottom: 1px solid rgba(15,23,42,.08);
}

.feedback-report-card-header h2 {
    margin: 0 0 5px;
    font-size: 19px;
    color: #172033;
}

.feedback-report-card-header p {
    margin: 0;
    font-size: 12px;
    color: #667085;
}

.feedback-report-table-wrap {
    overflow-x: auto;
}

.feedback-report-table {
    width: 100%;
    min-width: 950px;
    border-collapse: collapse;
}

.feedback-report-table th {
    padding: 13px 18px;
    background: #f8f9fb;
    color: #667085;
    font-size: 10px;
    text-align: left;
    text-transform: uppercase;
    letter-spacing: .05em;
}

.feedback-report-table td {
    padding: 16px 18px;
    border-top: 1px solid #edf0f3;
    color: #344054;
    font-size: 12px;
}

.feedback-report-table td strong {
    display: block;
    color: #172033;
}

.feedback-report-table td small {
    display: block;
    margin-top: 3px;
    color: #98a2b3;
}

.feedback-risk,
.feedback-rating {
    display: inline-flex;
    padding: 5px 9px;
    border-radius: 999px;
    font-size: 10px;
    font-weight: 700;
}

.feedback-risk.high {
    background: #fde8e8;
    color: #b42318;
}

.feedback-risk.medium {
    background: #fff4d6;
    color: #956900;
}

.feedback-risk.low {
    background: #e6f6ee;
    color: #18794e;
}

.feedback-risk.neutral {
    background: #eef1f4;
    color: #667085;
}

.feedback-rating {
    background: #eef2ff;
    color: #3b4cca;
}

.feedback-report-action {
    text-align: right;
    white-space: nowrap;
}

.feedback-report-action a {
    font-size: 11px;
    font-weight: 700;
    text-decoration: none;
}

.feedback-report-empty {
    padding: 60px 24px;
    text-align: center;
    color: #667085;
    font-size: 13px;
}

.feedback-report-pagination {
    padding: 18px 24px;
    border-top: 1px solid #edf0f3;
}

</style>

@endsection
