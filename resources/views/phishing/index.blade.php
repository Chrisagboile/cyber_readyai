
@extends('layouts.app')

@section('content')
<div class="phishing-index">
    <div class="pi-container">

        {{-- Page heading --}}
        <header class="pi-header">
            <div class="pi-heading">
                <div class="pi-eyebrow">
                    <span class="pi-eyebrow-dot"></span>
                    CYBERREADYAI / SECURITY OPERATIONS
                </div>

                <h1>Phishing Simulations</h1>

                <p>
                    Create controlled security-awareness exercises, monitor
                    employee responses, and review campaign outcomes.
                </p>
            </div>

            <a href="{{ route('phishing.create') }}" class="pi-primary-button">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M12 5V19M5 12H19"
                          stroke="currentColor" stroke-width="2"
                          stroke-linecap="round"/>
                </svg>
                Create campaign
            </a>
        </header>

        {{-- Feedback messages --}}
        @if(session('success'))
            <div class="pi-alert pi-alert-success" role="status">
                <span class="pi-alert-icon">✓</span>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="pi-alert pi-alert-warning" role="alert">
                <span class="pi-alert-icon">!</span>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div class="pi-alert pi-alert-warning" role="alert">
                <span class="pi-alert-icon">!</span>
                <div>
                    <strong>Please check the following:</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- Summary cards --}}
        <section class="pi-stats" aria-label="Campaign overview">
            <article class="pi-stat-card">
                <div class="pi-stat-top">
                    <span class="pi-stat-label">Total campaigns</span>
                    <span class="pi-stat-icon pi-icon-blue">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <rect x="4" y="3" width="16" height="18" rx="3"
                                  stroke="currentColor" stroke-width="1.7"/>
                            <path d="M8 8H16M8 12H16M8 16H13"
                                  stroke="currentColor" stroke-width="1.7"
                                  stroke-linecap="round"/>
                        </svg>
                    </span>
                </div>
                <div class="pi-stat-value">{{ $campaigns->total() }}</div>
                <p class="pi-stat-note">Campaigns in this workspace</p>
            </article>

            <article class="pi-stat-card">
                <div class="pi-stat-top">
                    <span class="pi-stat-label">Active campaigns</span>
                    <span class="pi-stat-icon pi-icon-green">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle cx="12" cy="12" r="9"
                                    stroke="currentColor" stroke-width="1.7"/>
                            <path d="M8 12L10.5 14.5L16 9"
                                  stroke="currentColor" stroke-width="1.7"
                                  stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>
                <div class="pi-stat-value">
                    {{ $campaigns->getCollection()->where('status', 'active')->count() }}
                </div>
                <p class="pi-stat-note">Active on this page</p>
            </article>

            <article class="pi-stat-card">
                <div class="pi-stat-top">
                    <span class="pi-stat-label">Employees assigned</span>
                    <span class="pi-stat-icon pi-icon-purple">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle cx="9" cy="8" r="3"
                                    stroke="currentColor" stroke-width="1.7"/>
                            <path d="M3.5 19V17.5C3.5 15.6 5.1 14 7 14H11C12.9 14 14.5 15.6 14.5 17.5V19"
                                  stroke="currentColor" stroke-width="1.7"
                                  stroke-linecap="round"/>
                            <path d="M16 5.5C17.7 5.8 19 7.2 19 9C19 10.8 17.7 12.2 16 12.5M17 14.5C19.1 14.8 20.5 16 20.5 18V19"
                                  stroke="currentColor" stroke-width="1.7"
                                  stroke-linecap="round"/>
                        </svg>
                    </span>
                </div>
                <div class="pi-stat-value">
                    {{ $campaigns->getCollection()->sum(fn ($campaign) => $campaign->recipients->count()) }}
                </div>
                <p class="pi-stat-note">Assignments on this page</p>
            </article>

            <article class="pi-stat-card">
                <div class="pi-stat-top">
                    <span class="pi-stat-label">Reports received</span>
                    <span class="pi-stat-icon pi-icon-orange">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M5 4H19V20L12 16L5 20V4Z"
                                  stroke="currentColor" stroke-width="1.7"
                                  stroke-linejoin="round"/>
                            <path d="M9 9H15M9 12H13"
                                  stroke="currentColor" stroke-width="1.7"
                                  stroke-linecap="round"/>
                        </svg>
                    </span>
                </div>
                <div class="pi-stat-value">
                    {{ $campaigns->getCollection()->sum(fn ($campaign) => $campaign->recipients->whereNotNull('reported_at')->count()) }}
                </div>
                <p class="pi-stat-note">Reports on this page</p>
            </article>
        </section>

        {{-- Campaign table --}}
        <section class="pi-panel">
            <div class="pi-panel-header">
                <div>
                    <h2>Campaign register</h2>
                    <p>Review campaign status, participation, and reporting activity.</p>
                </div>

                <span class="pi-total-badge">
                    {{ $campaigns->total() }} total
                </span>
            </div>

            <div class="pi-table-wrap">
                <table class="pi-table">
                    <thead>
                        <tr>
                            <th scope="col">Campaign</th>
                            <th scope="col">Status</th>
                            <th scope="col">Assigned</th>
                            <th scope="col">Clicked</th>
                            <th scope="col">Reported</th>
                            <th scope="col">Created</th>
                            <th scope="col" class="pi-action-heading">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse($campaigns as $campaign)
                        @php
                            $campaignStatus = strtolower($campaign->status ?? 'draft');

                            $campaignStatusClass = match($campaignStatus) {
                                'active' => 'active',
                                'completed' => 'completed',
                                'scheduled' => 'scheduled',
                                'draft' => 'draft',
                                'paused' => 'paused',
                                default => 'other',
                            };

                            $assignedCount = $campaign->recipients->count();
                            $clickedCount = $campaign->recipients->whereNotNull('clicked_at')->count();
                            $reportedCount = $campaign->recipients->whereNotNull('reported_at')->count();
                        @endphp

                        <tr>
                            <td>
                                <div class="pi-campaign-cell">
                                    <span class="pi-campaign-icon">
                                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                            <path d="M12 3L20 6V11C20 16 16.6 19.5 12 21C7.4 19.5 4 16 4 11V6L12 3Z"
                                                  stroke="currentColor" stroke-width="1.7"
                                                  stroke-linejoin="round"/>
                                            <path d="M9 12L11 14L15.5 9.5"
                                                  stroke="currentColor" stroke-width="1.7"
                                                  stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </span>

                                    <div class="pi-campaign-info">
                                        <span class="pi-campaign-name">
                                            {{ $campaign->name }}
                                        </span>
                                        <span class="pi-campaign-subtitle">
                                            ID #{{ $campaign->id }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="pi-status pi-status-{{ $campaignStatusClass }}">
                                    <span class="pi-status-dot"></span>
                                    {{ ucfirst($campaignStatus) }}
                                </span>
                            </td>

                            <td>
                                <span class="pi-metric">{{ $assignedCount }}</span>
                            </td>

                            <td>
                                <span class="pi-metric">{{ $clickedCount }}</span>
                                @if($assignedCount > 0)
                                    <span class="pi-metric-sub">
                                        {{ round(($clickedCount / $assignedCount) * 100) }}%
                                    </span>
                                @endif
                            </td>

                            <td>
                                <span class="pi-metric">{{ $reportedCount }}</span>
                                @if($assignedCount > 0)
                                    <span class="pi-metric-sub">
                                        {{ round(($reportedCount / $assignedCount) * 100) }}%
                                    </span>
                                @endif
                            </td>

                            <td>
                                <span class="pi-date">
                                    {{ $campaign->created_at?->format('d M Y') ?? '—' }}
                                </span>
                            </td>

                            <td class="pi-action-cell">
                                <a class="pi-view-button"
                                   href="{{ route('phishing.show', $campaign) }}">
                                    View details
                                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M5 12H19M13 6L19 12L13 18"
                                              stroke="currentColor" stroke-width="1.8"
                                              stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="pi-empty">
                                    <div class="pi-empty-icon">
                                        <svg viewBox="0 0 64 64" fill="none" aria-hidden="true">
                                            <rect x="13" y="9" width="38" height="46" rx="6"
                                                  stroke="currentColor" stroke-width="2"/>
                                            <path d="M22 23H42M22 32H42M22 41H35"
                                                  stroke="currentColor" stroke-width="2"
                                                  stroke-linecap="round"/>
                                        </svg>
                                    </div>

                                    <h3>No campaigns yet</h3>

                                    <p>
                                        No phishing simulation campaigns have been created
                                        in this view. Create a controlled awareness exercise
                                        to begin tracking employee responses.
                                    </p>

                                    <a href="{{ route('phishing.create') }}"
                                       class="pi-primary-button pi-empty-button">
                                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                            <path d="M12 5V19M5 12H19"
                                                  stroke="currentColor" stroke-width="2"
                                                  stroke-linecap="round"/>
                                        </svg>
                                        Create your first campaign
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if($campaigns->hasPages())
                <div class="pi-pagination">
                    <div class="pi-pagination-summary">
                        Showing {{ $campaigns->firstItem() }}–{{ $campaigns->lastItem() }}
                        of {{ $campaigns->total() }} campaigns
                    </div>
                    <div>
                        {{ $campaigns->links() }}
                    </div>
                </div>
            @endif
        </section>

        {{-- Awareness reminder --}}
        <aside class="pi-reminder">
            <span class="pi-reminder-icon">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <circle cx="12" cy="12" r="9"
                            stroke="currentColor" stroke-width="1.7"/>
                    <path d="M12 11V16M12 8H12.01"
                          stroke="currentColor" stroke-width="1.8"
                          stroke-linecap="round"/>
                </svg>
            </span>
            <div>
                <h3>Responsible simulation practice</h3>
                <p>
                    Use controlled, in-app exercises to strengthen awareness.
                    Track clicks and reports as learning indicators, not as
                    standalone proof of an employee's overall security risk.
                </p>
            </div>
        </aside>

    </div>
