@extends('pension-guard.layout')

@section('title', 'Record Security Incident | Pension Account Guard')

@section('content')
<div style="max-width:900px; margin:0 auto; padding:24px;">
    <p>
        <a href="{{ route('pension-guard.admin.incidents.index') }}">
            &larr; Back to incident register
        </a>
    </p>

    <h1>Record security incident</h1>

    <p>
        Record a reported event with its source.
        Verify the report before describing it as confirmed.
    </p>

    <form method="POST"
          action="{{ route('pension-guard.admin.incidents.store') }}">
        @csrf

        @php($buttonText = 'Save incident')

        @include('pension-guard.admin.incidents._form')
    </form>
</div>
@endsection