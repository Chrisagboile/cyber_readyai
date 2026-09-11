<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
class DepartmentController extends Controller
{
    public function index(Request $request)
    {
       /* dd(
        auth()->id(),
        auth()->user()?->name,
        auth()->user()?->role?->slug,
        auth()->user()?->organisation_id,
        auth()->user()?->department_id
    ); */
        $user = $request->user();
/*
        $query = Department::with(['organisation', 'managers'])
            ->withCount('users')
            ->orderBy('name');
*/
        $query = Department::with(['organisation', 'managers'])
            ->withCount(['users', 'employees'])
            ->orderBy('name');

        if ($user->hasRole('super-admin')) {
            // Super Admin can see all organisations.
        } elseif ($user->hasRole('organisation-admin')) {
            $query->where('organisation_id', $user->organisation_id);
        } elseif ($user->hasRole('manager')) {
            $query->where('id', $user->department_id);
        } else {
            abort(403);
        }

        $departments = $query->paginate(15);

        return view('departments.index', compact('departments'));
    }

    public function create(Request $request)
    {
        $user = $request->user();

        if (! $user->hasAnyRole(['super-admin', 'organisation-admin'])) {
            abort(403);
        }

        if ($user->hasRole('super-admin')) {
            $organisations = Organisation::where('status', 'active')
                ->orderBy('name')
                ->get();

            $managers = User::whereHas('role', function ($query) {
                $query->where('slug', 'manager');
            })
                ->orderBy('name')
                ->get();
        } else {
            $organisations = Organisation::where('id', $user->organisation_id)
                ->where('status', 'active')
                ->get();

            $managers = User::where('organisation_id', $user->organisation_id)
                ->whereHas('role', function ($query) {
                    $query->where('slug', 'manager');
                })
                ->orderBy('name')
                ->get();
        }

        return view('departments.create', compact(
            'organisations',
            'managers'
        ));
    }

