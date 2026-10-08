@extends('layouts.public-test')

@section('title', 'My Assessments')

@section('content')


<div style="max-width:1100px;margin:0px auto;padding:0 20px 60px;">

    <h1>
        My Learning
    </h1>

    <p style="color:#6b7280;">
        Learning plans generated from your public cybersecurity assessments.
    </p>

    @forelse ($learningPlans as $plan)

        <div style="
            margin-top:20px;
            background:#fff;
            border:1px solid #e5e7eb;
            border-radius:16px;
            padding:25px;
        ">

            <div style="
                display:flex;
                justify-content:space-between;
                gap:15px;
                flex-wrap:wrap;
            ">

                <h2 style="margin:0;">
                    {{ $plan->title }}
                </h2>

                <strong>
                    {{ ucfirst($plan->priority) }} Priority
                </strong>

            </div>

            <p style="color:#6b7280;line-height:1.6;">
                {{ $plan->description }}
            </p>

            <div style="
                height:9px;
                background:#e5e7eb;
                border-radius:999px;
                overflow:hidden;
            ">

                <div style="
                    width:{{ min(
                        100,
                        max(
                            0,
                            $plan->progress_percentage
                        )
                    ) }}%;
                    height:100%;
                    background:#111827;
                "></div>

            </div>

            <div style="
                margin-top:10px;
                color:#6b7280;
            ">

                Progress:
                {{ $plan->progress_percentage }}%

                &nbsp; | &nbsp;

                Status:
                {{ ucfirst(
                    str_replace(
                        '_',
                        ' ',
                        $plan->status
                    )
                ) }}

            </div>

        </div>

    @empty

        <div style="
            margin-top:25px;
            background:#fff;
            border:1px solid #e5e7eb;
            border-radius:16px;
            padding:25px;
        ">

            <p>
                No learning plans have been generated yet.
            </p>

        </div>

    @endforelse

    <div style="margin-top:20px;">
        {{ $learningPlans->links() }}
    </div>

</div>

@endsection
