@extends('pension-guard.layout')

@section('title', 'Trusted Contacts | Pension Account Guard')

@section('content')
<div class="container">
    <h2>Trusted Contacts</h2>

    <p>
        Manage people you may choose to receive pension-security alerts.
    </p>

    <div class="alert" role="note">
        Adding a contact does not enable alerts or give them access to
        your pension information. Email verification and explicit,
        category-specific consent are required before alerts can be shared.
    </div>

    @if (session('success'))
        <div class="alert alert-success" role="status">
            {{ session('success') }}
        </div>
    @endif

    @if (session('warning'))
        <div class="alert alert-warning" role="status">
            {{ session('warning') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <strong>Please correct the following:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <p>
        <a href="{{ route('pension-guard.trusted-contacts.create') }}"
           class="btn btn-primary">
            Add trusted contact
        </a>
    </p>

    @if ($contacts->isEmpty())
        <p>You have not added any trusted contacts yet.</p>
    @else
        @foreach ($contacts as $contact)
            <section class="card mb-4 p-3"
                     aria-labelledby="contact-heading-{{ $contact->id }}">

                <h3 id="contact-heading-{{ $contact->id }}">
                    {{ $contact->name }}
                </h3>

                <p>
                    <strong>Email:</strong> {{ $contact->email }}<br>
                    <strong>Relationship:</strong>
                    {{ $contact->relationship ?: 'Not specified' }}
                </p>

                <p>
                    <strong>Email verification:</strong>
                    @if ($contact->isVerified())
                        <span>Verified</span>
                    @else
                        <span>Not verified</span>
                    @endif
                </p>

                <p>
                    <strong>Alert consent:</strong>
                    @if ($contact->hasActiveConsent())
                        <span>Active</span>
                    @elseif ($contact->consent_revoked_at)
                        <span>Revoked</span>
                    @else
                        <span>Not active</span>
                    @endif
                </p>

                @if (!$contact->isVerified())
                    <p>
                        This contact must verify their email address before
                        you can grant alert consent.
                    </p>

                    <form method="POST"
                          action="{{ route('pension-guard.trusted-contacts.resend-verification', $contact->id) }}"
                          class="mb-3">
                        @csrf

                        <button type="submit" class="btn btn-primary">
                            Resend verification email
                        </button>
                    </form>
                @else
                    <h4>Choose permitted alert categories</h4>

                    <p>
                        Select only the types of alerts this contact may
                        receive. Consent applies only to the categories
                        selected below.
                    </p>

                    <form method="POST"
                          action="{{ route('pension-guard.trusted-contacts.consent.update', $contact->id) }}">
                        @csrf
                        @method('PUT')

                        @php
                            $selectedCategories = old(
                                'alert_categories',
                                $contact->hasActiveConsent()
                                    ? ($contact->alert_categories ?? [])
                                    : []
                            );
                        @endphp

                        @foreach ($alertCategories as $key => $label)
                            <div class="form-check mb-2">
                                <input
                                    type="checkbox"
                                    class="form-check-input"
                                    id="contact-{{ $contact->id }}-{{ $key }}"
                                    name="alert_categories[]"
                                    value="{{ $key }}"
                                    @checked(in_array($key, $selectedCategories, true))
                                >

                                <label
                                    class="form-check-label"
                                    for="contact-{{ $contact->id }}-{{ $key }}">
                                    {{ $label }}
                                </label>
                            </div>
                        @endforeach

                        @error('alert_categories')
                            <p class="text-danger" role="alert">{{ $message }}</p>
                        @enderror

                        @error('confirm_consent')
                            <p class="text-danger" role="alert">{{ $message }}</p>
                        @enderror

                        <div class="form-check mt-3 mb-3">
                            <input
                                type="checkbox"
                                class="form-check-input"
                                id="consent-{{ $contact->id }}"
                                name="confirm_consent"
                                value="1"
                                required
                            >

                            <label
                                class="form-check-label"
                                for="consent-{{ $contact->id }}">
                                I explicitly authorise this contact to receive
                                the selected alert categories. I understand
                                that I can revoke this permission later.
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Save consent
                        </button>
                    </form>

                    @if ($contact->hasActiveConsent())
                        <form method="POST"
                              action="{{ route('pension-guard.trusted-contacts.consent.revoke', $contact->id) }}"
                              class="mt-3"
                              onsubmit="return confirm('Revoke this contact’s alert consent?');">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-secondary">
                                Revoke alert consent
                            </button>
                        </form>
                    @endif
                @endif

                <hr>

                <form method="POST"
                      action="{{ route('pension-guard.trusted-contacts.destroy', $contact->id) }}"
                      onsubmit="return confirm('Remove this trusted contact?');">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-secondary">
                        Remove contact
                    </button>
                </form>
            </section>
        @endforeach
    @endif

    <p class="mb-4">
        <a href="{{ route('pension-guard.dashboard') }}">
            Back to dashboard
        </a>
    </p>
</div>
@endsection