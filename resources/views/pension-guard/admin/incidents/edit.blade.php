@extends('pension-guard.layout')

@section('title', 'Edit Security Incident | Pension Account Guard')

@section('content')
<div style="max-width:900px; margin:0 auto; padding:24px;">
    <p>
        <a href="{{ route('pension-guard.admin.incidents.index') }}">
            &larr; Back to incident register
        </a>
    </p>

    <h1>Edit security incident</h1>

    <p>
        Update the record and review status using the available source evidence.
    </p>

    <form method="POST"
          action="{{ route('pension-guard.admin.incidents.update', $incident) }}">
        @csrf
        @method('PUT')

        @php($buttonText = 'Update incident')

        @include('pension-guard.admin.incidents._form')
    </form>
</div>
@endsection