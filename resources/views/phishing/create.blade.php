
@extends('layouts.app')

@section('content')
<style>
    .phishing-create {
        --pc-primary: #2563eb;
        --pc-primary-dark: #1d4ed8;
        --pc-ink: #172033;
        --pc-muted: #64748b;
        --pc-border: #e2e8f0;
        --pc-surface: #ffffff;
        --pc-background: #f5f7fb;
        color: var(--pc-ink);
        padding: 28px 0 48px;
    }

    .phishing-create * {
        box-sizing: border-box;
    }

    .phishing-create .pc-container {
        max-width: 1120px;
        margin: 0 auto;
        padding: 0 22px;
    }

    .phishing-create .pc-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 26px;
    }

    .phishing-create .pc-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--pc-primary);
        font-size: 12px;
        font-weight: 800;
        letter-spacing: .09em;
        text-transform: uppercase;
        margin-bottom: 10px;
    }

    .phishing-create .pc-title {
        font-size: clamp(26px, 3vw, 34px);
        font-weight: 750;
        letter-spacing: -.04em;
        line-height: 1.2;
        margin: 0 0 10px;
        color: var(--pc-ink);
    }

    .phishing-create .pc-subtitle {
        max-width: 650px;
        color: var(--pc-muted);
        font-size: 15px;
        line-height: 1.7;
        margin: 0;
    }

    .phishing-create .pc-back {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 15px;
        border: 1px solid var(--pc-border);
        border-radius: 10px;
        background: var(--pc-surface);
        color: #334155;
        text-decoration: none;
        font-size: 13px;
        font-weight: 650;
        white-space: nowrap;
        transition: background .2s, border-color .2s;
    }

    .phishing-create .pc-back:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        color: var(--pc-ink);
    }

    .phishing-create .pc-notice {
        display: flex;
        align-items: flex-start;
        gap: 13px;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 13px;
        padding: 15px 17px;
        margin-bottom: 23px;
    }

    .phishing-create .pc-notice-icon {
        flex: 0 0 34px;
        height: 34px;
        display: grid;
        place-items: center;
        border-radius: 10px;
        background: #dbeafe;
        color: #1d4ed8;
        font-size: 17px;
        font-weight: 800;
    }

    .phishing-create .pc-notice strong {
        display: block;
        color: #1e40af;
        font-size: 13px;
        margin-bottom: 4px;
    }

    .phishing-create .pc-notice p {
        color: #1e40af;
        font-size: 13px;
        line-height: 1.6;
        margin: 0;
    }

    .phishing-create .pc-steps {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 24px;
    }

    .phishing-create .pc-step {
        display: flex;
        align-items: center;
        gap: 11px;
        min-width: 0;
        padding: 14px;
        border: 1px solid var(--pc-border);
        border-radius: 12px;
        background: var(--pc-surface);
    }

    .phishing-create .pc-step-number {
        flex: 0 0 33px;
        height: 33px;
        display: grid;
        place-items: center;
        border-radius: 10px;
        background: #eff6ff;
        color: var(--pc-primary);
        font-weight: 800;
        font-size: 13px;
    }

    .phishing-create .pc-step.active {
        border-color: #93c5fd;
        background: #f8fbff;
    }

    .phishing-create .pc-step.active .pc-step-number {
        background: var(--pc-primary);
        color: #fff;
    }

    .phishing-create .pc-step-title {
        font-size: 13px;
        font-weight: 750;
        line-height: 1.4;
    }

    .phishing-create .pc-step-caption {
        color: var(--pc-muted);
        font-size: 11px;
        line-height: 1.5;
        margin-top: 3px;
    }

    .phishing-create .pc-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 310px;
        gap: 22px;
        align-items: start;
    }

    .phishing-create .pc-main,
    .phishing-create .pc-sidebar {
        display: grid;
        gap: 20px;
        min-width: 0;
    }

    .phishing-create .pc-card {
        border: 1px solid var(--pc-border);
        border-radius: 15px;
        background: var(--pc-surface);
        overflow: hidden;
        box-shadow: 0 3px 12px rgba(15, 23, 42, .025);
    }

    .phishing-create .pc-card-header {
        padding: 20px 22px 17px;
        border-bottom: 1px solid #edf0f5;
    }

    .phishing-create .pc-card-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 16px;
        font-weight: 750;
        margin: 0 0 6px;
        color: var(--pc-ink);
    }

    .phishing-create .pc-card-icon {
        width: 34px;
        height: 34px;
        display: grid;
        place-items: center;
        flex: 0 0 34px;
        background: #eff6ff;
        color: var(--pc-primary);
        border-radius: 10px;
        font-size: 15px;
        font-weight: 800;
    }

    .phishing-create .pc-card-description {
        color: var(--pc-muted);
        font-size: 12px;
        line-height: 1.6;
        margin: 0;
    }

    .phishing-create .pc-card-body {
        padding: 22px;
    }

    .phishing-create .pc-field {
        margin-bottom: 20px;
    }

    .phishing-create .pc-field:last-child {
        margin-bottom: 0;
    }

    .phishing-create .pc-label {
        display: block;
        color: #334155;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .phishing-create .pc-required {
        color: #dc2626;
    }

    .phishing-create .pc-control {
        width: 100%;
        display: block;
        border: 1px solid #cbd5e1;
        border-radius: 9px;
        padding: 11px 13px;
        color: #172033;
        background: #fff;
        font-family: inherit;
        font-size: 14px;
        line-height: 1.6;
        box-shadow: none;
        transition: border-color .2s, box-shadow .2s;
    }

    .phishing-create .pc-control:focus {
        outline: none;
        border-color: var(--pc-primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .12);
    }

    .phishing-create textarea.pc-control {
        resize: vertical;
        min-height: 100px;
    }

    .phishing-create .pc-message {
        min-height: 185px !important;
    }

    .phishing-create .pc-help {
        color: var(--pc-muted);
        font-size: 12px;
        line-height: 1.6;
        margin: 7px 0 0;
    }

    .phishing-create .pc-counter {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        color: #94a3b8;
        font-size: 11px;
        margin-top: 6px;
    }

    .phishing-create .pc-error {
        background: #fff1f2;
        border: 1px solid #fecdd3;
        color: #9f1239;
        border-radius: 11px;
        padding: 15px 18px;
        margin-bottom: 20px;
        font-size: 13px;
    }

    .phishing-create .pc-error ul {
        padding-left: 20px;
        margin: 8px 0 0;
    }

    .phishing-create .pc-error li + li {
        margin-top: 4px;
    }

    .phishing-create .pc-employee-toolbar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 14px;
    }

    .phishing-create .pc-employee-count {
        color: var(--pc-muted);
        font-size: 12px;
    }

    .phishing-create .pc-employee-actions {
        display: flex;
        gap: 12px;
    }

    .phishing-create .pc-text-action {
        border: 0;
        background: transparent;
        padding: 0;
        color: var(--pc-primary);
        font: inherit;
        font-size: 12px;
        font-weight: 750;
        cursor: pointer;
    }

    .phishing-create .pc-text-action:hover {
        text-decoration: underline;
    }

    .phishing-create .pc-search {
        position: relative;
        margin-bottom: 13px;
    }

    .phishing-create .pc-search .pc-control {
        padding-left: 38px;
    }

    .phishing-create .pc-search-icon {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 16px;
        pointer-events: none;
    }

    .phishing-create .pc-employee-list {
        border: 1px solid var(--pc-border);
        border-radius: 11px;
        max-height: 340px;
        overflow-y: auto;
        overscroll-behavior: contain;
    }

    .phishing-create .pc-employee {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 13px 14px;
        border-bottom: 1px solid #edf0f5;
        cursor: pointer;
        transition: background .15s;
    }

    .phishing-create .pc-employee:last-child {
        border-bottom: 0;
    }

    .phishing-create .pc-employee:hover {
        background: #f8fafc;
    }

    .phishing-create .pc-employee:has(input:checked) {
        background: #eff6ff;
    }

    .phishing-create .pc-checkbox {
        flex: 0 0 16px;
        width: 16px;
        height: 16px;
        accent-color: var(--pc-primary);
        cursor: pointer;
    }

    .phishing-create .pc-avatar {
        width: 37px;
        height: 37px;
        display: grid;
        place-items: center;
        flex: 0 0 37px;
        border-radius: 11px;
        background: #e0e7ff;
        color: #3730a3;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .phishing-create .pc-employee-info {
        min-width: 0;
        flex: 1;
    }

    .phishing-create .pc-employee-name {
        display: block;
        color: #1e293b;
        font-size: 13px;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .phishing-create .pc-employee-email {
        display: block;
        color: var(--pc-muted);
        font-size: 11px;
        margin-top: 3px;
        overflow-wrap: anywhere;
    }

    .phishing-create .pc-no-results {
        padding: 24px 16px;
        color: var(--pc-muted);
        font-size: 13px;
        text-align: center;
    }

    .phishing-create .pc-selected-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 12px 14px;
        margin-top: 12px;
        background: #f8fafc;
        border: 1px solid var(--pc-border);
        border-radius: 10px;
    }

    .phishing-create .pc-selected-label {
        color: var(--pc-muted);
        font-size: 12px;
    }

    .phishing-create .pc-selected-number {
        color: var(--pc-primary);
        font-size: 14px;
        font-weight: 800;
    }

    .phishing-create .pc-side-note {
        padding: 17px;
        background: #f8fafc;
        border: 1px solid var(--pc-border);
        border-radius: 12px;
    }

    .phishing-create .pc-side-note h3 {
        font-size: 13px;
        font-weight: 750;
        margin: 0 0 9px;
        color: var(--pc-ink);
    }

    .phishing-create .pc-side-note p {
        font-size: 12px;
        color: var(--pc-muted);
        line-height: 1.75;
        margin: 0;
    }

    .phishing-create .pc-check-list {
        display: grid;
        gap: 13px;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .phishing-create .pc-check-list li {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        color: #475569;
        font-size: 12px;
        line-height: 1.65;
    }

    .phishing-create .pc-check {
        display: grid;
        place-items: center;
        flex: 0 0 22px;
        width: 22px;
        height: 22px;
        border-radius: 7px;
        background: #dcfce7;
        color: #15803d;
        font-size: 12px;
        font-weight: 800;
    }

    .phishing-create .pc-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 18px 22px;
        border-top: 1px solid var(--pc-border);
        background: #fbfcfe;
    }

    .phishing-create .pc-footer-note {
        color: var(--pc-muted);
        font-size: 11px;
        line-height: 1.6;
    }

    .phishing-create .pc-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
    }

    .phishing-create .pc-btn {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        padding: 11px 16px;
        border: 1px solid transparent;
        border-radius: 9px;
        font-family: inherit;
        font-size: 13px;
        font-weight: 750;
        line-height: 1.4;
        text-decoration: none;
        cursor: pointer;
        transition: background .2s, border-color .2s, transform .2s;
    }

    .phishing-create .pc-btn:focus-visible,
    .phishing-create .pc-text-action:focus-visible {
        outline: 3px solid rgba(37, 99, 235, .35);
        outline-offset: 3px;
    }

    .phishing-create .pc-btn-primary {
        color: #fff;
        background: var(--pc-primary);
    }

    .phishing-create .pc-btn-primary:hover {
        background: var(--pc-primary-dark);
        color: #fff;
    }

    .phishing-create .pc-btn-secondary {
        background: #fff;
        color: #334155;
        border-color: #cbd5e1;
    }

    .phishing-create .pc-btn-secondary:hover {
        background: #f8fafc;
        color: var(--pc-ink);
    }

    .phishing-create .pc-empty {
        padding: 25px 18px;
        color: var(--pc-muted);
        font-size: 13px;
        line-height: 1.7;
        text-align: center;
    }

    .phishing-create .pc-empty strong {
        display: block;
        color: var(--pc-ink);
        margin-bottom: 5px;
    }

    @media (max-width: 900px) {
        .phishing-create .pc-grid {
            grid-template-columns: minmax(0, 1fr);
        }

        .phishing-create .pc-sidebar {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 600px) {
        .phishing-create {
            padding-top: 20px;
        }

        .phishing-create .pc-container {
            padding: 0 14px;
        }

        .phishing-create .pc-header {
            flex-direction: column;
            gap: 14px;
        }

        .phishing-create .pc-steps {
            grid-template-columns: minmax(0, 1fr);
            gap: 8px;
        }

        .phishing-create .pc-step {
            padding: 10px 12px;
        }

        .phishing-create .pc-card-header,
        .phishing-create .pc-card-body {
            padding: 17px;
        }

        .phishing-create .pc-sidebar {
            grid-template-columns: minmax(0, 1fr);
        }

        .phishing-create .pc-footer {
            align-items: stretch;
            flex-direction: column;
        }

        .phishing-create .pc-actions {
            display: grid;
            grid-template-columns: 1fr;
        }

        .phishing-create .pc-btn {
            width: 100%;
        }

        .phishing-create .pc-selected-bar {
            align-items: flex-start;
            flex-direction: column;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .phishing-create *,
        .phishing-create *::before,
        .phishing-create *::after {
            transition: none !important;
        }
    }
</style>

<div class="phishing-create">
    <div class="pc-container">

        {{-- Page heading --}}
        <header class="pc-header">
            <div>
                <div class="pc-eyebrow">
                    <span aria-hidden="true">&#9679;</span>
                    CyberReadyAI / Security Awareness
                </div>

                <h1 class="pc-title">Create phishing simulation</h1>

                <p class="pc-subtitle">
                    Build a controlled awareness exercise, select the employees who
                    should participate, and measure how they respond to simulated threats.
                </p>
            </div>

            <a href="{{ route('phishing.index') }}" class="pc-back">
                <span aria-hidden="true">&larr;</span>
                Back to campaigns
            </a>
        </header>

        {{-- Safe in-app simulation notice --}}
        <div class="pc-notice" role="note">
            <div class="pc-notice-icon" aria-hidden="true">&#9432;</div>
            <div>
                <strong>Controlled in-app simulation</strong>
                <p>
                    Creating this campaign does not send email. Use fictional scenarios
                    and never request real passwords, payment details or sensitive information.
                </p>
            </div>
        </div>

        {{-- Progress indicators --}}
        <div class="pc-steps" aria-label="Campaign creation steps">
            <div class="pc-step active">
                <div class="pc-step-number">1</div>
                <div>
                    <div class="pc-step-title">Campaign details</div>
                    <div class="pc-step-caption">Name and description</div>
                </div>
            </div>

            <div class="pc-step active">
                <div class="pc-step-number">2</div>
                <div>
                    <div class="pc-step-title">Message design</div>
                    <div class="pc-step-caption">Create the exercise</div>
                </div>
            </div>

            <div class="pc-step active">
                <div class="pc-step-number">3</div>
                <div>
                    <div class="pc-step-title">Employee assignment</div>
                    <div class="pc-step-caption">Choose participants</div>
                </div>
            </div>
        </div>

        {{-- Validation errors --}}
        @if($errors->any())
            <div class="pc-error" role="alert">
                <strong>Please correct the following errors:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('phishing.store') }}" id="phishingCampaignForm">
            @csrf

            <div class="pc-grid">

                {{-- Main form --}}
                <div class="pc-main">

                    {{-- Campaign details --}}
                    <section class="pc-card">
                        <div class="pc-card-header">
                            <h2 class="pc-card-title">
                                <span class="pc-card-icon" aria-hidden="true">01</span>
                                Campaign details
                            </h2>
                            <p class="pc-card-description">
                                Give the exercise a clear name so you can identify it in reports.
                            </p>
                        </div>

                        <div class="pc-card-body">
                            <div class="pc-field">
                                <label class="pc-label" for="name">
                                    Campaign name <span class="pc-required">*</span>
                                </label>

                                <input
                                    class="pc-control"
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="{{ old('name') }}"
                                    placeholder="e.g. Invoice verification awareness exercise"
                                    maxlength="150"
                                    autocomplete="off"
                                    required
                                >

                                <p class="pc-help">
                                    Use a short, recognisable name for dashboards and reporting.
                                </p>
                            </div>

                            <div class="pc-field">
                                <label class="pc-label" for="description">
                                    Description
                                </label>

                                <textarea
                                    class="pc-control"
                                    id="description"
                                    name="description"
                                    rows="3"
                                    maxlength="2000"
                                    placeholder="Describe the awareness objective and what employees should learn."
                                >{{ old('description') }}</textarea>

                                <p class="pc-help">
                                    Explain the learning objective. This field is optional.
                                </p>
                            </div>
                        </div>
                    </section>

                    {{-- Simulated message --}}
                    <section class="pc-card">
                        <div class="pc-card-header">
                            <h2 class="pc-card-title">
                                <span class="pc-card-icon" aria-hidden="true">02</span>
                                Simulated message
                            </h2>
                            <p class="pc-card-description">
                                Write the fictional message participants will see inside CyberReadyAI.
                            </p>
                        </div>

                        <div class="pc-card-body">
                            <div class="pc-field">
                                <label class="pc-label" for="subject">
                                    Message subject <span class="pc-required">*</span>
                                </label>

                                <input
                                    class="pc-control"
                                    type="text"
                                    id="subject"
                                    name="subject"
                                    value="{{ old('subject') }}"
                                    placeholder="e.g. Action required: review a sample invoice"
                                    maxlength="200"
                                    required
                                >
                            </div>

                            <div class="pc-field">
                                <label class="pc-label" for="body">
                                    Message content <span class="pc-required">*</span>
                                </label>

                                <textarea
                                    class="pc-control pc-message"
                                    id="body"
                                    name="body"
                                    rows="8"
                                    maxlength="10000"
                                    placeholder="Write the fictional scenario that participants will review..."
                                    required
                                >{{ old('body') }}</textarea>

                                <div class="pc-counter">
                                    <span>Keep the scenario realistic but controlled.</span>
                                    <span id="messageCharacterCount" aria-live="polite">0 / 10000</span>
                                </div>

                                <p class="pc-help">
                                    The message is stored as campaign content. Do not include actual
                                    credentials, confidential customer data or real payment requests.
                                </p>
                            </div>
                        </div>
                    </section>

                    {{-- Employee selection --}}
                    <section class="pc-card">
                        <div class="pc-card-header">
                            <h2 class="pc-card-title">
                                <span class="pc-card-icon" aria-hidden="true">03</span>
                                Assign employees
                            </h2>
                            <p class="pc-card-description">
                                Select the eligible employees who should participate in this exercise.
                            </p>
                        </div>

                        <div class="pc-card-body">
                            @if($employees->isNotEmpty())

                                <div class="pc-employee-toolbar">
                                    <div class="pc-employee-count">
                                        <strong>{{ $employees->count() }}</strong>
                                        eligible employee(s)
                                    </div>

                                    <div class="pc-employee-actions">
                                        <button type="button" class="pc-text-action" id="selectAllEmployees">
                                            Select all
                                        </button>
                                        <button type="button" class="pc-text-action" id="clearEmployees">
                                            Clear selection
                                        </button>
                                    </div>
                                </div>

                                <div class="pc-search">
                                    <span class="pc-search-icon" aria-hidden="true">&#9906;</span>
                                    <label for="employeeSearch" class="visually-hidden">
                                        Search employees
                                    </label>
                                    <input
                                        type="search"
                                        id="employeeSearch"
                                        class="pc-control"
                                        placeholder="Search by employee name or email..."
                                        autocomplete="off"
                                    >
                                </div>

                                <div class="pc-employee-list" id="employeeList">
                                    @foreach($employees as $employee)
                                        @php
                                            $initials = collect(
                                                preg_split('/\s+/', trim($employee->name))
                                            )->filter()->take(2)->map(
                                                fn ($part) => mb_substr($part, 0, 1)
                                            )->implode('');
                                        @endphp

                                        <label
                                            class="pc-employee"
                                            data-search="{{ mb_strtolower($employee->name . ' ' . $employee->email) }}"
                                            for="employee-{{ $employee->id }}"
                                        >
                                            <input
                                                class="pc-checkbox"
                                                type="checkbox"
                                                name="user_ids[]"
                                                value="{{ $employee->id }}"
                                                id="employee-{{ $employee->id }}"
                                                @checked(in_array(
                                                    (string) $employee->id,
                                                    array_map('strval', old('user_ids', [])),
                                                    true
                                                ))
                                            >

                                            <span class="pc-avatar" aria-hidden="true">
                                                {{ $initials ?: 'U' }}
                                            </span>

                                            <span class="pc-employee-info">
                                                <span class="pc-employee-name">
                                                    {{ $employee->name }}
                                                </span>
                                                <span class="pc-employee-email">
                                                    {{ $employee->email }}
                                                </span>
                                            </span>
                                        </label>
                                    @endforeach
                                </div>

                                <div class="pc-no-results" id="noEmployeesFound" hidden>
                                    No employees match your search.
                                </div>

                                <div class="pc-selected-bar">
                                    <span class="pc-selected-label">
                                        Employees selected for this campaign
                                    </span>
                                    <span class="pc-selected-number" id="selectedEmployeeCount" aria-live="polite">
                                        0 selected
                                    </span>
                                </div>

                                @error('user_ids')
                                    <p class="pc-help" style="color:#b91c1c;" role="alert">
                                        {{ $message }}
                                    </p>
                                @enderror

                            @else
                                <div class="pc-empty">
                                    <strong>No eligible employees found</strong>
                                    There are currently no eligible active employee accounts in your
                                    permitted scope. Add or activate an eligible employee account
                                    before creating this campaign.
                                </div>
                            @endif
                        </div>
                    </section>

                    {{-- Form actions --}}
                    <section class="pc-card">
                        <div class="pc-footer">
                            <div class="pc-footer-note">
                                Your campaign will be saved as a draft.<br>
                                You can review it before launching.
                            </div>

                            <div class="pc-actions">
                                <a href="{{ route('phishing.index') }}" class="pc-btn pc-btn-secondary">
                                    Cancel
                                </a>

                                <button
                                    type="submit"
                                    class="pc-btn pc-btn-primary"
                                    @disabled($employees->isEmpty())
                                >
                                    <span aria-hidden="true">&#10003;</span>
                                    Create draft campaign
                                </button>
                            </div>
                        </div>
                    </section>

                </div>

                {{-- Guidance sidebar --}}
                <aside class="pc-sidebar">

                    <section class="pc-card">
                        <div class="pc-card-header">
                            <h2 class="pc-card-title">
                                <span class="pc-card-icon" aria-hidden="true">&#10003;</span>
                                Before you begin
                            </h2>
                            <p class="pc-card-description">
                                Keep your awareness exercise useful and safe.
                            </p>
                        </div>

                        <div class="pc-card-body">
                            <ul class="pc-check-list">
                                <li>
                                    <span class="pc-check" aria-hidden="true">&#10003;</span>
                                    <span>Define a clear security-awareness objective.</span>
                                </li>
                                <li>
                                    <span class="pc-check" aria-hidden="true">&#10003;</span>
                                    <span>Use a fictional message and controlled scenario.</span>
                                </li>
                                <li>
                                    <span class="pc-check" aria-hidden="true">&#10003;</span>
                                    <span>Assign only employees within your authorised scope.</span>
                                </li>
                                <li>
                                    <span class="pc-check" aria-hidden="true">&#10003;</span>
                                    <span>Provide constructive feedback after the exercise.</span>
                                </li>
                            </ul>
                        </div>
                    </section>

                    <div class="pc-side-note">
                        <h3>How the simulation works</h3>
                        <p>
                            <strong>1. Create:</strong> configure the campaign and message.
                            <br><br>
                            <strong>2. Assign:</strong> select eligible employee accounts.
                            <br><br>
                            <strong>3. Launch:</strong> make the exercise available in the employee portal.
                            <br><br>
                            <strong>4. Review:</strong> inspect recorded interactions and reports.
                        </p>
                    </div>

                    <div class="pc-side-note">
                        <h3>Privacy and safety</h3>
                        <p>
                            This initial version is an in-app exercise, not an email delivery system.
                            Never ask employees to submit real passwords, financial information or
                            confidential business data.
                        </p>
                    </div>

                </aside>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    'use strict';

    const form = document.getElementById('phishingCampaignForm');

    if (!form) {
        return;
    }

    const checkboxes = Array.from(
        form.querySelectorAll('input[name="user_ids[]"]')
    );

    const selectedCount = document.getElementById('selectedEmployeeCount');
    const selectAllButton = document.getElementById('selectAllEmployees');
    const clearButton = document.getElementById('clearEmployees');
    const searchInput = document.getElementById('employeeSearch');
    const noResults = document.getElementById('noEmployeesFound');
    const employeeRows = Array.from(
        document.querySelectorAll('#employeeList .pc-employee')
    );
    const message = document.getElementById('body');
    const characterCount = document.getElementById('messageCharacterCount');

    function updateSelectedCount() {
        const count = checkboxes.filter(function (checkbox) {
            return checkbox.checked;
        }).length;

        if (selectedCount) {
            selectedCount.textContent = count + (count === 1 ? ' selected' : ' selected');
        }
    }

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    if (selectAllButton) {
        selectAllButton.addEventListener('click', function () {
            checkboxes.forEach(function (checkbox) {
                const row = checkbox.closest('.pc-employee');

                if (row && !row.hidden) {
                    checkbox.checked = true;
                }
            });

            updateSelectedCount();
        });
    }

    if (clearButton) {
        clearButton.addEventListener('click', function () {
            checkboxes.forEach(function (checkbox) {
                checkbox.checked = false;
            });

            updateSelectedCount();
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const query = searchInput.value.trim().toLowerCase();
            let visibleCount = 0;

            employeeRows.forEach(function (row) {
                const searchText = row.getAttribute('data-search') || '';
                const matches = searchText.includes(query);

                row.hidden = !matches;

                if (matches) {
                    visibleCount++;
                }
            });

            if (noResults) {
                noResults.hidden = visibleCount !== 0;
            }
        });
    }

    function updateCharacterCount() {
        if (message && characterCount) {
            characterCount.textContent =
                message.value.length.toLocaleString() + ' / 10000';
        }
    }

    if (message) {
        message.addEventListener('input', updateCharacterCount);
        updateCharacterCount();
    }

    form.addEventListener('submit', function (event) {
        const selected = checkboxes.filter(function (checkbox) {
            return checkbox.checked;
        });

        if (selected.length === 0) {
            event.preventDefault();

            const employeeSection = document.getElementById('employeeList');

            if (employeeSection) {
                employeeSection.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }

            if (selectedCount) {
                selectedCount.style.color = '#dc2626';
                selectedCount.textContent = 'Select at least one employee';
            }
        }
    });

    updateSelectedCount();
})();
</script>
@endsection
