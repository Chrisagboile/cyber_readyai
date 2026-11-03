@extends('pension-guard.layout')

@section('title', 'Edit Official Source')

@section('content')
<section class="panel">
    <h2>Edit Official Guidance Source</h2>

    <form
        method="POST"
        action="{{ route('pension-guard.admin.sources.update', $source) }}"
    >
        @csrf
        @method('PUT')

        @include('pension-guard.admin.sources._form', [
            'buttonLabel' => 'Save changes',
        ])
    </form>

    <p>
        <a href="{{ route('pension-guard.admin.sources.index') }}">
            Cancel and return to sources
        </a>
    </p>
</section>
@endsection