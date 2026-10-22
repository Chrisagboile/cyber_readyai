
@extends('layouts.app')

@section('content')
<div class="phishing-employee">
    <div class="pe-container">

        {{-- Page heading --}}
        <div class="pe-header">
            <div class="pe-heading">
                <div class="pe-eyebrow">
                    <span class="pe-eyebrow-dot"></span>
                    CYBERREADYAI SECURITY AWARENESS
                </div>

                <h1>My Phishing Simulations</h1>

                <p>
                    Build your confidence in recognising suspicious messages,
                    identifying warning signs, and reporting potential threats safely.
                </p>
            </div>

            <div class="pe-header-icon" aria-hidden="true">
                <svg viewBox="0 0 64 64" fill="none">
                    <rect x="9" y="15" width="46" height="34" rx="7"
                          stroke="currentColor" stroke-width="2.5"/>
                    <path d="M12 20L32 35L52 20"
                          stroke="currentColor" stroke-width="2.5"
                          stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M42 43L47 48L57 37"
                          stroke="currentColor" stroke-width="3"
                          stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="pe-alert pe-alert-success" role="status">
                <span class="pe-alert-icon">✓</span>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="pe-alert pe-alert-error" role="alert">
                <span class="pe-alert-icon">!</span>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div class="pe-alert pe-alert-error" role="alert">
                <span class="pe-alert-icon">!</span>
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

        {{-- Summary panel --}}
        <div class="pe-summary">
            <div class="pe-summary-copy">
                <div class="pe-summary-label">YOUR LEARNING SPACE</div>
                <h2>Stay alert. Think before you click.</h2>
                <p>
                    Each simulation is an opportunity to practise the decisions
                    that help protect you and your organisation from phishing.
                </p>
            </div>

            <div class="pe-summary-stat">
                <span class="pe-stat-icon">
                    <svg viewBox="0 0 24 24" fill="none">
                        <rect x="4" y="4" width="16" height="16" rx="3"
                              stroke="currentColor" stroke-width="1.8"/>
                        <path d="M8 9H16M8 12H16M8 15H13"
                              stroke="currentColor" stroke-width="1.8"
                              stroke-linecap="round"/>
                    </svg>
                </span>
                <span class="pe-stat-number">{{ $recipients->total() }}</span>
                <span class="pe-stat-label">Assigned simulations</span>
            </div>
        </div>

        {{-- Simulations heading --}}
        <div class="pe-section-heading">
            <div>
                <h2>Your assigned exercises</h2>
                <p>Open a simulation to begin your awareness exercise.</p>
            </div>

            <span class="pe-count">
                {{ $recipients->count() }} on this page
            </span>
        </div>

        {{-- Simulation cards --}}
        <div class="pe-grid">
            @forelse($recipients as $recipient)
                @php
                    $status = strtolower($recipient->status ?? 'pending');

                    $statusClass = match($status) {
                        'reported' => 'reported',
                        'completed' => 'completed',
                        'clicked' => 'attention',
                        'in_progress' => 'progress',
                        default => 'pending',
                    };

                    $statusLabel = str_replace('_', ' ', $status);
                @endphp

                <article class="pe-card">
                    <div class="pe-card-top">
                        <div class="pe-card-icon">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M12 3L20 6V11C20 16 16.6 19.5 12 21C7.4 19.5 4 16 4 11V6L12 3Z"
                                      stroke="currentColor" stroke-width="1.7"
                                      stroke-linejoin="round"/>
                                <path d="M9 12L11 14L15.5 9.5"
                                      stroke="currentColor" stroke-width="1.7"
                                      stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>

                        <span class="pe-status pe-status-{{ $statusClass }}">
                            <span class="pe-status-dot"></span>
                            {{ ucfirst($statusLabel) }}
                        </span>
                    </div>

                    <div class="pe-card-content">
                        <div class="pe-card-kicker">SECURITY EXERCISE</div>

                        <h3>
                            {{ $recipient->campaign?->name ?? 'Security simulation' }}
                        </h3>

                        <p class="pe-description">
                            {{ $recipient->campaign?->description ?: 'An in-app cybersecurity awareness exercise designed to help you recognise and respond to suspicious messages.' }}
                        </p>
                    </div>

                    <div class="pe-card-footer">
                        <div class="pe-card-note">
                            <svg viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="9"
                                        stroke="currentColor" stroke-width="1.7"/>
                                <path d="M12 11V16M12 8H12.01"
                                      stroke="currentColor" stroke-width="1.8"
                                      stroke-linecap="round"/>
                            </svg>
                            <span>Awareness practice</span>
                        </div>

                        <a class="pe-button"
                           href="{{ route('phishing.open', $recipient) }}">
                            Open simulation
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M5 12H19M13 6L19 12L13 18"
                                      stroke="currentColor" stroke-width="1.8"
                                      stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </div>
                </article>
            @empty
                <div class="pe-empty">
                    <div class="pe-empty-icon">
                        <svg viewBox="0 0 64 64" fill="none">
                            <rect x="12" y="14" width="40" height="36" rx="6"
                                  stroke="currentColor" stroke-width="2"/>
                            <path d="M20 25L32 34L44 25"
                                  stroke="currentColor" stroke-width="2"
                                  stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M22 42H42"
                                  stroke="currentColor" stroke-width="2"
                                  stroke-linecap="round"/>
                        </svg>
                    </div>

                    <h3>No active simulations yet</h3>

                    <p>
                        You don't currently have any active phishing simulations
                        assigned to you. When an exercise becomes available,
                        it will appear here.
                    </p>

                    <span class="pe-empty-tip">
                        <span>TIP</span>
                        Stay cautious with unexpected links and requests for information.
                    </span>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($recipients->hasPages())
            <div class="pe-pagination">
                {{ $recipients->links() }}
            </div>
        @endif

        {{-- Safety reminder --}}
        <div class="pe-reminder">
            <div class="pe-reminder-icon">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M12 3L20 6V11C20 16 16.6 19.5 12 21C7.4 19.5 4 16 4 11V6L12 3Z"
                          stroke="currentColor" stroke-width="1.7"
                          stroke-linejoin="round"/>
                    <path d="M12 8V12M12 15H12.01"
                          stroke="currentColor" stroke-width="1.8"
                          stroke-linecap="round"/>
                </svg>
            </div>

            <div>
                <h3>Remember: pause before you click</h3>
                <p>
                    Check the sender, inspect unexpected requests, avoid entering
                    real passwords into simulation pages, and use the reporting
                    option when something looks suspicious.
                </p>
            </div>
        </div>

    </div>
