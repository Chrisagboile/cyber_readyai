@extends('layouts.app')

@section('title', 'Edit Department')

@section('content')

<div class="department-edit-page">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="department-edit-header">

        <div>

            <span class="department-edit-eyebrow">
                Department Management
            </span>

            <h1 class="department-edit-title">
                Edit Department
            </h1>

            <p class="department-edit-description">
                Update department details, status and manager assignments.
            </p>

        </div>

        <a
            href="{{ route('departments.show', $department) }}"
            class="department-edit-back-button"
        >
            ← Back to Department
        </a>

    </div>


    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}

    @if($errors->any())

        <div class="department-edit-error-box">

            <div class="department-edit-error-title">
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
         MAIN FORM CARD
    ========================================================== --}}

    <div class="department-edit-card">

        <div class="department-edit-card-header">

            <div>
                <h2>Department Details</h2>

                <p>
                    Editing {{ $department->name }}
                </p>
            </div>

            <span class="department-edit-current-status
                {{ $department->status === 'active' ? 'active' : 'inactive' }}"
            >
                {{ ucfirst($department->status) }}
            </span>

        </div>


        <form
            method="POST"
            action="{{ route('departments.update', $department) }}"
            class="department-edit-form"
        >

            @csrf
            @method('PUT')


            {{-- =================================================
                 BASIC INFORMATION
            ================================================== --}}

            <div class="department-edit-section">

                <div class="department-edit-section-title">
                    Basic Information
                </div>

                <div class="department-edit-grid">


                    {{-- Organisation --}}
                    <div class="department-edit-field">

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
                                            old(
                                                'organisation_id',
                                                $department->organisation_id
                                            ) == $organisation->id
                                        )
                                    >
                                        {{ $organisation->name }}
                                    </option>

                                @endforeach

                            </select>

                        @else

                            <input
                                type="text"
                                value="{{ $department->organisation->name ?? '' }}"
                                disabled
                            >

                            <input
                                type="hidden"
                                name="organisation_id"
                                value="{{ $department->organisation_id }}"
                            >

                        @endif

                        @error('organisation_id')
                            <span class="department-edit-field-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    {{-- Department Name --}}
                    <div class="department-edit-field">

                        <label for="name">
                            Department Name
                        </label>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            value="{{ old('name', $department->name) }}"
                            placeholder="e.g. Information Technology"
                            required
                        >

                        @error('name')
                            <span class="department-edit-field-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    {{-- Slug --}}
                    <div class="department-edit-field">

                        <label for="slug">
                            Slug
                        </label>

                        <input
                            type="text"
                            name="slug"
                            id="slug"
                            value="{{ old('slug', $department->slug) }}"
                            placeholder="information-technology"
                        >

                        <small>
                            Leave blank to generate automatically from
                            the department name.
                        </small>

                        @error('slug')
                            <span class="department-edit-field-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    {{-- Status --}}
                    <div class="department-edit-field">

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
                                    old(
                                        'status',
                                        $department->status
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
                                        $department->status
                                    ) === 'inactive'
                                )
                            >
                                Inactive
                            </option>

                        </select>

                        @error('status')
                            <span class="department-edit-field-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

                </div>

            </div>


            {{-- =================================================
                 DESCRIPTION
            ================================================== --}}

            <div class="department-edit-section">

                <div class="department-edit-section-title">
                    Description
                </div>

                <div class="department-edit-field">

                    <label for="description">
                        Department Description
                    </label>

                    <textarea
                        name="description"
                        id="description"
                        rows="5"
                        placeholder="Describe the purpose or responsibilities of this department..."
                    >{{ old('description', $department->description) }}</textarea>

                    @error('description')
                        <span class="department-edit-field-error">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

            </div>


            {{-- =================================================
                 MANAGERS
            ================================================== --}}

            <div class="department-edit-section">

                <div class="department-edit-section-title">
                    Department Manager(s)
                </div>

                <p class="department-edit-section-description">
                    Select one or more managers responsible for this department.
                </p>

                <div class="department-edit-manager-field">

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
                                        old(
                                            'manager_ids',
                                            $department->managers
                                                ->pluck('id')
                                                ->all()
                                        )
                                    )
                                )
                            >
                                {{ $manager->name }}
                                ({{ $manager->username }})
                            </option>

                        @endforeach

                    </select>

                    <div class="department-edit-manager-help">
                        Hold <strong>Ctrl</strong> on Windows or
                        <strong>Command</strong> on Mac to select multiple managers.
                    </div>

                    @error('manager_ids')
                        <span class="department-edit-field-error">
                            {{ $message }}
                        </span>
                    @enderror

                    @error('manager_ids.*')
                        <span class="department-edit-field-error">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

            </div>


            {{-- =================================================
                 FORM ACTIONS
            ================================================== --}}

            <div class="department-edit-actions">

                <a
                    href="{{ route('departments.show', $department) }}"
                    class="department-edit-cancel"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="department-edit-save"
                >
                    Save Changes
                </button>

            </div>

        </form>

    </div>

</div>


<style>

/* =========================================================
   DEPARTMENT EDIT
========================================================= */

.department-edit-page {
    width: 100%;
    max-width: 1100px;
    margin: 0 auto;
    padding: 28px 32px 50px;
    box-sizing: border-box;
}


/* =========================================================
   HEADER
========================================================= */

.department-edit-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 24px;
    margin-bottom: 26px;
}

