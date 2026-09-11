@extends('layouts.app')

@section('title', 'Create Department')

@section('content')

<div class="department-create-page">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="department-create-header">

        <div>

            <span class="department-create-eyebrow">
                Organisation Management
            </span>

            <h1 class="department-create-title">
                Create Department
            </h1>

            <p class="department-create-description">
                Create a new department and assign its manager(s).
            </p>

        </div>

        <a
            href="{{ route('departments.index') }}"
            class="department-create-back-button"
        >
            ← Back to Departments
        </a>

    </div>


    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}

    @if($errors->any())

        <div class="department-create-error-box">

            <div class="department-create-error-title">
                Please correct the following:
            </div>

            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>

    @endif


    {{-- =========================================================
         MAIN CARD
    ========================================================== --}}

    <div class="department-create-card">

        <div class="department-create-card-header">

            <div>
                <h2>Department Details</h2>

                <p>
                    Enter the information for the new department.
                </p>
            </div>

        </div>


        <form
            method="POST"
            action="{{ route('departments.store') }}"
            class="department-create-form"
        >

            @csrf


            {{-- =================================================
                 BASIC INFORMATION
            ================================================== --}}

            <div class="department-create-section">

                <div class="department-create-section-title">
                    Basic Information
                </div>

                <div class="department-create-grid">


                    {{-- Organisation --}}
                    <div class="department-create-field">

                        <label for="organisation_id">
                            Organisation
                        </label>

                        @if(auth()->user()->hasRole('super-admin'))

                            <select
                                name="organisation_id"
                                id="organisation_id"
                                required
                            >

                                <option value="">
                                    Select organisation
                                </option>

                                @foreach($organisations as $organisation)

                                    <option
                                        value="{{ $organisation->id }}"
                                        @selected(
                                            old('organisation_id')
                                            == $organisation->id
                                        )
                                    >
                                        {{ $organisation->name }}
                                    </option>

                                @endforeach

                            </select>

                        @else

                            <input
                                type="text"
                                value="{{ $organisations->first()->name ?? '' }}"
                                disabled
                            >

                            <input
                                type="hidden"
                                name="organisation_id"
                                value="{{ $organisations->first()->id ?? '' }}"
                            >

                        @endif

                        @error('organisation_id')
                            <span class="department-create-field-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    {{-- Department Name --}}
                    <div class="department-create-field">

                        <label for="name">
                            Department Name
                        </label>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            value="{{ old('name') }}"
                            placeholder="e.g. Information Technology"
                            required
                        >

                        <small>
                            Enter the name of the department.
                        </small>

                        @error('name')
                            <span class="department-create-field-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    {{-- Slug --}}
                    <div class="department-create-field">

                        <label for="slug">
                            Slug
                        </label>

                        <input
                            type="text"
                            name="slug"
                            id="slug"
                            value="{{ old('slug') }}"
                            placeholder="information-technology"
                        >

                        <small>
                            Leave blank to generate the slug automatically.
                        </small>

                        @error('slug')
                            <span class="department-create-field-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    {{-- Status --}}
                    <div class="department-create-field">

                        <label for="status">
                            Status
                        </label>

                        <select
                            name="status"
                            id="status"
                            required
                        >

                            <option
                                value="active"
                                @selected(
                                    old('status', 'active') === 'active'
                                )
                            >
                                Active
                            </option>

                            <option
                                value="inactive"
                                @selected(
                                    old('status') === 'inactive'
                                )
                            >
                                Inactive
                            </option>

                        </select>

                        @error('status')
                            <span class="department-create-field-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

                </div>

            </div>


            {{-- =================================================
                 DESCRIPTION
            ================================================== --}}

            <div class="department-create-section">

                <div class="department-create-section-title">
                    Description
                </div>

                <div class="department-create-field">

                    <label for="description">
                        Department Description
                    </label>

                    <textarea
                        name="description"
                        id="description"
                        rows="5"
                        placeholder="Describe the purpose and responsibilities of this department..."
                    >{{ old('description') }}</textarea>

                    @error('description')
                        <span class="department-create-field-error">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

            </div>


            {{-- =================================================
                 MANAGERS
            ================================================== --}}

            <div class="department-create-section">

                <div class="department-create-section-title">
                    Department Manager(s)
                </div>

                <p class="department-create-section-description">
                    Select one or more managers responsible for this department.
                </p>

                <div class="department-create-manager-field">

                    <select
                        name="manager_ids[]"
                        id="manager_ids"
                        multiple
                    >

                        @foreach($managers as $manager)

                            <option
                                value="{{ $manager->id }}"
                                @selected(
                                    in_array(
                                        $manager->id,
                                        old('manager_ids', [])
                                    )
                                )
                            >
                                {{ $manager->name }}
                                ({{ $manager->username }})
                            </option>

                        @endforeach

                    </select>

                    <div class="department-create-manager-help">
                        Hold <strong>Ctrl</strong> on Windows or
                        <strong>Command</strong> on Mac to select multiple managers.
                    </div>

                    @error('manager_ids')
                        <span class="department-create-field-error">
                            {{ $message }}
                        </span>
                    @enderror

                    @error('manager_ids.*')
                        <span class="department-create-field-error">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

            </div>


            {{-- =================================================
                 ACTIONS
            ================================================== --}}

            <div class="department-create-actions">

                <a
                    href="{{ route('departments.index') }}"
                    class="department-create-cancel"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="department-create-save"
                >
                    Create Department
                </button>

            </div>

        </form>

    </div>

</div>


<style>

