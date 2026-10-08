@extends('layouts.public-test')

@section('title', 'My Assessments')

@section('content')

<div style="max-width:1100px;margin:0px auto;padding:0 20px 60px;">

    <h1>
        My Assessments
    </h1>

    <p style="color:#6b7280;">
        Your public-test assessment history is stored independently
        from the main CyberReadyAI employee assessment system.
    </p>

    <div style="
        margin-top:25px;
        background:#fff;
        border:1px solid #e5e7eb;
        border-radius:16px;
        padding:20px;
    ">

        @forelse ($attempts as $attempt)

            <div style="
                padding:18px 0;
                border-bottom:1px solid #e5e7eb;
                display:flex;
                justify-content:space-between;
                gap:15px;
                align-items:center;
            ">

                <div>

                    <strong>
                        Assessment #{{ $attempt->id }}
                    </strong>

                    <div style="
                        color:#6b7280;
                        margin-top:5px;
                    ">
                        {{ $attempt->created_at?->format('d M Y H:i') }}
                    </div>

                </div>

                <div>

                    <strong>
                        {{ number_format(
                            (float) $attempt->score_percentage,
                            1
                        ) }}%
                    </strong>

                    <span style="
                        margin-left:12px;
                        color:#6b7280;
                    ">
                        {{ ucfirst(
                            str_replace(
                                '_',
                                ' ',
                                $attempt->status
                            )
                        ) }}
                    </span>

                    @if (
                        in_array(
                            $attempt->status,
                            ['completed', 'expired'],
                            true
                        )
                    )

                        <a
                            href="{{ route(
                                'public-test.result',
                                $attempt
                            ) }}"
                            style="
                                margin-left:15px;
                                color:#4f46e5;
                                font-weight:700;
                                text-decoration:none;
                            "
                        >
                            Result
                        </a>

                    @else

                        <a
                            href="{{ route('public-test.question', [
                                'attempt' => $attempt->id,
                                'question' => max(
                                    1,
                                    $attempt->current_position
                                ),
                            ]) }}"
                            style="
                                margin-left:15px;
                                color:#4f46e5;
                                font-weight:700;
                                text-decoration:none;
                            "
                        >
                            Continue
                        </a>

                    @endif

                </div>

            </div>

        @empty

            <p>
                No assessments have been taken yet.
            </p>

        @endforelse

    </div>

    <div style="margin-top:20px;">
        {{ $attempts->links() }}
    </div>

</div>

@endsection