.department-edit-eyebrow {
    display: block;
    margin-bottom: 8px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .1em;
    color: #667085;
}

.department-edit-title {
    margin: 0 0 8px;
    font-size: 30px;
    line-height: 1.15;
    font-weight: 700;
    letter-spacing: -.02em;
    color: #172033;
}

.department-edit-description {
    margin: 0;
    font-size: 15px;
    line-height: 1.6;
    color: #667085;
}

.department-edit-back-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 38px;
    padding: 9px 13px;
    border-radius: 8px;
    background: #eef1f4;
    color: #172033;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    white-space: nowrap;
}


/* =========================================================
   ERROR
========================================================= */

.department-edit-error-box {
    margin-bottom: 20px;
    padding: 14px 16px;
    border-radius: 10px;
    background: #fde8e8;
    border: 1px solid #f5c5c5;
    color: #9f1d1d;
    font-size: 12px;
}

.department-edit-error-title {
    margin-bottom: 7px;
    font-weight: 750;
}

.department-edit-error-box ul {
    margin: 0;
    padding-left: 18px;
}

.department-edit-error-box li + li {
    margin-top: 3px;
}


/* =========================================================
   CARD
========================================================= */

.department-edit-card {
    overflow: hidden;
    border-radius: 16px;
    background: #fff;
    border: 1px solid rgba(15,23,42,.08);
    box-shadow: 0 6px 24px rgba(15,23,42,.05);
}

.department-edit-card-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 20px;
    padding: 23px 25px;
    border-bottom: 1px solid rgba(15,23,42,.08);
}

.department-edit-card-header h2 {
    margin: 0 0 5px;
    font-size: 19px;
    line-height: 1.3;
    color: #172033;
}

.department-edit-card-header p {
    margin: 0;
    font-size: 13px;
    color: #667085;
}

.department-edit-current-status {
    display: inline-flex;
    align-items: center;
    padding: 5px 9px;
    border-radius: 999px;
    font-size: 10px;
    line-height: 1.2;
    font-weight: 700;
}

.department-edit-current-status.active {
    background: #e6f6ee;
    color: #18794e;
}

.department-edit-current-status.inactive {
    background: #eef1f4;
    color: #667085;
}


/* =========================================================
   FORM
========================================================= */

.department-edit-form {
    padding: 25px;
}

.department-edit-section {
    padding: 0 0 26px;
    margin-bottom: 25px;
    border-bottom: 1px solid rgba(15,23,42,.08);
}

.department-edit-section:last-of-type {
    margin-bottom: 0;
}

.department-edit-section-title {
    margin-bottom: 16px;
    font-size: 14px;
    line-height: 1.3;
    font-weight: 750;
    color: #172033;
}

.department-edit-section-description {
    margin: -8px 0 16px;
    font-size: 12px;
    line-height: 1.5;
    color: #667085;
}


/* =========================================================
   GRID
========================================================= */

.department-edit-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px;
}


/* =========================================================
   FORM FIELDS
========================================================= */

.department-edit-field label {
    display: block;
    margin-bottom: 7px;
    font-size: 12px;
    font-weight: 700;
    color: #172033;
}

.department-edit-field input,
.department-edit-field select,
.department-edit-field textarea {
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

.department-edit-field input,
.department-edit-field select {
    height: 42px;
    padding: 0 12px;
}

.department-edit-field textarea {
    min-height: 120px;
    padding: 11px 12px;
    resize: vertical;
    line-height: 1.55;
}

.department-edit-field input:focus,
.department-edit-field select:focus,
.department-edit-field textarea:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,.10);
}

.department-edit-field input:disabled {
    background: #f4f6f8;
    color: #667085;
    cursor: not-allowed;
}

.department-edit-field small {
    display: block;
    margin-top: 6px;
    font-size: 11px;
    line-height: 1.45;
    color: #8a94a6;
}

.department-edit-field-error {
    display: block;
    margin-top: 6px;
    font-size: 11px;
    color: #b42318;
}


/* =========================================================
   MANAGER SELECT
========================================================= */

.department-edit-manager-field select {
    width: 100%;
    min-height: 150px;
    height: auto;
    padding: 7px;
    line-height: 1.8;
}

.department-edit-manager-field option {
    padding: 7px 9px;
    border-radius: 5px;
}

.department-edit-manager-field option:checked {
    background: #eaf2ff;
    color: #2257a5;
}

.department-edit-manager-help {
    margin-top: 7px;
    font-size: 11px;
    line-height: 1.5;
    color: #8a94a6;
}


/* =========================================================
   ACTIONS
========================================================= */

.department-edit-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 9px;
    padding-top: 20px;
}

.department-edit-cancel,
.department-edit-save {
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

.department-edit-cancel {
    background: #eef1f4;
    color: #172033;
}

.department-edit-save {
    border: 0;
    background: #2563eb;
    color: #fff;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 750px) {

    .department-edit-page {
        padding: 20px 16px 40px;
    }

    .department-edit-header {
        flex-direction: column;
    }

    .department-edit-grid {
        grid-template-columns: 1fr;
    }

    .department-edit-card-header {
        flex-direction: column;
    }

    .department-edit-actions {
        flex-direction: column-reverse;
        align-items: stretch;
    }

    .department-edit-cancel,
    .department-edit-save {
        width: 100%;
    }

}

</style>

@endsection
