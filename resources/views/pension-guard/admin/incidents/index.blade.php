@extends('pension-guard.layout')

@section('title', 'Security Incident Register | Pension Account Guard')

@section('content')
<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 24px;">
    <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap;">
        <div>
            <h1>Security Incident Register</h1>
            <p>Record and review reported security incidents relevant to pension protection.</p>
        </div>

        <a href="{{ route('pension-guard.admin.incidents.create') }}"
           style="display:inline-block; background:#174ea6; color:#fff; padding:10px 16px; border-radius:6px; text-decoration:none;">
            Record incident
        </a>
    </div>

    @if(session('success'))
        <div role="status" style="background:#e7f6ec; color:#176534; padding:12px; margin:16px 0; border-radius:6px;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div role="alert" style="background:#fdecec; color:#8b1e1e; padding:12px; margin:16px 0; border-radius:6px;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="GET"
          action="{{ route('pension-guard.admin.incidents.index') }}"
          style="display:flex; gap:12px; flex-wrap:wrap; margin:24px 0;">

        <input
            type="search"
            name="search"
            value="{{ $searchFilter }}"
            placeholder="Search title or organisation"
            aria-label="Search title or organisation"
            style="padding:10px; min-width:240px; border:1px solid #ccd5e0; border-radius:6px;"
        >

        <select
            name="status"
            aria-label="Filter by status"
            style="padding:10px; border:1px solid #ccd5e0; border-radius:6px;"
        >
            <option value="">All statuses</option>

            @foreach($statuses as $status)
                <option value="{{ $status }}" @selected($statusFilter === $status)>
                    {{ ucwords(str_replace('_', ' ', $status)) }}
                </option>
            @endforeach
        </select>

        <button
            type="submit"
            style="padding:10px 16px; border:0; border-radius:6px; background:#174ea6; color:#fff;"
        >
            Filter
        </button>

        <a href="{{ route('pension-guard.admin.incidents.index') }}"
           style="padding:10px;">
            Clear
        </a>
    </form>

    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; background:#fff;">
            <thead>
                <tr style="background:#edf3fa; text-align:left;">
                    <th style="padding:12px;">Incident</th>
                    <th style="padding:12px;">Organisation</th>
                    <th style="padding:12px;">Reported date</th>
                    <th style="padding:12px;">Status</th>
                    <th style="padding:12px;">Source</th>
                    <th style="padding:12px;">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($incidents as $incident)
                    <tr style="border-bottom:1px solid #e2e8f0;">
                        <td style="padding:12px;">
                            {{ $incident->title }}
                        </td>

                        <td style="padding:12px;">
                            {{ $incident->affected_organisation ?: 'Not specified' }}
                        </td>

                        <td style="padding:12px;">
                            {{ $incident->reported_at?->format('d M Y') ?? 'Not specified' }}
                        </td>

                        <td style="padding:12px;">
                            {{ ucwords(str_replace('_', ' ', $incident->status)) }}
                        </td>

                        <td style="padding:12px;">
                            @if($incident->source_url)
                                <a href="{{ $incident->source_url }}"
                                   target="_blank"
                                   rel="noopener noreferrer">
                                    {{ $incident->source_name ?: 'View source' }}
                                </a>
                            @else
                                {{ $incident->source_name ?: 'Not specified' }}
                            @endif
                        </td>

                        <td style="padding:12px;">
                            <a href="{{ route('pension-guard.admin.incidents.edit', $incident) }}">
                                Edit
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding:24px; text-align:center;">
                            No incidents found. You can record a reported incident using the button above.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:20px;">
        {{ $incidents->links() }}
    </div>

    <p style="margin-top:24px; color:#536273; font-size:14px;">
        Verification status describes the current review state of each record.
        An unverified report should not be presented as a confirmed security incident.
    </p>
</div>
@endsection