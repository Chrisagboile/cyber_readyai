@extends('layouts.app')

@section('content')
<div class="organisation-assessment-create-page">

    <div class="dashboard-header">

        <div>
            <h1 class="dashboard-title">
                Create Assessment
            </h1>

            <p class="dashboard-description">
                Create and assign a cybersecurity assessment to an employee.
            </p>
        </div>

        <a
            href="{{ route('organisation.assessments') }}"
            class="assessment-back-link"
        >
            ← Back to Assessments
        </a>

    </div>


    <div class="assessment-form-card">

        <div class="assessment-form-header">
            <h2>Assessment Configuration</h2>

            <p>
                Select an employee, choose the question categories and
                configure the assessment duration.
            </p>
        </div>


        @if($errors->any())
            <div class="assessment-form-errors">

                <strong>
                    Please correct the following:
                </strong>

                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>
        @endif


        <form
            method="POST"
            action="{{ route('organisation.assessments.generate') }}"
        >
            @csrf


            <div class="assessment-form-grid">


                {{-- Assessment Name --}}
                <div class="assessment-form-field full-width">

                    <label for="assessment_name">
                        Assessment Name
                    </label>

                    <input
                        id="assessment_name"
                        type="text"
                        name="assessment_name"
                        value="{{ old('assessment_name') }}"
                        placeholder="e.g. Cybersecurity Awareness Assessment"
                        required
                    >

                    @error('assessment_name')
                        <span class="assessment-form-error">
                            {{ $message }}
                        </span>
                    @enderror

                </div>


                {{-- Employee --}}
                <div class="assessment-form-field full-width">

                    <label for="employee_id">
                        Employee
                    </label>

                    <select
                        id="employee_id"
                        name="employee_id"
                        required
                    >
                        <option value="">
                            Select employee
                        </option>

                        @php
                            $employeesByDepartment = $employees->groupBy(
                                fn ($employee) =>
                                    $employee->department?->name
                                    ?? 'No Department'
                            );
                        @endphp

                        @foreach($employeesByDepartment as $departmentName => $departmentEmployees)

                            <optgroup label="{{ $departmentName }}">

                                @foreach($departmentEmployees as $employee)

                                    <option
                                        value="{{ $employee->id }}"
                                        @selected(
                                            (string) old('employee_id')
                                            === (string) $employee->id
                                        )
                                    >
                                        {{ $employee->name }}
                                    </option>

                                @endforeach

                            </optgroup>

                        @endforeach

                    </select>

                    @error('employee_id')
                        <span class="assessment-form-error">
                            {{ $message }}
                        </span>
                    @enderror

                    <small>
                        Only active employees in your organisation
                        can receive organisation assessments.
                    </small>

                </div>


                {{-- Question Quantity --}}
                <div class="assessment-form-field">

                    <label for="question_quantity">
                        Number of Questions
                    </label>

                    <input
                        id="question_quantity"
                        type="number"
                        name="question_quantity"
                        min="1"
                        max="200"
                        value="{{ old('question_quantity', 10) }}"
                        required
                    >

                    @error('question_quantity')
                        <span class="assessment-form-error">
                            {{ $message }}
                        </span>
                    @enderror

                </div>


                {{-- Duration --}}
                <div class="assessment-form-field">

                    <label for="duration_minutes">
                        Duration
                    </label>

                    <select
                        id="duration_minutes"
                        name="duration_minutes"
                        required
                    >
                        @foreach([10, 15, 20, 30, 45, 60, 90, 120, 180, 240] as $minutes)

                            <option
                                value="{{ $minutes }}"
                                @selected(
                                    (int) old(
                                        'duration_minutes',
                                        30
                                    ) === $minutes
                                )
                            >
                                {{ $minutes }} minutes
                            </option>

                        @endforeach
                    </select>

                    @error('duration_minutes')
                        <span class="assessment-form-error">
                            {{ $message }}
                        </span>
                    @enderror

                </div>


                {{-- Categories --}}
                <div class="assessment-form-field full-width">

                    <label>
                        Question Categories
                    </label>

                    <div class="category-grid">

                        @foreach($categories as $category)

                            <label class="category-option">

                                <input
                                    type="checkbox"
                                    name="category_ids[]"
                                    value="{{ $category->id }}"
                                    @checked(
                                        in_array(
                                            $category->id,
                                            old('category_ids', [])
                                        )
                                    )
                                >

                                <span>
                                    <strong>
                                        {{ $category->name }}
                                    </strong>
                                </span>

                            </label>

                        @endforeach

                    </div>

                    @error('category_ids')
                        <span class="assessment-form-error">
                            {{ $message }}
                        </span>
                    @enderror

                    @error('category_ids.*')
                        <span class="assessment-form-error">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

            </div>


            <div class="assessment-form-actions">

                <a
                    href="{{ route('organisation.assessments') }}"
                    class="assessment-cancel-button"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="assessment-save-button"
                >
                    Generate Assessment
                </button>

            </div>

        </form>

    </div>

