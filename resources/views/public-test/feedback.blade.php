@extends('layouts.public-test')

@section('title', 'My Assessments')

@section('content')


<div style="
    max-width:750px;
    margin:0px auto;
    padding:0 20px 60px;
">

    <div style="
        background:#fff;
        border:1px solid #e5e7eb;
        border-radius:18px;
        padding:30px;
    ">

        <h1>
            Public Test Feedback
        </h1>

        <p style="color:#6b7280;">
            Help us improve the CyberReadyAI assessment.
        </p>

        @if ($errors->any())

            <div style="
                margin:20px 0;
                padding:14px;
                border-radius:9px;
                background:#fef2f2;
                color:#991b1b;
            ">

                <ul style="margin:0;">

                    @foreach ($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif

        <form
            method="POST"
            action="{{ route(
                'public-test.feedback.store',
                $attempt
            ) }}"
        >

            @csrf

            <div style="margin-bottom:18px;">

                <label>
                    Organisation Name
                </label>

                <input
                    type="text"
                    name="organization_name"
                    value="{{ old(
                        'organization_name',
                        $feedback?->organization_name
                    ) }}"
                    style="
                        width:100%;
                        box-sizing:border-box;
                        padding:12px;
                        margin-top:6px;
                    "
                >

            </div>

            <div style="margin-bottom:18px;">

                <label>
                    How often does your organisation conduct
                    cybersecurity awareness testing?
                </label>

                <select
                    name="awareness_test_frequency"
                    required
                    style="
                        width:100%;
                        padding:12px;
                        margin-top:6px;
                    "
                >

                    <option value="">
                        Select
                    </option>

                    @foreach ([
                        'Never',
                        'Annually',
                        'Twice a year',
                        'Quarterly',
                        'Monthly'
                    ] as $value)

                        <option
                            value="{{ $value }}"
                            {{ old(
                                'awareness_test_frequency',
                                $feedback?->awareness_test_frequency
                            ) === $value
                                ? 'selected'
                                : '' }}
                        >
                            {{ $value }}
                        </option>

                    @endforeach

                </select>

            </div>

            <div style="margin-bottom:18px;">

                <label>
                    Who would you recommend this test for?
                </label>

                @foreach ([
                    'Employees',
                    'Managers',
                    'IT Teams',
                    'Leadership',
                    'Students'
                ] as $value)

                    <label style="
                        display:block;
                        margin-top:8px;
                    ">

                        <input
                            type="checkbox"
                            name="recommended_for[]"
                            value="{{ $value }}"
                            {{ in_array(
                                $value,
                                old(
                                    'recommended_for',
                                    $feedback?->recommended_for ?? []
                                )
                            )
                                ? 'checked'
                                : '' }}
                        >

                        {{ $value }}

                    </label>

                @endforeach

            </div>

            <div style="margin-bottom:18px;">

                <label>
                    Organisation Size
                </label>

                <select
                    name="organization_size"
                    required
                    style="
                        width:100%;
                        padding:12px;
                        margin-top:6px;
                    "
                >

                    <option value="">
                        Select
                    </option>

                    @foreach ([
                        '1-10',
                        '11-50',
                        '51-250',
                        '251-1000',
                        '1000+'
                    ] as $value)

                        <option
                            value="{{ $value }}"
                            {{ old(
                                'organization_size',
                                $feedback?->organization_size
                            ) === $value
                                ? 'selected'
                                : '' }}
                        >
                            {{ $value }}
                        </option>

                    @endforeach

                </select>

            </div>

            <div style="margin-bottom:18px;">

                <label>
                    How would cybersecurity awareness testing
                    impact your organisation?
                </label>

                <select
                    name="awareness_impact"
                    required
                    style="
                        width:100%;
                        padding:12px;
                        margin-top:6px;
                    "
                >

                    <option value="">
                        Select
                    </option>

                    @foreach ([
                        'Very positively',
                        'Positively',
                        'Moderately',
                        'Slightly',
                        'Not at all'
                    ] as $value)

                        <option
                            value="{{ $value }}"
                            {{ old(
                                'awareness_impact',
                                $feedback?->awareness_impact
                            ) === $value
                                ? 'selected'
                                : '' }}
                        >
                            {{ $value }}
                        </option>

                    @endforeach

                </select>

            </div>

            <div style="margin-bottom:18px;">

                <label>
                    Rating
                </label>

                <select
                    name="rating"
                    required
                    style="
                        width:100%;
                        padding:12px;
                        margin-top:6px;
                    "
                >

                    <option value="">
                        Select rating
                    </option>

                    @for ($i = 1; $i <= 5; $i++)

                        <option
                            value="{{ $i }}"
                            {{ (int) old(
                                'rating',
                                $feedback?->rating
                            ) === $i
                                ? 'selected'
                                : '' }}
                        >
                            {{ $i }}
                            / 5
                        </option>

                    @endfor

                </select>

            </div>

            <div style="margin-bottom:18px;">

                <label>
                    Comments
                </label>

                <textarea
                    name="comments"
                    rows="5"
                    style="
                        width:100%;
                        box-sizing:border-box;
                        padding:12px;
                        margin-top:6px;
                    "
                >{{ old(
                    'comments',
                    $feedback?->comments
                ) }}</textarea>

            </div>

            <div style="margin-bottom:22px;">

                <label>
                    Improvement Suggestions
                </label>

                <textarea
                    name="improvement_suggestions"
                    rows="5"
                    style="
                        width:100%;
                        box-sizing:border-box;
                        padding:12px;
                        margin-top:6px;
                    "
                >{{ old(
                    'improvement_suggestions',
                    $feedback?->improvement_suggestions
                ) }}</textarea>

            </div>

            <button
                type="submit"
                style="
                    width:100%;
                    border:0;
                    border-radius:9px;
                    padding:14px;
                    background:#111827;
                    color:white;
                    font-weight:700;
                    cursor:pointer;
                "
            >
                Submit Feedback
            </button>

        </form>

    </div>

</div>

@endsection
