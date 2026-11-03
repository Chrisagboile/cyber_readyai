@extends('pension-guard.layouts.app')

@section('title', 'Add Trusted Contact - Pension Account Guard')

@section('content')
<div class="container">
    <h2>Add a Trusted Contact</h2>

    <p>
        Enter the details of someone you may want to notify about
        pension-security alerts.
    </p>

    <div class="alert" role="note">
        No alerts will be shared just by adding this person.
        Verification and your explicit consent will be required.
    </div>

    @if ($errors->any())
        <div class="alert" role="alert">
            <strong>Please correct the following:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ route('pension-guard.trusted-contacts.store') }}">
        @csrf

        <div class="mb-3">
            <label for="name">Full name</label>
            <input class="form-control"
                   type="text"
                   id="name"
                   name="name"
                   value="{{ old('name') }}"
                   maxlength="120"
                   autocomplete="name"
                   required>
        </div>

        <div class="mb-3">
            <label for="email">Email address</label>
            <input class="form-control"
                   type="email"
                   id="email"
                   name="email"
                   value="{{ old('email') }}"
                   maxlength="255"
                   autocomplete="email"
                   required>
        </div>

        <div class="mb-3">
            <label for="relationship">Relationship (optional)</label>
            <input class="form-control"
                   type="text"
                   id="relationship"
                   name="relationship"
                   value="{{ old('relationship') }}"
                   maxlength="80"
                   placeholder="For example, daughter, son or friend">
        </div>

        <button type="submit" class="btn btn-primary">
            Add contact
        </button>

        <a href="{{ route('pension-guard.trusted-contacts.index') }}"
           class="btn btn-secondary">
            Cancel
        </a>
    </form>
</div>
@endsection
