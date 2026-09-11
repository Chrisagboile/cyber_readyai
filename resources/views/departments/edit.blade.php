@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="dashboard-header">
        <div>
            <h1 class="dashboard-title">Edit Department</h1>
            <p class="dashboard-description">
                Update department details, status, and manager assignments.
            </p>
        </div>

        <div>
            <a
                href="{{ route('departments.show', $department) }}"
                class="btn btn-outline-secondary"
            >
                ← Back to Department
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
                    Editing {{ $department->name }}
                </p>
            </div>
        </div>

        <form
            method="POST"
            action="{{ route('departments.update', $department) }}"
        >
            @csrf
            @method('PUT')

            <div class="row g-4">

                @if(auth()->user()->hasRole('super-admin'))
                    <div class="col-md-6">
                        <label for="organisation_id" class="form-label">
                            Organisation
                        </label>

                        <select
                            name="organisation_id"
                            id="organisation_id"
                            class="form-select"
                            required
                        >
                            <option value="">Select organisation</option>

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
                    </div>
                @else
                    <div class="col-md-6">
                        <label class="form-label">
                            Organisation
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $department->organisation->name ?? '' }}"
                            disabled
                        />

                        <input
                            type="hidden"
                            name="organisation_id"
                            value="{{ $department->organisation_id }}"
                        />
                    </div>
                @endif

                <div class="col-md-6">
                    <label for="name" class="form-label">
                        Department Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        id="name"
                        class="form-control"
                        value="{{ old('name', $department->name) }}"
                        placeholder="e.g. Information Technology"
                        required
                    >
                </div>

                <div class="col-md-6">
                    <label for="slug" class="form-label">
                        Slug
                    </label>

                    <input
                        type="text"
                        name="slug"
                        id="slug"
                        class="form-control"
                        value="{{ old('slug', $department->slug) }}"
                        placeholder="information-technology"
                    >

                    <small class="text-muted">
                        Leave blank to generate automatically from the department name.
                    </small>
                </div>

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
                            @selected(old('status', $department->status) === 'active')
                        >
                            Active
                        </option>

                        <option
                            value="inactive"
                            @selected(old('status', $department->status) === 'inactive')
                        >
                            Inactive
                        </option>
                    </select>
                </div>

                <div class="col-12">
                    <label for="description" class="form-label">
                        Description
                    </label>

                    <textarea
                        name="description"
                        id="description"
                        rows="4"
                        class="form-control"
                        placeholder="Describe the purpose or responsibilities of this department..."
                    >{{ old('description', $department->description) }}</textarea>
                </div>

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
                                        old(
                                            'manager_ids',
                                            $department->managers->pluck('id')->all()
                                        )
                                    )
                                )
                            >
                                {{ $manager->name }}
                                ({{ $manager->username }})
                            </option>
                        @endforeach
                    </select>

                    <small class="text-muted">
                        Hold Ctrl (Windows) or Command (Mac) to select multiple managers.
                    </small>
                </div>

            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">

                <a
                    href="{{ route('departments.show', $department) }}"
                    class="btn btn-outline-secondary"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Save Changes
                </button>

            </div>

        </form>

    </div>

</div>
@endsection
