@extends('pension-guard.layout')

@section('title', 'Audit Log | Pension Account Guard')

@section('content')
<div class="container">
    <h1>Pension Account Guard — Audit Log</h1>

    <p>
        Review recorded administrative activity. This page is restricted
        to authorised Pension Account Guard administrators.
    </p>

    <form method="GET"
          action="{{ route('pension-guard.admin.audit.index') }}"
          class="mb-4">
        <div class="mb-3">
            <label for="action">Filter by action</label>
            <input
                type="text"
                id="action"
                name="action"
                value="{{ $actionFilter }}"
                maxlength="100"
                class="form-control"
                placeholder="e.g. official_source.created"
            >
        </div>

        <div class="mb-3">
            <label for="actor_id">Filter by actor ID</label>
            <input
                type="number"
                id="actor_id"
                name="actor_id"
                value="{{ $actorFilter }}"
                min="1"
                class="form-control"
            >
        </div>

        <button type="submit" class="btn btn-primary">Filter</button>
        <a
            href="{{ route('pension-guard.admin.audit.index') }}"
            class="btn btn-secondary"
        >Clear</a>
    </form>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Date and time</th>
                    <th>Actor ID</th>
                    <th>Action</th>
                    <th>Target</th>
                    <th>Metadata</th>
                    <th>IP address</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $log)
                    <tr>
                        <td>
                            {{ $log->created_at?->format('Y-m-d H:i:s') ?? '—' }}
                        </td>
                        <td>{{ $log->actor_id ?? 'System/guest' }}</td>
                        <td>{{ $log->action }}</td>
                        <td>
                            {{ $log->target_type ?? '—' }}
                            @if ($log->target_id)
                                #{{ $log->target_id }}
                            @endif
                        </td>
                        <td>
                            <pre class="mb-0 text-wrap">{{ json_encode($log->metadata ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                        </td>
                        <td>{{ $log->ip_address ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No audit events found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $logs->links() }}
</div>
@endsection