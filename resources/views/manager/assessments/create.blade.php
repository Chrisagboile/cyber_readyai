@extends('layouts.app')

@section('title', 'Create Assessment')

@section('page-title', 'Create Assessment')

@section('content')

<div class="dashboard-header">

    <div>

        <h1 class="dashboard-title">
            Create Employee Assessment
        </h1>

        <p class="dashboard-description">
            Select an employee, choose cybersecurity categories
            and specify how many questions should be included.
        </p>

    </div>

    <div class="dashboard-date">

        {{ now()->format('l, d F Y') }}

    </div>

</div>


@if(session('success'))

    <div class="alert alert-success">

        {{ session('success') }}

    </div>

@endif


@if($errors->any())

    <div class="alert alert-danger">

        <strong>
            Please correct the following:
        </strong>

        <ul>

            @foreach($errors->all() as $error)

                <li>
                    {{ $error }}
                </li>

            @endforeach

        </ul>

    </div>

@endif


<div class="assessment-builder-card">

    <form
        method="POST"
        action="{{ route('manager.assessments.generate') }}"
    >

        @csrf


        {{-- Employee --}}

        <div class="form-section">

            <h3>
                1. Select Employee
            </h3>

            <div class="form-group">

                <label for="employee_id">
                    Employee
                </label>

                <select
                    name="employee_id"
                    id="employee_id"
                    required
                >

                    <option value="">
                        Select employee
                    </option>

                    @foreach($employees as $employee)

                        <option
                            value="{{ $employee->id }}"
                            {{ old('employee_id') == $employee->id
                                ? 'selected'
                                : '' }}
                        >

                            {{ $employee->name }}

                            @if($employee->email)
                                — {{ $employee->email }}
                            @endif

                        </option>

                    @endforeach

                </select>

            </div>

        </div>


        {{-- Categories --}}

        <div class="form-section">

            <h3>
                2. Select Cybersecurity Categories
            </h3>

            <p class="form-help">

                Questions will be randomly selected from
                the categories you choose.

            </p>


            <div class="category-grid">

                @foreach($categories as $category)

                    <label class="category-option">

                        <input
                            type="checkbox"
                            name="category_ids[]"
                            value="{{ $category->id }}"

                            {{ in_array(
                                $category->id,
                                old('category_ids', [])
                            )
                                ? 'checked'
                                : '' }}
                        >

                        <div>

                            <strong>
                                {{ $category->name }}
                            </strong>

                            @if($category->description)

                                <p>
                                    {{ $category->description }}
                                </p>

                            @endif

                        </div>

                    </label>

                @endforeach

            </div>

        </div>


        {{-- Quantity --}}

        <div class="form-section">

            <h3>
                3. Number of Questions
            </h3>

            <div class="quantity-container">

                <label for="question_quantity">
                    Number of questions
                </label>

                <input
                    type="number"
                    name="question_quantity"
                    id="question_quantity"
                    min="1"
                    max="200"
                    value="{{ old(
                        'question_quantity',
                        20
                    ) }}"
                    required
                >

                <small>
                    Questions will be selected randomly
                    from the selected categories.
                </small>

            </div>

        </div>

        <div class="form-group">
            <label for="assessment_name">
                Assessment Name
            </label>

            <input
                type="text"
                id="assessment_name"
                name="assessment_name"
                value="{{ old('assessment_name') }}"
                class="form-control"
                placeholder="e.g. Cybersecurity Awareness Assessment"
                required
            >

            @error('assessment_name')
                <div class="form-error">
                    {{ $message }}
                </div>
            @enderror
        </div>


        <div class="form-group">
        <label for="duration_minutes">
            Duration
        </label>

        <select
            id="duration_minutes"
            name="duration_minutes"
            class="form-control"
            required
        >
            <option value="15">
                15 minutes
            </option>

            <option value="30" selected>
                30 minutes
            </option>

            <option value="45">
                45 minutes
            </option>

            <option value="60">
                60 minutes
            </option>

            <option value="90">
                90 minutes
            </option>

            <option value="120">
                120 minutes
            </option>
        </select>

        @error('duration_minutes')
            <div class="form-error">
                {{ $message }}
            </div>
        @enderror
    </div>
            {{-- Submit --}}

        <div class="form-actions">

            <button
                type="submit"
                class="btn btn-primary"
            >

                🎲 Generate Random Assessment

            </button>

        </div>

    </form>

</div>

@endsection
