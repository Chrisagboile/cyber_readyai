<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Department;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class SuperAdminOrganisationController extends Controller
{
    private function authorise(Request $request): void
    {
        abort_unless(
            $request->user()
            && $request->user()->hasRole('super-admin'),
            403
        );
    }

    public function index(Request $request)
    {
        $this->authorise($request);

        $search = trim((string) $request->input('search'));

        $query = Organisation::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . $search . '%');
            });
        }

        $organisations = $query
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Platform statistics for displayed organisations
        |--------------------------------------------------------------------------
        */

        $organisationIds = $organisations
            ->getCollection()
            ->pluck('id')
            ->values();

        $userCounts = User::query()
            ->whereIn('organisation_id', $organisationIds)
            ->selectRaw('organisation_id, COUNT(*) as total')
            ->groupBy('organisation_id')
            ->pluck('total', 'organisation_id');

        $employeeCounts = User::query()
            ->whereIn('organisation_id', $organisationIds)
            ->whereHas('role', function ($query) {
                $query->where('slug', 'employee');
            })
            ->selectRaw('organisation_id, COUNT(*) as total')
            ->groupBy('organisation_id')
            ->pluck('total', 'organisation_id');

        $managerCounts = User::query()
            ->whereIn('organisation_id', $organisationIds)
            ->whereHas('role', function ($query) {
                $query->where('slug', 'manager');
            })
            ->selectRaw('organisation_id, COUNT(*) as total')
            ->groupBy('organisation_id')
            ->pluck('total', 'organisation_id');

        $adminCounts = User::query()
            ->whereIn('organisation_id', $organisationIds)
            ->whereHas('role', function ($query) {
                $query->where('slug', 'organisation-admin');
            })
            ->selectRaw('organisation_id, COUNT(*) as total')
            ->groupBy('organisation_id')
            ->pluck('total', 'organisation_id');

        $departmentCounts = Department::query()
            ->whereIn('organisation_id', $organisationIds)
            ->selectRaw('organisation_id, COUNT(*) as total')
            ->groupBy('organisation_id')
            ->pluck('total', 'organisation_id');

        $assessmentCounts = Assessment::query()
            ->whereIn('organisation_id', $organisationIds)
            ->selectRaw('organisation_id, COUNT(*) as total')
            ->groupBy('organisation_id')
            ->pluck('total', 'organisation_id');

        $organisations = $organisations->through(
            function ($organisation) use (
                $userCounts,
                $employeeCounts,
                $managerCounts,
                $adminCounts,
                $departmentCounts,
                $assessmentCounts
            ) {
                $organisation->platform_stats = (object) [
                    'users' => (int) ($userCounts[$organisation->id] ?? 0),
                    'employees' => (int) ($employeeCounts[$organisation->id] ?? 0),
                    'managers' => (int) ($managerCounts[$organisation->id] ?? 0),
                    'admins' => (int) ($adminCounts[$organisation->id] ?? 0),
                    'departments' => (int) ($departmentCounts[$organisation->id] ?? 0),
                    'assessments' => (int) ($assessmentCounts[$organisation->id] ?? 0),
                ];

                return $organisation;
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Overall platform counts
        |--------------------------------------------------------------------------
        */

        $totalOrganisations = Organisation::count();

        $activeOrganisations = Organisation::query()
            ->where('status', 'active')
            ->count();

        $inactiveOrganisations = Organisation::query()
            ->where(function ($query) {
                $query
                    ->where('status', 'inactive')
                    ->orWhereNull('status');
            })
            ->count();

        return view('tenants.index', [
            'organisations' => $organisations,
            'search' => $search,
            'totalOrganisations' => $totalOrganisations,
            'activeOrganisations' => $activeOrganisations,
            'inactiveOrganisations' => $inactiveOrganisations,
        ]);
    }

    public function create(Request $request)
    {
        $this->authorise($request);

        return view('tenants.create');
    }

    public function store(Request $request)
    {
        $this->authorise($request);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'nullable',
                'string',
                'max:255',
                'alpha_dash',
                'unique:organisations,slug',
            ],

            'status' => [
                'required',
                Rule::in(['active', 'inactive']),
            ],
        ]);

        $name = trim($validated['name']);

        $slug = trim(
            (string) ($validated['slug'] ?? '')
        );

        if ($slug === '') {
            $slug = Str::slug($name);
        }

        /*
        |--------------------------------------------------------------------------
        | Ensure generated slug is unique
        |--------------------------------------------------------------------------
        */

        $baseSlug = $slug;
        $counter = 2;

        while (
            Organisation::where('slug', $slug)->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $organisation = Organisation::create([
            'name' => $name,
            'slug' => $slug,
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('tenants.index')
            ->with(
                'success',
                'Organisation created successfully.'
            );
    }

    public function edit(
        Request $request,
        Organisation $organisation
    ) {
        $this->authorise($request);

        return view(
            'tenants.edit',
            compact('organisation')
        );
    }

    public function update(
        Request $request,
        Organisation $organisation
    ) {
        $this->authorise($request);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('organisations', 'slug')
                    ->ignore($organisation->id),
            ],

            'status' => [
                'required',
                Rule::in(['active', 'inactive']),
            ],
        ]);

        $organisation->update([
            'name' => trim($validated['name']),
            'slug' => trim($validated['slug']),
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('tenants.index')
            ->with(
                'success',
                'Organisation updated successfully.'
            );
    }

    public function toggleStatus(
        Request $request,
        Organisation $organisation
    ) {
        $this->authorise($request);

        $newStatus = strtolower(
            (string) $organisation->status
        ) === 'active'
            ? 'inactive'
            : 'active';

        $organisation->update([
            'status' => $newStatus,
        ]);

        return back()->with(
            'success',
            'Organisation status changed to ' .
            ucfirst($newStatus) .
            '.'
        );
    }
}
