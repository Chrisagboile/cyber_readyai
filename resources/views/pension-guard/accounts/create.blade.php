@extends('pension-guard.layout')

@section('title', 'Add a Pension')

@section('content')
<section class="panel">
    <h2>Add a pension record</h2>

    <p class="muted">
        Choose a provider or enter its name manually. Do not enter your
        pension account number, password, balance or security codes.
    </p>

    <form method="POST" action="{{ route('pension-guard.accounts.store') }}">
        @csrf

        <label for="provider_id">Choose a provider (optional)</label>
        <select name="provider_id" id="provider_id">
            <option value="">My provider is not listed / enter manually</option>
            @foreach ($providers as $provider)
                <option value="{{ $provider->id }}"
                    @selected(old('provider_id') == $provider->id)>
                    {{ $provider->name }}
                </option>
            @endforeach
        </select>

        <label for="provider_name">Provider name if not listed</label>
        <input
            id="provider_name"
            name="provider_name"
            maxlength="150"
            value="{{ old('provider_name') }}"
            placeholder="Enter provider name"
        >

        <label for="display_name">Optional name to help you recognise it</label>
        <input
            id="display_name"
            name="display_name"
            maxlength="100"
            value="{{ old('display_name') }}"
            placeholder="For example, former workplace pension"
        >

        <label for="account_type">Pension type</label>
        <select name="account_type" id="account_type" required>
            <option value="">Select a type</option>
            @foreach ([
                'workplace' => 'Workplace pension',
                'personal' => 'Personal pension',
                'sipp' => 'Self-invested personal pension (SIPP)',
                'defined_benefit' => 'Defined-benefit pension',
                'other' => 'Other / not sure',
            ] as $value => $label)
                <option value="{{ $value }}"
                    @selected(old('account_type') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>

        <label for="notes">Optional notes (never enter sensitive information)</label>
        <p class="help-text">
            Only include general reminders. Do not enter pension account numbers,
            passwords, PINs, one-time verification codes, National Insurance numbers,
            bank details, balances, or other confidential personal information.
        </p>
        <textarea
            id="notes"
            name="notes"
            maxlength="500"
            rows="3"
            aria-describedby="notes-warning"
        >{{ old('notes') }}</textarea>
        <button type="submit">Save pension record</button>
    </form>
</section>
@endsection
