<?php

namespace App\Modules\PensionAccountGuard\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\PensionAccountGuard\Models\PensionGuardOfficialSource;
use App\Modules\PensionAccountGuard\Services\PensionGuardAuditService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PensionGuardSourceAdminController extends Controller
{
    public function index()
    {
        $sources = PensionGuardOfficialSource::query()
            ->orderBy('name')
            ->paginate(15);

        return view('pension-guard.admin.sources.index', [
            'sources' => $sources,
        ]);
    }

    public function create()
    {
        return view('pension-guard.admin.sources.create', [
            'source' => new PensionGuardOfficialSource(),
        ]);
    }

    public function store(
        Request $request,
        PensionGuardAuditService $auditService
    ) {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:pension_guard_official_sources,name',
            ],
            'url' => ['required', 'url', 'max:2048'],
            'source_type' => [
                'required',
                Rule::in([
                    'scam_warnings',
                    'pension_guidance',
                    'cybersecurity_guidance',
                    'guidance',
                ]),
            ],
            'purpose' => ['required', 'string', 'max:2000'],
            'last_reviewed_at' => ['nullable', 'date'],
            'next_review_due' => [
                'nullable',
                'date',
                'after_or_equal:last_reviewed_at',
            ],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $source = PensionGuardOfficialSource::create($validated);

        $auditService->record(
            'official_source.created',
            'official_source',
            (int) $source->id,
            [
                'source_name' => $source->name,
                'source_type' => $source->source_type,
                'is_active' => (bool) $source->is_active,
            ],
            $request
        );

        return redirect()
            ->route('pension-guard.admin.sources.index')
            ->with('success', 'Official source added successfully.');
    }

    public function edit(PensionGuardOfficialSource $source)
    {
        return view('pension-guard.admin.sources.edit', [
            'source' => $source,
        ]);
    }

    public function update(
        Request $request,
        PensionGuardOfficialSource $source,
        PensionGuardAuditService $auditService
    ) {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('pension_guard_official_sources', 'name')
                    ->ignore($source->id),
            ],
            'url' => ['required', 'url', 'max:2048'],
            'source_type' => [
                'required',
                Rule::in([
                    'scam_warnings',
                    'pension_guidance',
                    'cybersecurity_guidance',
                    'guidance',
                ]),
            ],
            'purpose' => ['required', 'string', 'max:2000'],
            'last_reviewed_at' => ['nullable', 'date'],
            'next_review_due' => [
                'nullable',
                'date',
                'after_or_equal:last_reviewed_at',
            ],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        // Capture original values so the audit records field names,
        // not sensitive or unnecessary before-and-after values.
        $original = $source->only([
            'name',
            'url',
            'source_type',
            'purpose',
            'last_reviewed_at',
            'next_review_due',
            'is_active',
        ]);

        $source->update($validated);
        $source->refresh();

        $changedFields = [];

        foreach ($original as $field => $oldValue) {
            $newValue = $source->{$field};

            if ((string) ($oldValue ?? '') !== (string) ($newValue ?? '')) {
                $changedFields[] = $field;
            }
        }

        $auditService->record(
            'official_source.updated',
            'official_source',
            (int) $source->id,
            [
                'source_name' => $source->name,
                'source_type' => $source->source_type,
                'is_active' => (bool) $source->is_active,
                'changed_fields' => $changedFields,
            ],
            $request
        );

        return redirect()
            ->route('pension-guard.admin.sources.index')
            ->with('success', 'Official source updated successfully.');
    }
}
