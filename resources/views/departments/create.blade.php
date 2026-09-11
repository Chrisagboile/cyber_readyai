@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="dashboard-header">
        <div>
            <h1 class="dashboard-title">Create Department</h1>
            <p class="dashboard-description">
                Create a new department and assign its manager(s).
            </p>
        </div>

        <div>
            <a
                href="{{ route('departments.index') }}"
                class="btn btn-outline-secondary"
            >
                ← Back to Departments
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please correct the following:</strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="overview-card">

        <div class="section-heading">
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
        >
            @csrf

            <div class="row g-4">

                {{-- Organisation --}}
                <div class="col-md-6">
                    <label for="organisation_id" class="form-label">
                        Organisation
                    </label>

                    @if(auth()->user()->hasRole('super-admin'))

                        <select
                            name="organisation_id"
                            id="organisation_id"
                            class="form-select"
                            required
                        >
                            <option value="">
                                Select organisation
                            </option>

                            @foreach($organisations as $organisation)
                                <option
                                    value="{{ $organisation->id }}"
                                    @selected(
                                        old('organisation_id') == $organisation->id
                                    )
                                >
                                    {{ $organisation->name }}
                                </option>
                            @endforeach
                        </select>

                    @else

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $organisations->first()->name ?? '' }}"
                            disabled
                        >

                        <input
                            type="hidden"
                            name="organisation_id"
                            value="{{ $organisations->first()->id ?? '' }}"
                        >

                    @endif
                </div>

                {{-- Department Name --}}
                <div class="col-md-6">
                    <label for="name" class="form-label">
                        Department Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        id="name"
                        class="form-control"
                        value="{{ old('name') }}"
                        placeholder="e.g. Information Technology"
                        required
                    >

                    <small class="text-muted">
                        Enter the name of the department.
                    </small>
                </div>

                {{-- Slug --}}
                <div class="col-md-6">
                    <label for="slug" class="form-label">
                        Slug
                    </label>

                    <input
                        type="text"
                        name="slug"
                        id="slug"
                        class="form-control"
                        value="{{ old('slug') }}"
                        placeholder="information-technology"
                    >

                    <small class="text-muted">
                        Leave blank to generate the slug automatically.
                    </small>
                </div>

                {{-- Status --}}
                <div class="col-md-6">
                    <label for="status" class="form-label">
                        Status
                    </label>

                    <select
                        name="status"
                        id="status"
                        class="form-select"
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

                {{-- Description --}}
                <div class="col-12">
                    <label for="description" class="form-label">
                        Description
                    </label>

                    <textarea
                        name="description"
                        id="description"
                        rows="4"
                        class="form-control"
                        placeholder="Describe the purpose and responsibilities of this department..."
                    >{{ old('description') }}</textarea>
                </div>

                {{-- Managers --}}
                <div class="col-12">
                    <label for="manager_ids" class="form-label">
                        Department Manager(s)
                    </label>

                    <select
                        name="manager_ids[]"
                        id="manager_ids"
                        class="form-select"
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

                    <small class="text-muted">
                        Hold Ctrl on Windows or Command on Mac to select
                        multiple managers.
                    </small>
                </div>

            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">

                <a
                    href="{{ route('departments.index') }}"
                    class="btn btn-outline-secondary"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Create Department
                </button>

            </div>

        </form>

    </div>

</div>
@endsection
