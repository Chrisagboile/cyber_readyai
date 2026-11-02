@if($errors->any())
    <div role="alert" style="background:#fdecec; color:#8b1e1e; padding:12px; margin-bottom:16px; border-radius:6px;">
        <strong>Please correct the following:</strong>
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div style="display:grid; gap:18px; max-width:760px;">
    <div>
        <label for="title">Incident title *</label>
        <input id="title" name="title" type="text" required maxlength="200"
               value="{{ old('title', $incident->title) }}"
               style="display:block; width:100%; padding:10px; margin-top:6px; box-sizing:border-box;">
    </div>

    <div>
        <label for="affected_organisation">Affected organisation</label>
        <input id="affected_organisation" name="affected_organisation" type="text" maxlength="200"
               value="{{ old('affected_organisation', $incident->affected_organisation) }}"
               style="display:block; width:100%; padding:10px; margin-top:6px; box-sizing:border-box;">
    </div>

    <div>
        <label for="description">Incident description *</label>
        <textarea id="description" name="description" required maxlength="10000" rows="5"
                  style="display:block; width:100%; padding:10px; margin-top:6px; box-sizing:border-box;">{{ old('description', $incident->description) }}</textarea>
    </div>

    <div>
        <label for="source_name">Source name *</label>
        <input id="source_name" name="source_name" type="text" required maxlength="200"
               value="{{ old('source_name', $incident->source_name) }}"
               style="display:block; width:100%; padding:10px; margin-top:6px; box-sizing:border-box;">
    </div>

    <div>
        <label for="source_url">Source URL *</label>
        <input id="source_url" name="source_url" type="url" required maxlength="2048"
               value="{{ old('source_url', $incident->source_url) }}"
               placeholder="https://example.com/report"
               style="display:block; width:100%; padding:10px; margin-top:6px; box-sizing:border-box;">
    </div>

    <div>
        <label for="reported_at">Reported date</label>
        <input id="reported_at" name="reported_at" type="date"
               value="{{ old('reported_at', $incident->reported_at?->format('Y-m-d')) }}"
               style="display:block; padding:10px; margin-top:6px;">
    </div>

    <div>
        <label for="status">Verification status *</label>
        <select id="status" name="status" required
                style="display:block; padding:10px; margin-top:6px;">
            @foreach($statuses as $status)
                <option value="{{ $status }}"
                    @selected(old('status', $incident->status ?: 'unverified') === $status)>
                    {{ ucwords(str_replace('_', ' ', $status)) }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="review_notes">Administrator review notes</label>
        <textarea id="review_notes" name="review_notes" maxlength="10000" rows="4"
                  style="display:block; width:100%; padding:10px; margin-top:6px; box-sizing:border-box;">{{ old('review_notes', $incident->review_notes) }}</textarea>
    </div>

    <div style="display:flex; gap:12px; flex-wrap:wrap;">
        <button type="submit"
                style="padding:11px 18px; background:#174ea6; color:white; border:0; border-radius:6px;">
            {{ $buttonText }}
        </button>
        <a href="{{ route('pension-guard.admin.incidents.index') }}" style="padding:11px;">
            Cancel
        </a>
    </div>
</div>