</div>


<style>
    .organisation-assessment-create-page {
        max-width: 1050px;
        margin: 0 auto;
        padding: 28px 32px 50px;
    }

    .organisation-assessment-create-page .dashboard-header {
        display: flex !important;
        justify-content: space-between !important;
        align-items: flex-start !important;
        gap: 24px !important;
        margin-bottom: 26px !important;
    }

    .assessment-back-link {
        font-size: 13px;
        text-decoration: none;
    }

    .assessment-form-card {
        overflow: hidden;
        border-radius: 16px;
        background: #fff;
        border: 1px solid rgba(15, 23, 42, .08);
        box-shadow: 0 6px 24px rgba(15, 23, 42, .05);
    }

    .assessment-form-header {
        padding: 23px 25px;
        border-bottom: 1px solid rgba(15, 23, 42, .08);
    }

    .assessment-form-header h2 {
        margin: 0 0 5px;
        font-size: 19px;
    }

    .assessment-form-header p {
        margin: 0;
        font-size: 13px;
        opacity: .6;
    }

    .assessment-form-card form {
        padding: 25px;
    }

    .assessment-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 21px;
    }

    .assessment-form-field.full-width {
        grid-column: 1 / -1;
    }

    .assessment-form-field label {
        display: block;
        margin-bottom: 7px;
        font-size: 12px;
        font-weight: 700;
    }

    .assessment-form-field input[type="text"],
    .assessment-form-field input[type="number"],
    .assessment-form-field select {
        width: 100%;
        height: 42px;
        box-sizing: border-box;
        padding: 0 12px;
        border: 1px solid #d7dce2;
        border-radius: 8px;
        background: #fff;
        font-size: 13px;
    }

    .assessment-form-field small {
        display: block;
        margin-top: 6px;
        font-size: 11px;
        line-height: 1.45;
        opacity: .58;
    }

    .assessment-form-error {
        display: block;
        margin-top: 6px;
        font-size: 11px;
        color: #b42318;
    }

    .assessment-form-errors {
        margin: 20px 25px 0;
        padding: 13px 15px;
        border-radius: 10px;
        background: #fde8e8;
        color: #9f1d1d;
        font-size: 12px;
    }

    .assessment-form-errors ul {
        margin: 7px 0 0;
        padding-left: 18px;
    }

    .category-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px;
    }

    .category-option {
        display: flex !important;
        align-items: center;
        gap: 9px;
        padding: 13px;
        border: 1px solid #dfe3e8;
        border-radius: 10px;
        cursor: pointer;
        background: #fafbfc;
    }

    .category-option:hover {
        border-color: #c7ced7;
    }

    .category-option input {
        margin: 0;
    }

    .category-option strong {
        font-size: 12px;
    }

    .assessment-form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 9px;
        margin-top: 28px;
        padding-top: 20px;
        border-top: 1px solid rgba(15, 23, 42, .08);
    }

    .assessment-cancel-button,
    .assessment-save-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 15px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
    }

    .assessment-cancel-button {
        background: #eef1f4;
        color: #172033;
    }

    .assessment-save-button {
        border: 0;
        background: #2563eb;
        color: #fff;
    }

    @media (max-width: 750px) {
        .organisation-assessment-create-page {
            padding: 20px 16px 40px;
        }

        .organisation-assessment-create-page .dashboard-header {
            flex-direction: column;
        }

        .assessment-form-grid {
            grid-template-columns: 1fr;
        }

        .assessment-form-field.full-width {
            grid-column: auto;
        }

        .category-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection
