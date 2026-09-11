<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class OrganisationUserController extends Controller
{
    /**
     * Get the authenticated Organisation Admin's organisation ID.
     */
    private function organisationId(Request $request): int
    {
        $user = $request->user();

        abort_unless($user, 403);

        abort_unless(
            $user->hasRole('organisation-admin'),
            403
        );

        abort_unless(
            $user->organisation_id,
            403
        );

        return (int) $user->organisation_id;
    }

    /**
     * Display organisation users.
     */
    public function index(Request $request)
    {
        $organisationId = $this->organisationId($request);

        $search = trim((string) $request->input('search'));

        $usersQuery = User::query()
            ->where('organisation_id', $organisationId)
            ->with(['role', 'department'])
            ->orderBy('name');

        if ($search !== '') {
            $usersQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $usersQuery
            ->paginate(15)
            ->withQueryString();

        $employeeCount = User::query()
            ->where('organisation_id', $organisationId)
            ->whereHas(
                'role',
                fn ($query) => $query->where('slug', 'employee')
            )
            ->count();

        $managerCount = User::query()
            ->where('organisation_id', $organisationId)
            ->whereHas(
                'role',
                fn ($query) => $query->where('slug', 'manager')
            )
            ->count();

        $organisationAdminCount = User::query()
            ->where('organisation_id', $organisationId)
            ->whereHas(
                'role',
                fn ($query) => $query->where('slug', 'organisation-admin')
            )
            ->count();

        return view('organisation.users.index', compact(
            'users',
            'search',
            'employeeCount',
            'managerCount',
            'organisationAdminCount',
        ));
    }

    /**
     * Show create-user form.
     */
    public function create(Request $request)
    {
        $organisationId = $this->organisationId($request);

        $roles = Role::query()
            ->whereIn('slug', [
                'employee',
                'manager',
                'organisation-admin',
            ])
            ->orderByRaw("
                CASE slug
                    WHEN 'employee' THEN 1
                    WHEN 'manager' THEN 2
                    WHEN 'organisation-admin' THEN 3
                    ELSE 4
                END
            ")
            ->get();

        $departments = Department::query()
            ->where('organisation_id', $organisationId)
            ->orderBy('name')
            ->get();

        return view('organisation.users.create', compact(
            'roles',
            'departments',
        ));
    }

    /**
     * Store a new organisation user.
     */
    public function store(Request $request)
    {
        $organisationId = $this->organisationId($request);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'role_id' => [
                'required',
                'integer',
                Rule::exists('roles', 'id')
                    ->where(function ($query) {
                        $query->whereIn('slug', [
                            'employee',
                            'manager',
                            'organisation-admin',
                        ]);
                    }),
            ],

            'department_id' => [
                'nullable',
                'integer',
                Rule::exists('departments', 'id')
                    ->where(
                        fn ($query) =>
                            $query->where(
                                'organisation_id',
                                $organisationId
                            )
                    ),
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        $role = Role::query()
            ->whereKey($validated['role_id'])
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Department requirements
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $role->slug,
                ['employee', 'manager'],
                true
            )
            && empty($validated['department_id'])
        ) {
            return back()
                ->withErrors([
                    'department_id' =>
                        'A department is required for employees and managers.',
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Organisation Admins do not belong to a department
        |--------------------------------------------------------------------------
        */

        if ($role->slug === 'organisation-admin') {
            $validated['department_id'] = null;
        }

        /*
        |--------------------------------------------------------------------------
        | Create user
        |--------------------------------------------------------------------------
        */

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'],
            'organisation_id' => $organisationId,
            'department_id' => $validated['department_id'] ?? null,

            /*
             * Organisation-created users are immediately verified.
             */
            'email_verified_at' => now(),

            /*
             * New organisation users start active.
             */
            'status' => 'active',
        ]);

        return redirect()
            ->route('organisation.users')
            ->with(
                'success',
                'User created successfully.'
            );
    }

    /**
     * Show edit-user form.
     */
    public function edit(
        Request $request,
        User $user
    ) {
        $organisationId = $this->organisationId($request);

        $this->authoriseUser($request, $user);

        $roles = Role::query()
            ->whereIn('slug', [
                'employee',
                'manager',
                'organisation-admin',
            ])
            ->orderByRaw("
                CASE slug
                    WHEN 'employee' THEN 1
                    WHEN 'manager' THEN 2
                    WHEN 'organisation-admin' THEN 3
                    ELSE 4
                END
            ")
            ->get();

        $departments = Department::query()
            ->where('organisation_id', $organisationId)
            ->orderBy('name')
            ->get();

        return view('organisation.users.edit', compact(
            'user',
            'roles',
            'departments',
        ));
    }

    /**
     * Update an organisation user.
     */
    public function update(
        Request $request,
        User $user
    ) {
        $organisationId = $this->organisationId($request);

        $this->authoriseUser($request, $user);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($user->id),
            ],

            'role_id' => [
                'required',
                'integer',
                Rule::exists('roles', 'id')
                    ->where(function ($query) {
                        $query->whereIn('slug', [
                            'employee',
                            'manager',
                            'organisation-admin',
                        ]);
                    }),
            ],

            'department_id' => [
                'nullable',
                'integer',
                Rule::exists('departments', 'id')
                    ->where(
                        fn ($query) =>
                            $query->where(
                                'organisation_id',
                                $organisationId
                            )
                    ),
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],

            'status' => [
                'required',
                Rule::in([
                    'active',
                    'inactive',
                ]),
            ],
        ]);

        $role = Role::query()
            ->whereKey($validated['role_id'])
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Department requirements
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $role->slug,
                ['employee', 'manager'],
                true
            )
            && empty($validated['department_id'])
        ) {
            return back()
                ->withErrors([
                    'department_id' =>
                        'A department is required for employees and managers.',
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Organisation Admins do not belong to a department
        |--------------------------------------------------------------------------
        */

        if ($role->slug === 'organisation-admin') {
            $validated['department_id'] = null;
        }

        /*
        |--------------------------------------------------------------------------
        | Protect the currently logged-in Organisation Admin
        |--------------------------------------------------------------------------
        |
        | An Organisation Admin must not be able to deactivate their
        | own account.
        |
        */

        if (
            (int) $user->id === (int) $request->user()->id
            && $validated['status'] === 'inactive'
        ) {
            return back()
                ->withErrors([
                    'status' =>
                        'You cannot deactivate your own account.',
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Update user information
        |--------------------------------------------------------------------------
        */

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role_id = $validated['role_id'];

        $user->department_id =
            $role->slug === 'organisation-admin'
                ? null
                : ($validated['department_id'] ?? null);

        $user->status = $validated['status'];

        /*
        |--------------------------------------------------------------------------
        | Optional password change
        |--------------------------------------------------------------------------
        */

        if (! empty($validated['password'])) {
            $user->password = Hash::make(
                $validated['password']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Ensure verified access
        |--------------------------------------------------------------------------
        */

        if (! $user->email_verified_at) {
            $user->email_verified_at = now();
        }

        $user->save();

        return redirect()
            ->route('organisation.users')
            ->with(
                'success',
                'User updated successfully.'
            );
    }

    /**
     * Activate or deactivate an organisation user.
     */
    public function toggleStatus(
        Request $request,
        User $user
    ) {
        $this->organisationId($request);

        $this->authoriseUser($request, $user);

        /*
        |--------------------------------------------------------------------------
        | Prevent self-deactivation
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $request->user()->id !== (int) $user->id,
            403,
            'You cannot change your own account status.'
        );

        $user->status =
            $user->status === 'active'
                ? 'inactive'
                : 'active';

        $user->save();

        return redirect()
            ->route('organisation.users')
            ->with(
                'success',
                $user->name
                . ' is now '
                . (
                    $user->status === 'active'
                        ? 'active.'
                        : 'inactive.'
                )
            );
    }

    /**
     * Remove an organisation user.
     */
    public function destroy(
        Request $request,
        User $user
    ) {
        $this->organisationId($request);

        $this->authoriseUser($request, $user);

        /*
        |--------------------------------------------------------------------------
        | Prevent deleting your own account
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $request->user()->id !== (int) $user->id,
            403,
            'You cannot remove your own account from the organisation.'
        );

        $user->delete();

        return redirect()
            ->route('organisation.users')
            ->with(
                'success',
                'User removed successfully.'
            );
    }

    /**
     * Confirm that the user belongs to the same organisation.
     */
    private function authoriseUser(
        Request $request,
        User $user
    ): void {
        $organisationId = $this->organisationId($request);

        abort_unless(
            (int) $user->organisation_id === $organisationId,
            403
        );

        /*
        |--------------------------------------------------------------------------
        | Super Admins are never manageable from this controller.
        |--------------------------------------------------------------------------
        */

        abort_unless(
            ! $user->hasRole('super-admin'),
            403
        );
    }
}
