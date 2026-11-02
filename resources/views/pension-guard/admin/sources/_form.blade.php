@if ($errors->any())
    <section class="panel" role="alert">
        <h3>Please correct the following errors</h3>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </section>
@endif

<div>
    <label for="name">Source name</label>
    <input
        id="name"
        name="name"
        type="text"
        value="{{ old('name', $source->name) }}"
        maxlength="255"
        required
    >
</div>

<div>
    <label for="url">Official website URL</label>
    <input
        id="url"
        name="url"
        type="url"
        value="{{ old('url', $source->url) }}"
        maxlength="2048"
        required
    >
</div>

<div>
    <label for="source_type">Category</label>
    <select id="source_type" name="source_type" required>
        @foreach ([
            'scam_warnings' => 'Scam warnings',
            'pension_guidance' => 'Pension guidance',
            'cybersecurity_guidance' => 'Cybersecurity guidance',
            'guidance' => 'General guidance',
        ] as $value => $label)
            <option
                value="{{ $value }}"
                @selected(old('source_type', $source->source_type ?: 'guidance') === $value)
            >
                {{ $label }}
            </option>
        @endforeach
    </select>
</div>

<div>
    <label for="purpose">Purpose and relevance</label>
    <textarea
        id="purpose"
        name="purpose"
        rows="4"
        maxlength="2000"
        required
    >{{ old('purpose', $source->purpose) }}</textarea>
</div>

<div>
    <label for="last_reviewed_at">Last reviewed</label>
    <input
        id="last_reviewed_at"
        name="last_reviewed_at"
        type="date"
        value="{{ old('last_reviewed_at', $source->last_reviewed_at?->format('Y-m-d')) }}"
    >
</div>

<div>
    <label for="next_review_due">Next review due</label>
    <input
        id="next_review_due"
        name="next_review_due"
        type="date"
        value="{{ old('next_review_due', $source->next_review_due?->format('Y-m-d')) }}"
    >
</div>

<div>
    <input type="hidden" name="is_active" value="0">
    <label>
        <input
            type="checkbox"
            name="is_active"
            value="1"
            @checked((bool) old('is_active', $source->exists ? $source->is_active : true))
        >
        Active — show this source in the public register
    </label>
</div>

<button type="submit">{{ $buttonLabel }}</button>