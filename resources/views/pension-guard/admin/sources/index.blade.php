@extends('pension-guard.layout')

@section('title', 'Manage Official Sources')

@section('content')
<section class="panel">
    <h2>Manage Official Guidance Sources</h2>
    <p>Manage the official websites and guidance referenced by Pension Account Guard.</p>

    <p>
        <a href="{{ route('pension-guard.admin.sources.create') }}">
            Add a new source
        </a>
    </p>

    @if (session('success'))
        <p role="status">{{ session('success') }}</p>
    @endif

    @if ($sources->isEmpty())
        <p>No sources have been added yet.</p>
    @else
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Last reviewed</th>
                        <th>Next review due</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sources as $source)
                        <tr>
                            <td>{{ $source->name }}</td>
                            <td>{{ ucwords(str_replace('_', ' ', $source->source_type)) }}</td>
                            <td>{{ $source->is_active ? 'Active' : 'Inactive' }}</td>
                            <td>
                                {{ $source->last_reviewed_at?->format('d M Y') ?? 'Not recorded' }}
                            </td>
                            <td>
                                {{ $source->next_review_due?->format('d M Y') ?? 'Not scheduled' }}
                            </td>
                            <td>
                                <a href="{{ route('pension-guard.admin.sources.edit', $source) }}">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $sources->links() }}
    @endif

    <p>
        <a href="{{ route('pension-guard.admin.index') }}">
            Back to admin dashboard
        </a>
    </p>
</section>
@endsection