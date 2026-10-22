
@extends('layouts.app')

@section('content')
<div class="phishing-show">
    <style>
        .phishing-show {
            --ps-ink: #17243b;
            --ps-muted: #64748b;
            --ps-line: #e2e8f0;
            --ps-blue: #2563eb;
            --ps-bg: #f5f7fb;
            color: var(--ps-ink);
            padding: 28px 0 44px;
        }

        .phishing-show * {
            box-sizing: border-box;
        }

        .phishing-show .ps-container {
            width: min(1180px, calc(100% - 32px));
            margin: 0 auto;
        }

        .phishing-show .ps-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            color: #475569;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .phishing-show .ps-back:hover {
            color: var(--ps-blue);
        }

        .phishing-show .ps-alert {
            padding: 13px 16px;
            border-radius: 12px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        .phishing-show .ps-alert-success {
            background: #ecfdf5;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .phishing-show .ps-alert-warning {
            background: #fffbeb;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .phishing-show .ps-hero {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 24px;
            padding: 28px;
            margin-bottom: 24px;
            border: 1px solid var(--ps-line);
            border-radius: 20px;
            background: linear-gradient(135deg, #ffffff 0%, #eff6ff 100%);
            box-shadow: 0 8px 24px rgba(15, 23, 42, .04);
        }

        .phishing-show .ps-eyebrow {
            margin: 0 0 8px;
            color: var(--ps-blue);
            text-transform: uppercase;
            letter-spacing: .09em;
            font-size: 11px;
            font-weight: 800;
        }

        .phishing-show .ps-title {
            margin: 0 0 10px;
            color: var(--ps-ink);
            font-size: clamp(25px, 3vw, 34px);
            line-height: 1.2;
            font-weight: 800;
            overflow-wrap: anywhere;
        }

        .phishing-show .ps-description {
            max-width: 720px;
            margin: 0 0 16px;
            color: var(--ps-muted);
            line-height: 1.7;
            white-space: pre-line;
        }

        .phishing-show .ps-status {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 6px 11px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
            text-transform: capitalize;
        }

        .phishing-show .ps-status::before {
            content: "";
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: currentColor;
        }

        .phishing-show .ps-status-draft {
            color: #475569;
            background: #e2e8f0;
        }

        .phishing-show .ps-status-active,
        .phishing-show .ps-status-launched {
            color: #166534;
            background: #dcfce7;
        }

        .phishing-show .ps-status-completed {
            color: #1d4ed8;
            background: #dbeafe;
        }

        .phishing-show .ps-status-paused {
            color: #92400e;
            background: #fef3c7;
        }

        .phishing-show .ps-launch {
            flex-shrink: 0;
            border: 0;
            border-radius: 11px;
            padding: 12px 18px;
            color: #fff;
            background: var(--ps-blue);
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 5px 12px rgba(37, 99, 235, .18);
            transition: background .2s ease, transform .2s ease;
        }

        .phishing-show .ps-launch:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
        }

        .phishing-show .ps-section-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin: 30px 0 15px;
        }

        .phishing-show .ps-section-heading h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 800;
        }

        .phishing-show .ps-section-heading p {
            margin: 4px 0 0;
            color: var(--ps-muted);
            font-size: 13px;
        }

        .phishing-show .ps-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 30px;
        }

        .phishing-show .ps-stat-card {
            position: relative;
            overflow: hidden;
            padding: 21px;
            border: 1px solid var(--ps-line);
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 5px 16px rgba(15, 23, 42, .035);
        }

        .phishing-show .ps-stat-card::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--ps-stat-color, #2563eb);
        }

        .phishing-show .ps-stat-label {
            display: block;
            margin-bottom: 12px;
            color: var(--ps-muted);
            font-size: 13px;
            font-weight: 600;
        }

        .phishing-show .ps-stat-value {
            display: block;
            color: var(--ps-ink);
            font-size: 32px;
            line-height: 1;
            font-weight: 800;
            font-variant-numeric: tabular-nums;
        }

        .phishing-show .ps-stat-note {
            display: block;
            margin-top: 10px;
            color: #94a3b8;
            font-size: 12px;
        }

        .phishing-show .ps-table-card {
            overflow: hidden;
            border: 1px solid var(--ps-line);
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 5px 16px rgba(15, 23, 42, .035);
        }

        .phishing-show .ps-table-top {
            padding: 18px 20px;
            border-bottom: 1px solid var(--ps-line);
        }

        .phishing-show .ps-table-top h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 800;
        }

        .phishing-show .ps-table-top p {
            margin: 5px 0 0;
            color: var(--ps-muted);
            font-size: 13px;
        }

        .phishing-show .ps-table-wrap {
            overflow-x: auto;
        }

        .phishing-show table {
            width: 100%;
            min-width: 760px;
            margin: 0;
            border-collapse: collapse;
        }

        .phishing-show thead th {
            padding: 14px 18px;
            background: #f8fafc;
            color: #64748b;
            text-align: left;
            text-transform: uppercase;
            letter-spacing: .045em;
            font-size: 11px;
            font-weight: 800;
            white-space: nowrap;
            border-bottom: 1px solid var(--ps-line);
        }

        .phishing-show tbody td {
            padding: 15px 18px;
            color: #475569;
            font-size: 13px;
            border-bottom: 1px solid #edf1f6;
            vertical-align: middle;
        }

        .phishing-show tbody tr:last-child td {
            border-bottom: 0;
        }

        .phishing-show tbody tr:hover {
            background: #f8fbff;
        }

        .phishing-show .ps-employee {
            color: var(--ps-ink);
            font-weight: 700;
        }

        .phishing-show .ps-record-status {
            display: inline-flex;
            padding: 5px 9px;
            border-radius: 999px;
            background: #f1f5f9;
            color: #475569;
            font-size: 11px;
            font-weight: 800;
            text-transform: capitalize;
            white-space: nowrap;
        }

        .phishing-show .ps-record-status.reported,
        .phishing-show .ps-record-status.completed {
            color: #166534;
            background: #dcfce7;
        }

        .phishing-show .ps-record-status.clicked {
            color: #9a3412;
            background: #ffedd5;
        }

        .phishing-show .ps-record-status.in_progress {
            color: #1d4ed8;
            background: #dbeafe;
        }

        .phishing-show .ps-empty {
            padding: 38px 20px;
            text-align: center;
        }

        .phishing-show .ps-empty-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            margin-bottom: 12px;
            border-radius: 14px;
            background: #eff6ff;
            color: var(--ps-blue);
            font-size: 22px;
        }

        .phishing-show .ps-empty h4 {
            margin: 0 0 7px;
            font-size: 15px;
            font-weight: 800;
        }

        .phishing-show .ps-empty p {
            margin: 0;
            color: var(--ps-muted);
            font-size: 13px;
        }

        .phishing-show .ps-reminder {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-top: 22px;
            padding: 16px 18px;
            border: 1px solid #bfdbfe;
            border-radius: 14px;
            background: #eff6ff;
            color: #1e40af;
        }

        .phishing-show .ps-reminder strong {
            display: block;
            margin-bottom: 3px;
            font-size: 13px;
        }

        .phishing-show .ps-reminder p {
            margin: 0;
            color: #1d4ed8;
            font-size: 12px;
            line-height: 1.6;
        }

        @media (max-width: 900px) {
            .phishing-show .ps-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .phishing-show .ps-hero {
                flex-direction: column;
            }

            .phishing-show .ps-launch {
                width: 100%;
            }
        }

        @media (max-width: 520px) {
            .phishing-show {
                padding-top: 18px;
            }

            .phishing-show .ps-container {
                width: calc(100% - 22px);
            }

            .phishing-show .ps-hero {
                padding: 20px;
                border-radius: 15px;
            }

            .phishing-show .ps-stats {
                gap: 10px;
            }

            .phishing-show .ps-stat-card {
                padding: 16px 14px;
            }

            .phishing-show .ps-stat-value {
                font-size: 27px;
            }

            .phishing-show .ps-stat-label {
                min-height: 32px;
            }

            .phishing-show .ps-section-heading h2 {
                font-size: 18px;
            }
        }
    </style>

    <div class="ps-container">
        <a href="{{ route('phishing.index') }}" class="ps-back">
            <span aria-hidden="true">&larr;</span>
            Back to campaigns
        </a>

        @if(session('success'))
            <div class="ps-alert ps-alert-success" role="status">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="ps-alert ps-alert-warning" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <section class="ps-hero">
            <div>
                <p class="ps-eyebrow">Campaign overview</p>

                <h1 class="ps-title">{{ $campaign->name }}</h1>

                <p class="ps-description">
                    {{ $campaign->description ?: 'No description provided.' }}
                </p>

                @php
                    $campaignStatus = strtolower($campaign->status ?? 'draft');
                    $statusClass = in_array($campaignStatus, [
                        'draft', 'active', 'launched', 'completed', 'paused'
                    ], true) ? $campaignStatus : 'draft';
                @endphp

                <span class="ps-status ps-status-{{ $statusClass }}">
                    {{ str_replace('_', ' ', ucfirst($campaignStatus)) }}
                </span>
            </div>

            @if($campaign->status === 'draft')
                <form method="POST"
                      action="{{ route('phishing.launch', $campaign) }}"
                      onsubmit="return confirm('Launch this in-app simulation for its assigned employees?');">
                    @csrf
                    <button type="submit" class="ps-launch">
                        Launch Simulation &rarr;
                    </button>
                </form>
            @endif
        </section>

        <div class="ps-section-heading">
            <div>
                <h2>Campaign performance</h2>
                <p>Engagement and reporting activity for this campaign.</p>
            </div>
        </div>

        <section class="ps-stats" aria-label="Campaign statistics">
            <article class="ps-stat-card" style="--ps-stat-color:#2563eb">
                <span class="ps-stat-label">Employees assigned</span>
                <strong class="ps-stat-value">{{ $stats['assigned'] }}</strong>
                <span class="ps-stat-note">Total campaign assignments</span>
            </article>

            <article class="ps-stat-card" style="--ps-stat-color:#f97316">
                <span class="ps-stat-label">Clicked</span>
                <strong class="ps-stat-value">{{ $stats['clicked'] }}</strong>
                <span class="ps-stat-note">Recorded click activity</span>
            </article>

            <article class="ps-stat-card" style="--ps-stat-color:#8b5cf6">
                <span class="ps-stat-label">Landing page visited</span>
                <strong class="ps-stat-value">{{ $stats['visited'] }}</strong>
                <span class="ps-stat-note">Simulation page visits</span>
            </article>

            <article class="ps-stat-card" style="--ps-stat-color:#16a34a">
                <span class="ps-stat-label">Reported</span>
                <strong class="ps-stat-value">{{ $stats['reported'] }}</strong>
                <span class="ps-stat-note">Messages reported by employees</span>
            </article>
        </section>

        <div class="ps-section-heading">
            <div>
                <h2>Assigned employees</h2>
                <p>Review individual participation and recorded activity.</p>
            </div>
        </div>

        <section class="ps-table-card">
            <div class="ps-table-top">
                <h3>Employee activity</h3>
                <p>
                    {{ $campaign->recipients->count() }}
                    {{ \Illuminate\Support\Str::plural('assignment', $campaign->recipients->count()) }}
                </p>
            </div>

            <div class="ps-table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Status</th>
                            <th>Clicked</th>
                            <th>Visited</th>
                            <th>Reported</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($campaign->recipients as $recipient)
                            <tr>
                                <td>
                                    <span class="ps-employee">
                                        {{ $recipient->user?->name ?? 'Unavailable user' }}
                                    </span>
                                </td>

                                <td>
                                    @php
                                        $recipientStatus = strtolower($recipient->status ?? 'pending');
                                        $recipientStatusClass = in_array($recipientStatus, [
                                            'pending', 'clicked', 'reported', 'completed', 'in_progress'
                                        ], true) ? $recipientStatus : 'pending';
                                    @endphp

                                    <span class="ps-record-status {{ $recipientStatusClass }}">
                                        {{ str_replace('_', ' ', ucfirst($recipientStatus)) }}
                                    </span>
                                </td>

                                <td>
                                    {{ $recipient->clicked_at?->format('d M Y, H:i') ?? '—' }}
                                </td>

                                <td>
                                    {{ $recipient->landing_page_visited_at?->format('d M Y, H:i') ?? '—' }}
                                </td>

                                <td>
                                    {{ $recipient->reported_at?->format('d M Y, H:i') ?? '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="ps-empty">
                                        <div class="ps-empty-icon" aria-hidden="true">
                                            &#128203;
                                        </div>
                                        <h4>No employees assigned yet</h4>
                                        <p>
                                            This campaign has no employee assignments.
                                            Assign employees before launching the simulation.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <aside class="ps-reminder">
            <span aria-hidden="true">&#9432;</span>
            <div>
                <strong>Responsible simulation practice</strong>
                <p>
                    Use this report to identify learning needs and improve security
                    awareness. Treat individual results as training information and
                    restrict access to authorised staff.
                </p>
            </div>
        </aside>
    </div>
</div>
@endsection