/* =========================================================
   DEPARTMENT CREATE
========================================================= */

.department-create-page {
    width: 100%;
    max-width: 1100px;
    margin: 0 auto;
    padding: 28px 32px 50px;
    box-sizing: border-box;
}


/* =========================================================
   HEADER
========================================================= */

.department-create-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 24px;
    margin-bottom: 26px;
}

.department-create-eyebrow {
    display: block;
    margin-bottom: 8px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .1em;
    color: #667085;
}

.department-create-title {
    margin: 0 0 8px;
    font-size: 30px;
    line-height: 1.15;
    font-weight: 700;
    letter-spacing: -.02em;
    color: #172033;
}

.department-create-description {
    margin: 0;
    font-size: 15px;
    line-height: 1.6;
    color: #667085;
}

.department-create-back-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 39px;
    padding: 9px 14px;
    border-radius: 8px;
    background: #eef1f4;
    color: #172033;
    font-size: 11px;
    font-weight: 700;
    text-decoration: none;
    white-space: nowrap;
}


/* =========================================================
   ERROR
========================================================= */

.department-create-error-box {
    margin-bottom: 20px;
    padding: 14px 16px;
    border-radius: 10px;
    background: #fde8e8;
    border: 1px solid #f5c5c5;
    color: #9f1d1d;
    font-size: 12px;
}

.department-create-error-title {
    margin-bottom: 7px;
    font-weight: 750;
}

.department-create-error-box ul {
    margin: 0;
    padding-left: 18px;
}

.department-create-error-box li + li {
    margin-top: 3px;
}


/* =========================================================
   CARD
========================================================= */

.department-create-card {
    overflow: hidden;
    border-radius: 16px;
    background: #fff;
    border: 1px solid rgba(15,23,42,.08);
    box-shadow: 0 6px 24px rgba(15,23,42,.05);
}

.department-create-card-header {
    padding: 23px 25px;
    border-bottom: 1px solid rgba(15,23,42,.08);
}

.department-create-card-header h2 {
    margin: 0 0 5px;
    font-size: 19px;
    line-height: 1.3;
    color: #172033;
}

.department-create-card-header p {
    margin: 0;
    font-size: 13px;
    color: #667085;
}


/* =========================================================
   FORM
========================================================= */

.department-create-form {
    padding: 25px;
}

.department-create-section {
    padding-bottom: 26px;
    margin-bottom: 25px;
    border-bottom: 1px solid rgba(15,23,42,.08);
}

.department-create-section:last-of-type {
    margin-bottom: 0;
}

.department-create-section-title {
    margin-bottom: 16px;
    font-size: 14px;
    line-height: 1.3;
    font-weight: 750;
    color: #172033;
}

.department-create-section-description {
    margin: -8px 0 16px;
    font-size: 12px;
    line-height: 1.5;
    color: #667085;
}


/* =========================================================
   GRID
========================================================= */

.department-create-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px;
}


/* =========================================================
   FORM FIELDS
========================================================= */

.department-create-field label {
    display: block;
    margin-bottom: 7px;
    font-size: 12px;
    font-weight: 700;
    color: #172033;
}

.department-create-field input,
.department-create-field select,
.department-create-field textarea {
    width: 100%;
    box-sizing: border-box;
    border: 1px solid #d7dce2;
    border-radius: 9px;
    background: #fff;
    color: #172033;
    font-family: inherit;
    font-size: 13px;
    transition:
        border-color .15s ease,
        box-shadow .15s ease;
}

.department-create-field input,
.department-create-field select {
    height: 42px;
    padding: 0 12px;
}

.department-create-field textarea {
    min-height: 120px;
    padding: 11px 12px;
    resize: vertical;
    line-height: 1.55;
}

.department-create-field input:focus,
.department-create-field select:focus,
.department-create-field textarea:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,.10);
}

.department-create-field input:disabled {
    background: #f4f6f8;
    color: #667085;
    cursor: not-allowed;
}

.department-create-field small {
    display: block;
    margin-top: 6px;
    font-size: 11px;
    line-height: 1.45;
    color: #8a94a6;
}

.department-create-field-error {
    display: block;
    margin-top: 6px;
    font-size: 11px;
    color: #b42318;
}


/* =========================================================
   MANAGER SELECT
========================================================= */

.department-create-manager-field select {
    width: 100%;
    min-height: 150px;
    height: auto;
    padding: 7px;
    line-height: 1.8;
}

.department-create-manager-field option {
    padding: 7px 9px;
    border-radius: 5px;
}

.department-create-manager-field option:checked {
    background: #eaf2ff;
    color: #2257a5;
}

.department-create-manager-help {
    margin-top: 7px;
    font-size: 11px;
    line-height: 1.5;
    color: #8a94a6;
}


/* =========================================================
   ACTIONS
========================================================= */

.department-create-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 9px;
    padding-top: 20px;
}

.department-create-cancel,
.department-create-save {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 39px;
    padding: 9px 14px;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
}

.department-create-cancel {
    background: #eef1f4;
    color: #172033;
}

.department-create-save {
    border: 0;
    background: #2563eb;
    color: #fff;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 750px) {

    .department-create-page {
        padding: 20px 16px 40px;
    }

    .department-create-header {
        flex-direction: column;
    }

    .department-create-grid {
        grid-template-columns: 1fr;
    }

    .department-create-actions {
        flex-direction: column-reverse;
        align-items: stretch;
    }

    .department-create-cancel,
    .department-create-save {
        width: 100%;
    }

}

</style>

@endsection
