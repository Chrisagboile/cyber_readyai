<?php

namespace App\Modules\PensionAccountGuard\Services;

use App\Modules\PensionAccountGuard\Models\PensionGuardAuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class PensionGuardAuditService
{
    /**
     * Record an audit event using explicitly selected metadata.
     */
    public function record(
        string $action,
        ?string $targetType = null,
        ?int $targetId = null,
        array $metadata = [],
        ?Request $request = null
    ): void {
        try {
            $request ??= request();

            $actorId = Auth::guard('pension_guard')->id();

            $safeMetadata = $this->filterMetadata($metadata);

            PensionGuardAuditLog::create([
                'actor_id' => $actorId,
                'action' => $action,
                'target_type' => $targetType,
                'target_id' => $targetId,
                'metadata' => $safeMetadata,
                'ip_address' => $request->ip(),
                'user_agent' => substr(
                    (string) $request->userAgent(),
                    0,
                    500
                ),
            ]);
        } catch (Throwable $exception) {
            report($exception);
        }
    }

    /**
     * Allow only explicitly approved metadata fields.
     */
    private function filterMetadata(array $metadata): array
    {
        $allowedKeys = [
            'source_name',
            'source_type',
            'is_active',
            'changed_fields',
            'incident_title',
            'affected_organisation',
            'status',
        ];

        $safe = [];

        foreach ($allowedKeys as $key) {
            if (!array_key_exists($key, $metadata)) {
                continue;
            }

            $value = $metadata[$key];

            if ($key === 'changed_fields' && is_array($value)) {
                // Keep field names only; never store submitted field values.
                $safe[$key] = array_values(array_filter(
                    $value,
                    fn ($field) => is_string($field)
                        && in_array($field, [
                            'title',
                            'affected_organisation',
                            'description',
                            'source_name',
                            'source_url',
                            'reported_at',
                            'status',
                            'review_notes',
                        ], true)
                ));

                continue;
            }

            if (
                $key === 'is_active' && is_bool($value)
            ) {
                $safe[$key] = $value;
                continue;
            }

            if (
                in_array($key, [
                    'source_name',
                    'source_type',
                    'incident_title',
                    'affected_organisation',
                    'status',
                ], true)
                && is_string($value)
            ) {
                $safe[$key] = mb_substr($value, 0, 200);
            }
        }

        return $safe;
    }
}
