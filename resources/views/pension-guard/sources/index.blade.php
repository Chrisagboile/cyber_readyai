@extends('pension-guard.layout')

@section('title', 'Official Pension Guidance Sources')

@section('content')
<section class="panel">
    <h2>Official pension guidance sources</h2>

    <p>
        This register lists external sources of pension, scam-prevention
        and cybersecurity guidance used by Pension Account Guard.
    </p>

    <p>
        These links provide information to help you carry out your own
        checks. Listing a source does not mean that Pension Account Guard
        has verified a particular pension offer, caller or message.
    </p>
</section>

<section class="panel">
    <h3>Source register</h3>

    @if ($sources->isEmpty())
        <p>
            No active sources are currently listed. Please check again later.
        </p>
    @else
        @foreach ($sources as $source)
            @php
                $overdue = $source->next_review_due
                    && $source->next_review_due->toDateString() < $today;

                $notReviewed = !$source->last_reviewed_at;
            @endphp

            <article class="panel">
                <h3>{{ $source->name }}</h3>

                <p>{{ $source->purpose }}</p>

                <p>
                    <strong>Category:</strong>
                    {{ ucwords(str_replace('_', ' ', $source->source_type)) }}
                </p>

                <p>
                    <a href="{{ $source->url }}"
                       target="_blank"
                       rel="noopener noreferrer">
                        Visit official source
                    </a>
                </p>

                <p>
                    <strong>Last reviewed:</strong>
                    @if ($source->last_reviewed_at)
                        {{ $source->last_reviewed_at->format('d M Y') }}
                    @else
                        Not yet recorded
                    @endif
                </p>

                <p>
                    <strong>Next review due:</strong>
                    @if ($source->next_review_due)
                        {{ $source->next_review_due->format('d M Y') }}
                    @else
                        Not scheduled
                    @endif
                </p>

                <p>
                    <strong>Review status:</strong>

                    @if ($overdue)
                        <span>Review overdue</span>
                    @elseif ($notReviewed)
                        <span>Review not recorded</span>
                    @elseif (!$source->next_review_due)
                        <span>Review date not scheduled</span>
                    @else
                        <span>Review date has not passed</span>
                    @endif
                </p>
            </article>
        @endforeach
    @endif
</section>

<section class="panel">
    <p>
        <strong>Important:</strong>
        Review status reflects the dates recorded in this register.
        It does not automatically confirm that a source's website,
        guidance or links have been checked recently.
    </p>
</section>
@endsection