    public function store(Request $request)
    {
        $user = $request->user();

        if (! $user->hasAnyRole(['super-admin', 'organisation-admin'])) {
            abort(403);
        }

        $organisationId = $request->input('organisation_id');

        if ($user->hasRole('organisation-admin')) {
            $organisationId = $user->organisation_id;
        }

        $validated = $request->validate([
            'organisation_id' => [
                'nullable',
                'integer',
                'exists:organisations,id',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'status' => [
                'required',
                'in:active,inactive',
            ],
            'manager_ids' => [
                'nullable',
                'array',
            ],
            'manager_ids.*' => [
                'integer',
                'exists:users,id',
            ],
        ]);

        if (! $organisationId) {
            abort(422, 'An organisation is required.');
        }

        if ($user->hasRole('organisation-admin')
            && (int) $organisationId !== (int) $user->organisation_id) {
            abort(403);
        }

        $slug = Str::slug(
            $validated['slug'] ?: $validated['name']
        );

        $slugExists = Department::where('organisation_id', $organisationId)
            ->where('slug', $slug)
            ->exists();

        if ($slugExists) {
            return back()
                ->withInput()
                ->withErrors([
                    'slug' => 'This department already exists in this organisation.',
                ]);
        }

        $department = Department::create([
            'organisation_id' => $organisationId,
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
        ]);

        $managerIds = $this->validManagerIds(
            $validated['manager_ids'] ?? [],
            $organisationId
        );

        $department->managers()->sync($managerIds);

        return redirect()
            ->route('departments.index')
            ->with('success', 'Department created successfully.');
    }

    public function show(Request $request, Department $department)
    {
        $this->authorizeDepartmentAccess($department);
        $department->load([
            'organisation',
            'managers',
            'users.role',
        ]);

        return view('departments.show', compact('department'));
    }

    public function edit(Request $request, Department $department)
    {
        $user = $request->user();

        if (! $user->hasAnyRole(['super-admin', 'organisation-admin'])) {
            abort(403);
        }

        $this->authorizeDepartmentAccess($user, $department);

        $organisations = $user->hasRole('super-admin')
            ? Organisation::where('status', 'active')->orderBy('name')->get()
            : Organisation::where('id', $user->organisation_id)->get();

        $managers = User::where(
            'organisation_id',
            $department->organisation_id
        )
            ->whereHas('role', function ($query) {
                $query->where('slug', 'manager');
            })
            ->orderBy('name')
            ->get();

        $department->load('managers');

        return view('departments.edit', compact(
            'department',
            'organisations',
            'managers'
        ));
    }

    public function update(
        Request $request,
        Department $department
    ) {
        $user = $request->user();

        if (! $user->hasAnyRole(['super-admin', 'organisation-admin'])) {
            abort(403);
        }

        $this->authorizeDepartmentAccess($user, $department);

        $validated = $request->validate([
            'organisation_id' => [
                'required',
                'integer',
                'exists:organisations,id',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'status' => [
                'required',
                'in:active,inactive',
            ],
            'manager_ids' => [
                'nullable',
                'array',
            ],
            'manager_ids.*' => [
                'integer',
                'exists:users,id',
            ],
        ]);

        if (
            $user->hasRole('organisation-admin')
            && (int) $validated['organisation_id']
                !== (int) $user->organisation_id
        ) {
            abort(403);
        }

        $department->update([
            'organisation_id' => $validated['organisation_id'],
            'name' => $validated['name'],
            'slug' => Str::slug(
                $validated['slug'] ?: $validated['name']
            ),
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
        ]);

        $managerIds = $this->validManagerIds(
            $validated['manager_ids'] ?? [],
            $department->organisation_id
        );

        $department->managers()->sync($managerIds);

        return redirect()
            ->route('departments.show', $department)
            ->with('success', 'Department updated successfully.');
    }

    public function destroy(Request $request, Department $department)
    {
        $user = $request->user();

        if (! $user->hasAnyRole(['super-admin', 'organisation-admin'])) {
            abort(403);
        }

        $this->authorizeDepartmentAccess($user, $department);

        if ($department->users()->exists()) {
            return redirect()
                ->route('departments.index')
                ->with(
                    'error',
                    'This department cannot be deleted because it still has users assigned to it.'
                );
        }

        if ($department->assessments()->exists()) {
            return redirect()
                ->route('departments.index')
                ->with(
                    'error',
                    'This department cannot be deleted because it has assessments associated with it.'
                );
        }

        $department->delete();

        return redirect()
            ->route('departments.index')
            ->with('success', 'Department deleted successfully.');
    }
private function authorizeDepartmentAccess(Department $department): void
{
    $user = auth()->user();

    // Super Admin can access every department.
    if ($user->hasRole('super-admin')) {
        return;
    }

    // Organisation Admin can access departments
    // within their own organisation.
    if ($user->hasRole('organisation-admin')) {
        if (
            $user->organisation_id &&
            (int) $user->organisation_id === (int) $department->organisation_id
        ) {
            return;
        }

        abort(403);
    }

    // Manager can access ONLY their assigned department.
    if ($user->hasRole('manager')) {
            if (
                $user->department_id &&
                (int) $user->department_id === (int) $department->id
            ) {
                return;
            }

            abort(403);
        }

        // Everyone else is denied.
        abort(403);
    }
/*
    private function authorizeDepartmentAccess(
        User $user,
        Department $department
    ): void {
        if ($user->hasRole('super-admin')) {
            return;
        }

        if (
            $user->organisation_id
            && (int) $department->organisation_id
                === (int) $user->organisation_id
        ) {
            return;
        }

        abort(403);
    }
*/
    private function validManagerIds(
        array $managerIds,
        int $organisationId
    ): array {
        return User::whereIn('id', $managerIds)
            ->where('organisation_id', $organisationId)
            ->whereHas('role', function ($query) {
                $query->where('slug', 'manager');
            })
            ->pluck('id')
            ->all();
    }
}