</div>

<style>
    .phishing-employee {
        --pe-navy: #14243b;
        --pe-blue: #315be8;
        --pe-blue-dark: #2448c7;
        --pe-text: #243247;
        --pe-muted: #718096;
        --pe-border: #e4eaf2;
        --pe-surface: #ffffff;
        --pe-background: #f5f7fb;
        color: var(--pe-text);
        padding: 28px 0 42px;
        background: var(--pe-background);
        min-height: 70vh;
    }

    .phishing-employee,
    .phishing-employee * {
        box-sizing: border-box;
    }

    .phishing-employee .pe-container {
        width: min(1180px, calc(100% - 40px));
        margin: 0 auto;
    }

    .phishing-employee .pe-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 28px;
    }

    .phishing-employee .pe-eyebrow {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 12px;
        color: var(--pe-blue);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.25px;
    }

    .phishing-employee .pe-eyebrow-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--pe-blue);
    }

    .phishing-employee .pe-heading h1 {
        color: var(--pe-navy);
        font-size: clamp(27px, 3vw, 36px);
        font-weight: 800;
        letter-spacing: -1.1px;
        line-height: 1.2;
        margin: 0 0 10px;
    }

    .phishing-employee .pe-heading > p {
        color: var(--pe-muted);
        max-width: 680px;
        line-height: 1.7;
        margin: 0;
        font-size: 14px;
    }

    .phishing-employee .pe-header-icon {
        display: grid;
        place-items: center;
        width: 76px;
        height: 76px;
        flex: 0 0 76px;
        color: var(--pe-blue);
        background: #e9efff;
        border: 1px solid #dbe5ff;
        border-radius: 22px;
    }

    .phishing-employee .pe-header-icon svg {
        width: 43px;
        height: 43px;
    }

    .phishing-employee .pe-alert {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        border-radius: 12px;
        padding: 14px 16px;
        margin: 0 0 18px;
        font-size: 13px;
        line-height: 1.6;
    }

    .phishing-employee .pe-alert-success {
        color: #176b47;
        background: #eaf8f0;
        border: 1px solid #c8ead7;
    }

    .phishing-employee .pe-alert-error {
        color: #9b3030;
        background: #fff0f0;
        border: 1px solid #f2d0d0;
    }

    .phishing-employee .pe-alert-icon {
        display: grid;
        place-items: center;
        width: 23px;
        height: 23px;
        flex: 0 0 23px;
        border: 1px solid currentColor;
        border-radius: 50%;
        font-weight: 800;
    }

    .phishing-employee .pe-alert ul {
        margin: 6px 0 0;
        padding-left: 20px;
    }

    .phishing-employee .pe-summary {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        padding: 28px 30px;
        margin-bottom: 34px;
        color: #fff;
        background: linear-gradient(115deg, #172b49 0%, #233e6b 58%, #315be8 100%);
        border-radius: 19px;
        box-shadow: 0 12px 28px rgba(29, 53, 95, .12);
    }

    .phishing-employee .pe-summary-copy {
        max-width: 670px;
    }

    .phishing-employee .pe-summary-label {
        color: #c5d5ff;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1.3px;
        margin-bottom: 10px;
    }

    .phishing-employee .pe-summary-copy h2 {
        color: #fff;
        font-size: clamp(20px, 2.4vw, 27px);
        line-height: 1.3;
        letter-spacing: -.5px;
        font-weight: 750;
        margin: 0 0 9px;
    }

    .phishing-employee .pe-summary-copy p {
        color: #d5e0f4;
        font-size: 13px;
        line-height: 1.7;
        max-width: 590px;
        margin: 0;
    }

    .phishing-employee .pe-summary-stat {
        display: grid;
        justify-items: center;
        gap: 5px;
        min-width: 160px;
        padding: 15px 20px;
        border: 1px solid rgba(255,255,255,.19);
        background: rgba(255,255,255,.08);
        border-radius: 15px;
        text-align: center;
    }

    .phishing-employee .pe-stat-icon {
        display: grid;
        place-items: center;
        width: 32px;
        height: 32px;
        color: #dce6ff;
    }

    .phishing-employee .pe-stat-icon svg {
        width: 27px;
        height: 27px;
    }

    .phishing-employee .pe-stat-number {
        color: #fff;
        font-size: 28px;
        font-weight: 800;
        line-height: 1.15;
    }

    .phishing-employee .pe-stat-label {
        color: #d5e0f4;
        font-size: 11px;
    }

    .phishing-employee .pe-section-heading {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 17px;
    }

    .phishing-employee .pe-section-heading h2 {
        color: var(--pe-navy);
        font-size: 19px;
        font-weight: 800;
        margin: 0 0 5px;
        letter-spacing: -.3px;
    }

    .phishing-employee .pe-section-heading p {
        color: var(--pe-muted);
        font-size: 12px;
        line-height: 1.6;
        margin: 0;
    }

    .phishing-employee .pe-count {
        white-space: nowrap;
        color: #52627b;
        background: #e9eef7;
        padding: 7px 11px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
    }

    .phishing-employee .pe-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
    }

    .phishing-employee .pe-card {
        display: flex;
        flex-direction: column;
        min-width: 0;
        background: var(--pe-surface);
        border: 1px solid var(--pe-border);
        border-radius: 16px;
        padding: 21px;
        box-shadow: 0 3px 10px rgba(23, 42, 73, .025);
        transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
    }

    .phishing-employee .pe-card:hover {
        transform: translateY(-3px);
        border-color: #ccd8f7;
        box-shadow: 0 12px 25px rgba(23, 42, 73, .075);
    }

    .phishing-employee .pe-card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 20px;
    }

    .phishing-employee .pe-card-icon {
        display: grid;
        place-items: center;
        width: 45px;
        height: 45px;
        flex: 0 0 45px;
        border-radius: 13px;
        color: var(--pe-blue);
        background: #edf2ff;
    }

    .phishing-employee .pe-card-icon svg {
        width: 25px;
        height: 25px;
    }

    .phishing-employee .pe-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 9px;
        border-radius: 7px;
        font-size: 10px;
        font-weight: 750;
        text-transform: capitalize;
        white-space: nowrap;
    }

    .phishing-employee .pe-status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .phishing-employee .pe-status-pending {
        color: #96640a;
        background: #fff5dc;
    }

    .phishing-employee .pe-status-reported,
    .phishing-employee .pe-status-completed {
        color: #14754c;
        background: #e6f7ee;
    }

    .phishing-employee .pe-status-attention {
        color: #ad4b19;
        background: #fff0e6;
    }

    .phishing-employee .pe-status-progress {
        color: #315be8;
        background: #eaf0ff;
    }

    .phishing-employee .pe-card-content {
        flex: 1;
    }

    .phishing-employee .pe-card-kicker {
        color: #8190a7;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 1.15px;
        margin-bottom: 8px;
    }

    .phishing-employee .pe-card-content h3 {
        color: var(--pe-navy);
        font-size: 17px;
        font-weight: 800;
        line-height: 1.45;
        overflow-wrap: anywhere;
        margin: 0 0 9px;
    }

    .phishing-employee .pe-description {
        color: var(--pe-muted);
        font-size: 12px;
        line-height: 1.75;
        overflow-wrap: anywhere;
        margin: 0 0 23px;
    }

    .phishing-employee .pe-card-footer {
        display: flex;
        flex-direction: column;
        gap: 15px;
        border-top: 1px solid #edf0f5;
        padding-top: 16px;
    }

    .phishing-employee .pe-card-note {
        display: flex;
        align-items: center;
        gap: 7px;
        color: #78869a;
        font-size: 11px;
    }

    .phishing-employee .pe-card-note svg {
        width: 16px;
        height: 16px;
        flex: 0 0 16px;
    }

    .phishing-employee .pe-button {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        width: 100%;
        padding: 11px 14px;
        border: 1px solid var(--pe-blue);
        border-radius: 9px;
        background: var(--pe-blue);
        color: #fff !important;
        font-size: 12px;
        font-weight: 750;
        line-height: 1.4;
        text-align: center;
        text-decoration: none !important;
        transition: background .18s ease, border-color .18s ease;
    }

    .phishing-employee .pe-button:hover {
        color: #fff !important;
        background: var(--pe-blue-dark);
        border-color: var(--pe-blue-dark);
    }

    .phishing-employee .pe-button svg {
        width: 17px;
        height: 17px;
        flex: 0 0 17px;
    }

    .phishing-employee .pe-empty {
        grid-column: 1 / -1;
        text-align: center;
        padding: 48px 22px;
        border: 1px dashed #cfd8e6;
        border-radius: 17px;
        background: #fff;
    }

    .phishing-employee .pe-empty-icon {
        display: grid;
        place-items: center;
        width: 68px;
        height: 68px;
        margin: 0 auto 18px;
        border-radius: 20px;
        color: #7185b6;
        background: #edf2fc;
    }

    .phishing-employee .pe-empty-icon svg {
        width: 42px;
        height: 42px;
    }

    .phishing-employee .pe-empty h3 {
        color: var(--pe-navy);
        font-size: 19px;
        font-weight: 800;
        margin: 0 0 9px;
    }

    .phishing-employee .pe-empty > p {
        color: var(--pe-muted);
        max-width: 530px;
        margin: 0 auto;
        font-size: 12px;
        line-height: 1.8;
    }

    .phishing-employee .pe-empty-tip {
        display: inline-flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
        gap: 9px;
        margin-top: 22px;
        color: #52627b;
        font-size: 11px;
        line-height: 1.6;
    }

    .phishing-employee .pe-empty-tip > span {
        color: #315be8;
        background: #edf2ff;
        border-radius: 5px;
        padding: 4px 7px;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: .6px;
    }

    .phishing-employee .pe-pagination {
        display: flex;
        justify-content: center;
        margin-top: 25px;
    }

    .phishing-employee .pe-reminder {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        margin-top: 32px;
        padding: 20px 22px;
        background: #edf3ff;
        border: 1px solid #dce6ff;
        border-radius: 14px;
    }

    .phishing-employee .pe-reminder-icon {
        display: grid;
        place-items: center;
        width: 39px;
        height: 39px;
        flex: 0 0 39px;
        color: #315be8;
        background: #fff;
        border: 1px solid #dce6ff;
        border-radius: 11px;
    }

    .phishing-employee .pe-reminder-icon svg {
        width: 23px;
        height: 23px;
    }

    .phishing-employee .pe-reminder h3 {
        color: #233b68;
        font-size: 13px;
        font-weight: 800;
        margin: 1px 0 5px;
    }

    .phishing-employee .pe-reminder p {
        color: #596e91;
        font-size: 11px;
        line-height: 1.8;
        margin: 0;
    }

    @media (max-width: 1000px) {
        .phishing-employee .pe-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 650px) {
        .phishing-employee {
            padding: 20px 0 30px;
        }

        .phishing-employee .pe-container {
            width: calc(100% - 28px);
        }

        .phishing-employee .pe-header {
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 22px;
        }

        .phishing-employee .pe-header-icon {
            width: 48px;
            height: 48px;
            flex-basis: 48px;
            border-radius: 14px;
        }

        .phishing-employee .pe-header-icon svg {
            width: 29px;
            height: 29px;
        }

        .phishing-employee .pe-heading h1 {
            font-size: 26px;
        }

        .phishing-employee .pe-heading > p {
            font-size: 12px;
        }

        .phishing-employee .pe-eyebrow {
            font-size: 9px;
            letter-spacing: .7px;
        }

        .phishing-employee .pe-summary {
            flex-direction: column;
            align-items: stretch;
            gap: 20px;
            padding: 22px;
            margin-bottom: 27px;
            border-radius: 15px;
        }

        .phishing-employee .pe-summary-stat {
            grid-template-columns: auto auto 1fr;
            justify-items: start;
            align-items: center;
            gap: 10px;
            min-width: 0;
            padding: 12px;
            text-align: left;
        }

        .phishing-employee .pe-stat-number {
            font-size: 23px;
        }

        .phishing-employee .pe-section-heading {
            align-items: flex-start;
        }

        .phishing-employee .pe-section-heading h2 {
            font-size: 17px;
        }

        .phishing-employee .pe-count {
            font-size: 10px;
            padding: 6px 8px;
        }

        .phishing-employee .pe-grid {
            grid-template-columns: 1fr;
            gap: 13px;
        }

        .phishing-employee .pe-card {
            padding: 18px;
        }

        .phishing-employee .pe-reminder {
            padding: 16px;
            gap: 11px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .phishing-employee .pe-card,
        .phishing-employee .pe-button {
            transition: none;
        }
    }
</style>
@endsection
