<?php

namespace App\Modules\PensionAccountGuard\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\PensionAccountGuard\Models\PensionGuardAuditLog;
use App\Modules\PensionAccountGuard\Models\PensionGuardSecurityIncident;
use App\Modules\PensionAccountGuard\Services\PensionGuardAuditService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PensionGuardSecurityIncidentController extends Controller
{
    public function __construct(
        private PensionGuardAuditService $audit
    ) {
    }

    public function index(Request $request)
    {
        $query = PensionGuardSecurityIncident::query()
            ->orderByDesc('reported_at')
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();

            $query->where(function ($builder) use ($search) {
                $builder->where('title', 'like', "%{$search}%")
                    ->orWhere('affected_organisation', 'like', "%{$search}%");
            });
        }

        return view('pension-guard.admin.incidents.index', [
            'incidents' => $query->paginate(20)->withQueryString(),
            'statuses' => PensionGuardSecurityIncident::allowedStatuses(),
            'statusFilter' => $request->string('status')->toString(),
            'searchFilter' => $request->string('search')->toString(),
        ]);
    }

    public function create()
    {
        return view('pension-guard.admin.incidents.create', [
            'statuses' => PensionGuardSecurityIncident::allowedStatuses(),
            'incident' => new PensionGuardSecurityIncident(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateIncident($request);

        $actorId = $request->user('pension_guard')?->getAuthIdentifier();

        $validated['created_by'] = $actorId;
        $validated['updated_by'] = $actorId;

        $incident = PensionGuardSecurityIncident::create($validated);

        $this->audit->record(
            'security_incident.created',
            'security_incident',
            $incident->id,
            [
                'incident_title' => $incident->title,
                'affected_organisation' => $incident->affected_organisation,
                'status' => $incident->status,
            ],
            $request
        );

        return redirect()
            ->route('pension-guard.admin.incidents.index')
            ->with('success', 'Security incident recorded.');
    }

    public function edit(PensionGuardSecurityIncident $incident)
    {
        return view('pension-guard.admin.incidents.edit', [
            'incident' => $incident,
            'statuses' => PensionGuardSecurityIncident::allowedStatuses(),
        ]);
    }

    public function update(
        Request $request,
        PensionGuardSecurityIncident $incident
    ) {
        $validated = $this->validateIncident($request);

        $changedFields = [];

        foreach (array_keys($validated) as $field) {
            if ($incident->getAttribute($field) != $validated[$field]) {
                $changedFields[] = $field;
            }
        }

        $validated['updated_by'] =
            $request->user('pension_guard')?->getAuthIdentifier();

        $incident->update($validated);

        if ($changedFields !== []) {
            $this->audit->record(
                'security_incident.updated',
                'security_incident',
                $incident->id,
                [
                    'incident_title' => $incident->title,
                    'affected_organisation' => $incident->affected_organisation,
                    'status' => $incident->status,
                    'changed_fields' => $changedFields,
                ],
                $request
            );
        }

        return redirect()
            ->route('pension-guard.admin.incidents.index')
            ->with('success', 'Security incident updated.');
    }

    private function validateIncident(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'affected_organisation' => ['nullable', 'string', 'max:200'],
            'description' => ['required', 'string', 'max:10000'],
            'source_name' => ['required', 'string', 'max:200'],
            'source_url' => ['required', 'url', 'max:2048'],
            'reported_at' => ['nullable', 'date'],
            'status' => [
                'required',
                Rule::in(PensionGuardSecurityIncident::allowedStatuses()),
            ],
            'review_notes' => ['nullable', 'string', 'max:10000'],
        ]);
    }
}