</div>

<style>
    .phishing-index {
        --pi-navy: #14243b;
        --pi-blue: #315be8;
        --pi-blue-dark: #2448c7;
        --pi-text: #243247;
        --pi-muted: #718096;
        --pi-border: #e4eaf2;
        --pi-background: #f5f7fb;
        color: var(--pi-text);
        background: var(--pi-background);
        padding: 28px 0 42px;
        min-height: 70vh;
    }

    .phishing-index,
    .phishing-index * {
        box-sizing: border-box;
    }

    .phishing-index .pi-container {
        width: min(1380px, calc(100% - 40px));
        margin: 0 auto;
    }

    .phishing-index .pi-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 26px;
    }

    .phishing-index .pi-eyebrow {
        display: flex;
        align-items: center;
        gap: 9px;
        color: var(--pi-blue);
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1.2px;
        margin-bottom: 11px;
    }

    .phishing-index .pi-eyebrow-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--pi-blue);
    }

    .phishing-index .pi-heading h1 {
        color: var(--pi-navy);
        font-size: clamp(27px, 3vw, 35px);
        font-weight: 800;
        letter-spacing: -1px;
        line-height: 1.2;
        margin: 0 0 9px;
    }

    .phishing-index .pi-heading p {
        color: var(--pi-muted);
        font-size: 13px;
        line-height: 1.7;
        max-width: 700px;
        margin: 0;
    }

    .phishing-index .pi-primary-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        padding: 12px 17px;
        background: var(--pi-blue);
        color: #fff !important;
        border: 1px solid var(--pi-blue);
        border-radius: 10px;
        text-decoration: none !important;
        white-space: nowrap;
        font-size: 12px;
        font-weight: 750;
        line-height: 1.4;
        transition: background .18s ease, border-color .18s ease;
    }

    .phishing-index .pi-primary-button:hover {
        background: var(--pi-blue-dark);
        border-color: var(--pi-blue-dark);
        color: #fff !important;
    }

    .phishing-index .pi-primary-button svg {
        width: 18px;
        height: 18px;
        flex: 0 0 18px;
    }

    .phishing-index .pi-alert {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 13px 16px;
        margin-bottom: 17px;
        border-radius: 11px;
        font-size: 12px;
        line-height: 1.6;
    }

    .phishing-index .pi-alert-success {
        color: #176b47;
        background: #eaf8f0;
        border: 1px solid #c8ead7;
    }

    .phishing-index .pi-alert-warning {
        color: #8b5b12;
        background: #fff7e7;
        border: 1px solid #f1dfb8;
    }

    .phishing-index .pi-alert-icon {
        display: grid;
        place-items: center;
        width: 23px;
        height: 23px;
        flex: 0 0 23px;
        border: 1px solid currentColor;
        border-radius: 50%;
        font-weight: 800;
    }

    .phishing-index .pi-alert ul {
        padding-left: 19px;
        margin: 5px 0 0;
    }

    .phishing-index .pi-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 27px;
    }

    .phishing-index .pi-stat-card {
        min-width: 0;
        padding: 19px 20px;
        border: 1px solid var(--pi-border);
        border-radius: 15px;
        background: #fff;
        box-shadow: 0 3px 10px rgba(23, 42, 73, .025);
    }

    .phishing-index .pi-stat-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 14px;
    }

    .phishing-index .pi-stat-label {
        color: #6f7d92;
        font-size: 11px;
        font-weight: 650;
        line-height: 1.5;
    }

    .phishing-index .pi-stat-icon {
        display: grid;
        place-items: center;
        width: 38px;
        height: 38px;
        flex: 0 0 38px;
        border-radius: 11px;
    }

    .phishing-index .pi-stat-icon svg {
        width: 22px;
        height: 22px;
    }

    .phishing-index .pi-icon-blue {
        color: #315be8;
        background: #edf2ff;
    }

    .phishing-index .pi-icon-green {
        color: #13845a;
        background: #e6f7ee;
    }

    .phishing-index .pi-icon-purple {
        color: #7750c9;
        background: #f0eaff;
    }

    .phishing-index .pi-icon-orange {
        color: #c66b21;
        background: #fff0e4;
    }

    .phishing-index .pi-stat-value {
        color: var(--pi-navy);
        font-size: 30px;
        font-weight: 800;
        letter-spacing: -.7px;
        line-height: 1.1;
        overflow-wrap: anywhere;
    }

    .phishing-index .pi-stat-note {
        color: #8a96a8;
        font-size: 10px;
        margin: 8px 0 0;
        line-height: 1.5;
    }

    .phishing-index .pi-panel {
        background: #fff;
        border: 1px solid var(--pi-border);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 3px 10px rgba(23, 42, 73, .025);
    }

    .phishing-index .pi-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 21px 23px;
        border-bottom: 1px solid #edf0f5;
    }

    .phishing-index .pi-panel-header h2 {
        color: var(--pi-navy);
        font-size: 17px;
        font-weight: 800;
        letter-spacing: -.25px;
        margin: 0 0 5px;
    }

    .phishing-index .pi-panel-header p {
        color: var(--pi-muted);
        font-size: 11px;
        line-height: 1.6;
        margin: 0;
    }

    .phishing-index .pi-total-badge {
        color: #52627b;
        background: #edf1f7;
        padding: 7px 10px;
        border-radius: 8px;
        white-space: nowrap;
        font-size: 10px;
        font-weight: 750;
    }

    .phishing-index .pi-table-wrap {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .phishing-index .pi-table {
        width: 100%;
        min-width: 880px;
        border-collapse: separate;
        border-spacing: 0;
        margin: 0;
        font-size: 12px;
    }

    .phishing-index .pi-table thead th {
        padding: 13px 16px;
        background: #f8faff;
        color: #78869a;
        border-bottom: 1px solid var(--pi-border);
        text-align: left;
        white-space: nowrap;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: .65px;
        text-transform: uppercase;
    }

    .phishing-index .pi-table tbody td {
        padding: 16px;
        vertical-align: middle;
        color: var(--pi-text);
        border-bottom: 1px solid #edf0f5;
    }

    .phishing-index .pi-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .phishing-index .pi-table tbody tr:not(:has(.pi-empty)):hover td {
        background: #fbfcff;
    }

    .phishing-index .pi-campaign-cell {
        display: flex;
        align-items: center;
        gap: 11px;
        min-width: 185px;
    }

    .phishing-index .pi-campaign-icon {
        display: grid;
        place-items: center;
        width: 39px;
        height: 39px;
        flex: 0 0 39px;
        color: var(--pi-blue);
        background: #edf2ff;
        border-radius: 11px;
    }

    .phishing-index .pi-campaign-icon svg {
        width: 22px;
        height: 22px;
    }

    .phishing-index .pi-campaign-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
        min-width: 0;
    }

    .phishing-index .pi-campaign-name {
        color: var(--pi-navy);
        font-size: 12px;
        font-weight: 750;
        overflow-wrap: anywhere;
        line-height: 1.5;
    }

    .phishing-index .pi-campaign-subtitle {
        color: #96a0b0;
        font-size: 10px;
    }

    .phishing-index .pi-status {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 6px 9px;
        border-radius: 7px;
        font-size: 10px;
        font-weight: 750;
        white-space: nowrap;
    }

    .phishing-index .pi-status-dot {
        width: 6px;
        height: 6px;
        flex: 0 0 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .phishing-index .pi-status-active {
        color: #14754c;
        background: #e6f7ee;
    }

    .phishing-index .pi-status-completed {
        color: #315be8;
        background: #eaf0ff;
    }

    .phishing-index .pi-status-scheduled {
        color: #7750a8;
        background: #f3eaff;
    }

    .phishing-index .pi-status-draft {
        color: #6e7888;
        background: #edf0f4;
    }

    .phishing-index .pi-status-paused {
        color: #9a6211;
        background: #fff4dc;
    }

    .phishing-index .pi-status-other {
        color: #596779;
        background: #edf1f7;
    }

    .phishing-index .pi-metric {
        color: #34445b;
        font-size: 13px;
        font-weight: 750;
        font-variant-numeric: tabular-nums;
    }

    .phishing-index .pi-metric-sub {
        display: block;
        color: #98a2b1;
        font-size: 9px;
        margin-top: 4px;
    }

    .phishing-index .pi-date {
        color: #66758b;
        font-size: 11px;
        white-space: nowrap;
    }

    .phishing-index .pi-action-heading,
    .phishing-index .pi-action-cell {
        text-align: right !important;
    }

    .phishing-index .pi-view-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 8px 10px;
        border: 1px solid #dce5fb;
        border-radius: 8px;
        color: var(--pi-blue) !important;
        background: #f7f9ff;
        text-decoration: none !important;
        white-space: nowrap;
        font-size: 10px;
        font-weight: 750;
        transition: background .18s ease, border-color .18s ease;
    }

    .phishing-index .pi-view-button:hover {
        color: var(--pi-blue-dark) !important;
        background: #edf2ff;
        border-color: #c5d4fb;
    }

    .phishing-index .pi-view-button svg {
        width: 14px;
        height: 14px;
    }

    .phishing-index .pi-empty {
        padding: 42px 20px;
        text-align: center;
        white-space: normal;
    }

    .phishing-index .pi-empty-icon {
        display: grid;
        place-items: center;
        width: 63px;
        height: 63px;
        margin: 0 auto 16px;
        color: #7185b6;
        background: #edf2fc;
        border-radius: 18px;
    }

    .phishing-index .pi-empty-icon svg {
        width: 39px;
        height: 39px;
    }

    .phishing-index .pi-empty h3 {
        color: var(--pi-navy);
        font-size: 18px;
        font-weight: 800;
        margin: 0 0 8px;
    }

    .phishing-index .pi-empty p {
        color: var(--pi-muted);
        max-width: 510px;
        margin: 0 auto 20px;
        font-size: 11px;
        line-height: 1.8;
    }

    .phishing-index .pi-empty-button {
        font-size: 11px;
        padding: 10px 13px;
    }

    .phishing-index .pi-pagination {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding: 15px 22px;
        border-top: 1px solid #edf0f5;
    }

    .phishing-index .pi-pagination-summary {
        color: #7f8ba0;
        font-size: 10px;
    }

    .phishing-index .pi-reminder {
        display: flex;
        align-items: flex-start;
        gap: 13px;
        margin-top: 22px;
        padding: 17px 19px;
        border: 1px solid #dce6ff;
        border-radius: 13px;
        background: #edf3ff;
    }

    .phishing-index .pi-reminder-icon {
        display: grid;
        place-items: center;
        width: 35px;
        height: 35px;
        flex: 0 0 35px;
        color: var(--pi-blue);
        background: #fff;
        border: 1px solid #dce6ff;
        border-radius: 10px;
    }

    .phishing-index .pi-reminder-icon svg {
        width: 21px;
        height: 21px;
    }

    .phishing-index .pi-reminder h3 {
        color: #233b68;
        font-size: 12px;
        font-weight: 800;
        margin: 1px 0 5px;
    }

    .phishing-index .pi-reminder p {
        color: #596e91;
        font-size: 10px;
        line-height: 1.8;
        margin: 0;
    }

    @media (max-width: 1050px) {
        .phishing-index .pi-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 650px) {
        .phishing-index {
            padding: 20px 0 30px;
        }

        .phishing-index .pi-container {
            width: calc(100% - 28px);
        }

        .phishing-index .pi-header {
            align-items: flex-start;
            flex-direction: column;
            gap: 17px;
        }

        .phishing-index .pi-heading h1 {
            font-size: 27px;
        }

        .phishing-index .pi-heading p {
            font-size: 12px;
        }

        .phishing-index .pi-primary-button {
            width: 100%;
        }

        .phishing-index .pi-stats {
            gap: 10px;
        }

        .phishing-index .pi-stat-card {
            padding: 14px;
            border-radius: 12px;
        }

        .phishing-index .pi-stat-top {
            align-items: flex-start;
            gap: 7px;
        }

        .phishing-index .pi-stat-label {
            font-size: 10px;
        }

        .phishing-index .pi-stat-icon {
            width: 31px;
            height: 31px;
            flex-basis: 31px;
        }

        .phishing-index .pi-stat-icon svg {
            width: 19px;
            height: 19px;
        }

        .phishing-index .pi-stat-value {
            font-size: 25px;
        }

        .phishing-index .pi-stat-note {
            font-size: 9px;
        }

        .phishing-index .pi-panel-header {
            padding: 17px;
        }

        .phishing-index .pi-panel-header h2 {
            font-size: 15px;
        }

        .phishing-index .pi-panel-header p {
            font-size: 10px;
        }

        .phishing-index .pi-pagination {
            align-items: flex-start;
            flex-direction: column;
            padding: 15px;
        }

        .phishing-index .pi-reminder {
            padding: 15px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .phishing-index .pi-primary-button,
        .phishing-index .pi-view-button {
            transition: none;
        }
    }
</style>
@endsection
