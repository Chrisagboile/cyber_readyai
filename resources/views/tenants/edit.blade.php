@extends('layouts.app')

@section('content')

<div class="tenant-edit-page">

    <div class="tenant-edit-header">

        <div>
            <a
                href="{{ route('tenants.index') }}"
                class="tenant-edit-back"
            >
                ← Back to Organisations
            </a>

            <span class="tenant-edit-eyebrow">
                Platform Administration
            </span>

            <h1>Edit Organisation</h1>

            <p>
                Update tenant information and account status.
            </p>
        </div>

    </div>


    @if($errors->any())

        <div class="tenant-edit-alert">

            <strong>Please correct the following:</strong>

            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>

    @endif


    <div class="tenant-edit-card">

        <form
            method="POST"
            action="{{ route('tenants.update', $organisation) }}"
        >

            @csrf
            @method('PUT')

            <div class="tenant-edit-section">

                <div class="tenant-edit-section-heading">

                    <div>
                        <h2>Organisation Details</h2>

                        <p>
                            Manage the tenant identity and platform status.
                        </p>
                    </div>

                    @if(strtolower((string) $organisation->status) === 'active')

                        <span class="tenant-edit-badge tenant-edit-badge-success">
                            Active
                        </span>

                    @else

                        <span class="tenant-edit-badge tenant-edit-badge-neutral">
                            Inactive
                        </span>

                    @endif

                </div>


                <div class="tenant-edit-grid">

                    <div class="tenant-edit-field tenant-edit-field-full">

                        <label for="name">
                            Organisation Name
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $organisation->name) }}"
                            required
                        >

                    </div>


                    <div class="tenant-edit-field">

                        <label for="slug">
                            Slug
                        </label>

                        <input
                            type="text"
                            id="slug"
                            name="slug"
                            value="{{ old('slug', $organisation->slug) }}"
                            required
                        >

                    </div>


                    <div class="tenant-edit-field">

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
                                @selected(
                                    old(
                                        'status',
                                        $organisation->status
                                    ) === 'active'
                                )
                            >
                                Active
                            </option>

                            <option
                                value="inactive"
                                @selected(
                                    old(
                                        'status',
                                        $organisation->status
                                    ) === 'inactive'
                                )
                            >
                                Inactive
                            </option>

                        </select>

                    </div>

                </div>

            </div>


            <div class="tenant-edit-actions">

                <a
                    href="{{ route('tenants.index') }}"
                    class="tenant-edit-btn tenant-edit-btn-secondary"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="tenant-edit-btn tenant-edit-btn-primary"
                >
                    Save Changes
                </button>

            </div>

        </form>

    </div>

</div>


<style>
.tenant-edit-page {
    max-width: 1050px;
    margin: 0 auto;
    padding: 8px 0 45px;
}

.tenant-edit-header {
    margin-bottom: 22px;
}

.tenant-edit-back {
    display: inline-block;
    margin-bottom: 12px;
    color: inherit;
    text-decoration: none;
    font-size: 11px;
    font-weight: 800;
    opacity: .58;
}

.tenant-edit-back:hover {
    color: inherit;
    text-decoration: underline;
}

.tenant-edit-eyebrow {
    display: block;
    margin-bottom: 7px;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .09em;
    opacity: .52;
}

.tenant-edit-header h1 {
    margin: 0;
    font-size: 29px;
}

.tenant-edit-header p {
    margin: 7px 0 0;
    font-size: 13px;
    opacity: .6;
}

.tenant-edit-alert {
    margin-bottom: 18px;
    padding: 14px 16px;
    border: 1px solid rgba(220,53,69,.2);
    border-radius: 11px;
    background: rgba(220,53,69,.06);
    color: #a51f2d;
    font-size: 12px;
}

.tenant-edit-alert ul {
    margin: 7px 0 0 18px;
}

.tenant-edit-card {
    padding: 24px;
    border: 1px solid rgba(127,127,127,.14);
    border-radius: 16px;
    background: var(--card-bg, #fff);
    box-shadow: 0 7px 24px rgba(0,0,0,.035);
}

.tenant-edit-section-heading {
    display: flex;
    justify-content: space-between;
    gap: 15px;
    align-items: flex-start;
    margin-bottom: 20px;
}

.tenant-edit-section-heading h2 {
    margin: 0;
    font-size: 18px;
}

.tenant-edit-section-heading p {
    margin: 5px 0 0;
    font-size: 12px;
    opacity: .57;
}

.tenant-edit-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 17px;
}

.tenant-edit-field-full {
    grid-column: 1 / -1;
}

.tenant-edit-field label {
    display: block;
    margin-bottom: 7px;
    font-size: 11px;
    font-weight: 800;
}

.tenant-edit-field input,
.tenant-edit-field select {
    width: 100%;
    min-height: 43px;
    padding: 0 12px;
    border: 1px solid rgba(127,127,127,.18);
    border-radius: 9px;
    background: transparent;
    color: inherit;
    font-size: 13px;
}

.tenant-edit-field input:focus,
.tenant-edit-field select:focus {
    outline: none;
    border-color: rgba(70,70,70,.55);
}

.tenant-edit-badge {
    display: inline-flex;
    align-items: center;
    min-height: 25px;
    padding: 0 9px;
    border-radius: 999px;
    font-size: 9px;
    font-weight: 800;
}

.tenant-edit-badge-success {
    color: #25784c;
    background: rgba(46,157,99,.11);
}

.tenant-edit-badge-neutral {
    background: rgba(127,127,127,.1);
}

.tenant-edit-actions {
    display: flex;
    justify-content: flex-end;
    gap: 9px;
    margin-top: 25px;
    padding-top: 19px;
    border-top: 1px solid rgba(127,127,127,.10);
}

.tenant-edit-btn {
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

.tenant-edit-btn-primary {
    background: #111827;
    color: #fff;
}

.tenant-edit-btn-primary:hover {
    color: #fff;
    opacity: .92;
}

.tenant-edit-btn-secondary {
    color: inherit;
    background: rgba(127,127,127,.08);
    border-color: rgba(127,127,127,.15);
}

.tenant-edit-btn-secondary:hover {
    color: inherit;
    background: rgba(127,127,127,.13);
}

@media (max-width: 650px) {
    .tenant-edit-grid {
        grid-template-columns: 1fr;
    }

    .tenant-edit-field-full {
        grid-column: auto;
    }

    .tenant-edit-actions {
        flex-direction: column-reverse;
    }

    .tenant-edit-btn {
        width: 100%;
    }

    .tenant-edit-card {
        padding: 17px;
    }
}
</style>

@endsection
