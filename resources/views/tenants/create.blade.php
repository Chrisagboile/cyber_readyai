@extends('layouts.app')

@section('content')

<div class="tenant-form-page">

    <div class="tenant-form-header">

        <div>
            <a
                href="{{ route('tenants.index') }}"
                class="tenant-form-back"
            >
                ← Back to Organisations
            </a>

            <span class="tenant-form-eyebrow">
                Platform Administration
            </span>

            <h1>Create Organisation</h1>

            <p>
                Add a new CyberReadyAI tenant to the platform.
            </p>
        </div>

    </div>


    @if($errors->any())

        <div class="tenant-form-alert">

            <strong>Please correct the following:</strong>

            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>

    @endif


    <div class="tenant-form-card">

        <form
            method="POST"
            action="{{ route('tenants.store') }}"
        >

            @csrf

            <div class="tenant-form-section">

                <div class="tenant-form-section-heading">
                    <h2>Organisation Details</h2>
                    <p>
                        Basic information used to identify the tenant.
                    </p>
                </div>


                <div class="tenant-form-grid">

                    <div class="tenant-form-field tenant-form-field-full">

                        <label for="name">
                            Organisation Name
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            autofocus
                            placeholder="e.g. Acme Security Ltd"
                        >

                    </div>


                    <div class="tenant-form-field">

                        <label for="slug">
                            Slug
                        </label>

                        <input
                            type="text"
                            id="slug"
                            name="slug"
                            value="{{ old('slug') }}"
                            placeholder="Optional — generated automatically"
                        >

                        <small>
                            Leave blank to generate the slug automatically.
                        </small>

                    </div>


                    <div class="tenant-form-field">

                        <label for="status">
                            Status
                        </label>

                        <select
                            id="status"
                            name="status"
                            required
                        >
                            <option
                                value="active"
                                @selected(old('status', 'active') === 'active')
                            >
                                Active
                            </option>

                            <option
                                value="inactive"
                                @selected(old('status') === 'inactive')
                            >
                                Inactive
                            </option>
                        </select>

                    </div>

                </div>

            </div>


            <div class="tenant-form-actions">

                <a
                    href="{{ route('tenants.index') }}"
                    class="tenant-form-btn tenant-form-btn-secondary"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="tenant-form-btn tenant-form-btn-primary"
                >
                    Create Organisation
                </button>

            </div>

        </form>

    </div>

</div>


<style>
.tenant-form-page {
    max-width: 1050px;
    margin: 0 auto;
    padding: 8px 0 45px;
}

.tenant-form-header {
    margin-bottom: 22px;
}

.tenant-form-back {
    display: inline-block;
    margin-bottom: 12px;
    color: inherit;
    text-decoration: none;
    font-size: 11px;
    font-weight: 800;
    opacity: .58;
}

.tenant-form-back:hover {
    color: inherit;
    text-decoration: underline;
}

.tenant-form-eyebrow {
    display: block;
    margin-bottom: 7px;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .09em;
    opacity: .52;
}

.tenant-form-header h1 {
    margin: 0;
    font-size: 29px;
}

.tenant-form-header p {
    margin: 7px 0 0;
    font-size: 13px;
    opacity: .6;
}

.tenant-form-alert {
    margin-bottom: 18px;
    padding: 14px 16px;
    border: 1px solid rgba(220,53,69,.2);
    border-radius: 11px;
    background: rgba(220,53,69,.06);
    color: #a51f2d;
    font-size: 12px;
}

.tenant-form-alert ul {
    margin: 7px 0 0 18px;
}

.tenant-form-card {
    padding: 24px;
    border: 1px solid rgba(127,127,127,.14);
    border-radius: 16px;
    background: var(--card-bg, #fff);
    box-shadow: 0 7px 24px rgba(0,0,0,.035);
}

.tenant-form-section-heading {
    margin-bottom: 20px;
}

.tenant-form-section-heading h2 {
    margin: 0;
    font-size: 18px;
}

.tenant-form-section-heading p {
    margin: 5px 0 0;
    font-size: 12px;
    opacity: .57;
}

.tenant-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 17px;
}

.tenant-form-field-full {
    grid-column: 1 / -1;
}

.tenant-form-field label {
    display: block;
    margin-bottom: 7px;
    font-size: 11px;
    font-weight: 800;
}

.tenant-form-field input,
.tenant-form-field select {
    width: 100%;
    min-height: 43px;
    padding: 0 12px;
    border: 1px solid rgba(127,127,127,.18);
    border-radius: 9px;
    background: transparent;
    color: inherit;
    font-size: 13px;
}

.tenant-form-field input:focus,
.tenant-form-field select:focus {
    outline: none;
    border-color: rgba(70,70,70,.55);
}

.tenant-form-field small {
    display: block;
    margin-top: 6px;
    font-size: 10px;
    opacity: .5;
}

.tenant-form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 9px;
    margin-top: 25px;
    padding-top: 19px;
    border-top: 1px solid rgba(127,127,127,.10);
}

.tenant-form-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 42px;
    padding: 0 16px;
    border-radius: 9px;
    font-size: 12px;
    font-weight: 800;
    text-decoration: none;
    border: 1px solid transparent;
    cursor: pointer;
}

.tenant-form-btn-primary {
    background: #111827;
    color: #fff;
}

.tenant-form-btn-primary:hover {
    color: #fff;
    opacity: .92;
}

.tenant-form-btn-secondary {
    color: inherit;
    background: rgba(127,127,127,.08);
    border-color: rgba(127,127,127,.15);
}

.tenant-form-btn-secondary:hover {
    color: inherit;
    background: rgba(127,127,127,.13);
}

@media (max-width: 650px) {
    .tenant-form-grid {
        grid-template-columns: 1fr;
    }

    .tenant-form-field-full {
        grid-column: auto;
    }

    .tenant-form-actions {
        flex-direction: column-reverse;
    }

    .tenant-form-btn {
        width: 100%;
    }

    .tenant-form-card {
        padding: 17px;
    }
}
</style>

@endsection
