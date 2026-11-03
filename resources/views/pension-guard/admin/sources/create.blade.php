@extends('pension-guard.layout')

@section('title', 'Add Official Source')

@section('content')
<section class="panel">
    <h2>Add an Official Guidance Source</h2>

    <form method="POST" action="{{ route('pension-guard.admin.sources.store') }}">
        @csrf

        @include('pension-guard.admin.sources._form', [
            'buttonLabel' => 'Add source',
        ])
    </form>

    <p>
        <a href="{{ route('pension-guard.admin.sources.index') }}">
            Cancel and return to sources
        </a>
    </p>
</section>
@endsection