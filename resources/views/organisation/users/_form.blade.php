<div class="user-form-grid">

    {{-- Full Name --}}
    <div class="user-form-field">
        <label for="name">Full Name</label>

        <input
            id="name"
            type="text"
            name="name"
            value="{{ old('name', $user->name ?? '') }}"
            required
        >

        @error('name')
            <span class="user-form-error">{{ $message }}</span>
        @enderror
    </div>


    {{-- Email --}}
    <div class="user-form-field">
        <label for="email">Email Address</label>

        <input
            id="email"
            type="email"
            name="email"
            value="{{ old('email', $user->email ?? '') }}"
            required
        >

        @error('email')
            <span class="user-form-error">{{ $message }}</span>
        @enderror
    </div>


    {{-- Role --}}
    <div class="user-form-field">
        <label for="role_id">Role</label>

        <select
            id="role_id"
            name="role_id"
            required
        >
            <option value="">Select role</option>

            @foreach($roles as $role)
                <option
                    value="{{ $role->id }}"
                    @selected(
                        (string) old(
                            'role_id',
                            $user->role_id ?? ''
                        ) === (string) $role->id
                    )
                >
                    {{ ucwords(str_replace('-', ' ', $role->slug)) }}
                </option>
            @endforeach

        </select>

        @error('role_id')
            <span class="user-form-error">{{ $message }}</span>
        @enderror
    </div>


    {{-- Department --}}
    <div class="user-form-field">
        <label for="department_id">Department</label>

        <select
            id="department_id"
            name="department_id"
        >
            <option value="">No department</option>

            @foreach($departments as $department)
                <option
                    value="{{ $department->id }}"
                    @selected(
                        (string) old(
                            'department_id',
                            $user->department_id ?? ''
                        ) === (string) $department->id
                    )
                >
                    {{ $department->name }}
                </option>
            @endforeach

        </select>

        @error('department_id')
            <span class="user-form-error">{{ $message }}</span>
        @enderror

        <small>
            Employees and managers must belong to a department.
            Organisation Admins do not require one.
        </small>
    </div>


    {{-- Account Status --}}
    @isset($user)
        <div class="user-form-field">
            <label for="status">Account Status</label>

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
                            $user->status ?? 'active'
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
                            $user->status ?? 'active'
                        ) === 'inactive'
                    )
                >
                    Inactive
                </option>
            </select>

            @error('status')
                <span class="user-form-error">{{ $message }}</span>
            @enderror

            <small>
                Inactive users should not be able to access the organisation.
            </small>
        </div>
    @endisset


    {{-- Password --}}
    <div class="user-form-field">
        <label for="password">
            Password

            @isset($user)
                <small>
                    (leave blank to keep the current password)
                </small>
            @endisset
        </label>

        <input
            id="password"
            type="password"
            name="password"
            {{ isset($user) ? '' : 'required' }}
        >

        @error('password')
            <span class="user-form-error">{{ $message }}</span>
        @enderror
    </div>


    {{-- Confirm Password --}}
    <div class="user-form-field">
        <label for="password_confirmation">
            Confirm Password
        </label>

        <input
            id="password_confirmation"
            type="password"
            name="password_confirmation"
            {{ isset($user) ? '' : 'required' }}
        >
    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const roleSelect = document.getElementById('role_id');
    const departmentSelect = document.getElementById('department_id');

    if (!roleSelect || !departmentSelect) {
        return;
    }

    function updateDepartmentState() {

        const selectedOption =
            roleSelect.options[roleSelect.selectedIndex];

        const selectedRole =
            selectedOption
                ? selectedOption.text.toLowerCase()
                : '';

        if (selectedRole.includes('organisation admin')) {

            departmentSelect.value = '';
            departmentSelect.disabled = true;

        } else {

            departmentSelect.disabled = false;

        }
    }

    roleSelect.addEventListener(
        'change',
        updateDepartmentState
    );

    updateDepartmentState();
});
</script>
