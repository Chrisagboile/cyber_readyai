@extends('layouts.app')

@section('title', 'Manager Dashboard')
@section('page-title', 'Dashboard')

@section('content')

<style>
    .manager-dashboard {
        max-width: 1400px;
        margin: 0 auto;
        padding: 8px 0 40px;
    }

    .manager-hero {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 28px;
    }

    .manager-hero-content {
        max-width: 780px;
    }

    .manager-eyebrow {
        margin: 0 0 8px;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #2563eb;
    }

    .manager-title {
        margin: 0;
        font-size: 32px;
        line-height: 1.2;
        font-weight: 750;
        color: #0f172a;
    }

    .manager-subtitle {
        margin: 10px 0 0;
        font-size: 16px;
        line-height: 1.65;
        color: #64748b;
    }

    .manager-profile-card {
        min-width: 220px;
        padding: 16px 18px;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 8px 24px rgba(15, 23, 42, .05);
    }

    .manager-profile-top {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .manager-avatar {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 50%;
        background: #2563eb;
        color: #fff;
        font-size: 18px;
        font-weight: 700;
    }

    .manager-profile-name {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
    }

    .manager-profile-role {
        margin: 3px 0 0;
        font-size: 13px;
        color: #64748b;
    }

    .manager-section {
        margin-top: 30px;
    }

    .manager-section-header {
        margin-bottom: 14px;
    }

    .manager-section-title {
        margin: 0;
        font-size: 19px;
        font-weight: 750;
        color: #0f172a;
    }

    .manager-section-description {
        margin: 4px 0 0;
        font-size: 14px;
        line-height: 1.55;
        color: #64748b;
    }

    .manager-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
    }

    .manager-grid-2 {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .manager-card {
        position: relative;
        display: flex;
        flex-direction: column;
        min-height: 220px;
        padding: 24px;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 8px 24px rgba(15, 23, 42, .05);
        text-decoration: none;
        transition:
            transform .18s ease,
            box-shadow .18s ease,
            border-color .18s ease;
    }

    .manager-card:hover {
        transform: translateY(-2px);
        border-color: #bfdbfe;
        box-shadow: 0 14px 30px rgba(15, 23, 42, .08);
    }

    .manager-card-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 18px;
        border-radius: 14px;
        background: #eff6ff;
        font-size: 23px;
    }

    .manager-card-title {
        margin: 0;
        font-size: 18px;
        font-weight: 750;
        color: #0f172a;
    }

    .manager-card-description {
        margin: 8px 0 0;
        font-size: 14px;
        line-height: 1.65;
        color: #64748b;
    }

    .manager-card-link {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-top: auto;
        padding-top: 20px;
        font-size: 14px;
        font-weight: 700;
        color: #2563eb;
    }

    .manager-card-link .arrow {
        transition: transform .18s ease;
    }

    .manager-card:hover .manager-card-link .arrow {
        transform: translateX(3px);
    }

    .manager-card-primary {
        border-color: #dbeafe;
        background: linear-gradient(
            180deg,
            #fff 0%,
            #f8fbff 100%
        );
    }

    .manager-card-primary .manager-card-icon {
        background: #dbeafe;
    }

    .manager-quick-panel {
        padding: 24px;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 8px 24px rgba(15, 23, 42, .05);
    }

    .manager-quick-list {
        display: grid;
        gap: 12px;
        margin-top: 18px;
    }

    .manager-quick-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 15px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #f8fafc;
        text-decoration: none;
        transition:
            background .18s ease,
            border-color .18s ease;
    }

    .manager-quick-item:hover {
        background: #fff;
        border-color: #bfdbfe;
    }

    .manager-quick-item-content {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .manager-quick-icon {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        background: #fff;
        font-size: 17px;
        flex-shrink: 0;
    }

    .manager-quick-title {
        margin: 0;
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
    }

    .manager-quick-description {
        margin: 3px 0 0;
        font-size: 13px;
        color: #64748b;
    }

    .manager-quick-arrow {
        color: #2563eb;
        font-size: 18px;
        flex-shrink: 0;
    }

    @media (max-width: 1050px) {
        .manager-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .manager-grid-2 {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 760px) {
        .manager-hero {
            flex-direction: column;
        }

        .manager-profile-card {
            width: 100%;
            min-width: 0;
        }

        .manager-grid {
            grid-template-columns: 1fr;
        }

        .manager-title {
            font-size: 27px;
        }
    }
</style>

<div class="manager-dashboard">

    {{-- Dashboard Header --}}
    <div class="manager-hero">

        <div class="manager-hero-content">

            <p class="manager-eyebrow">
                Manager Workspace
            </p>

            <h1 class="manager-title">
                Manager Dashboard
            </h1>

            <p class="manager-subtitle">
                Welcome back, {{ auth()->user()->name }}.
                Monitor your team's cybersecurity readiness,
                assessment activity, risk exposure, and learning progress
                from one place.
            </p>

        </div>

        <div class="manager-profile-card">

            <div class="manager-profile-top">

                <div class="manager-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

                <div>

                    <p class="manager-profile-name">
                        {{ auth()->user()->name }}
                    </p>

                    <p class="manager-profile-role">
                        Manager
                    </p>

                </div>

            </div>

        </div>

    </div>


    {{-- Team Management --}}
    <div class="manager-section">

        <div class="manager-section-header">

            <h2 class="manager-section-title">
                Team Management
            </h2>

            <p class="manager-section-description">
                Access the core tools used to manage your department's
                cybersecurity readiness.
            </p>

        </div>


        <div class="manager-grid">

            {{-- Team Readiness --}}
            <a
                href="{{ route('manager.team-readiness') }}"
                class="manager-card"
            >

                <div class="manager-card-icon">
                    🛡️
                </div>

                <h3 class="manager-card-title">
                    Team Readiness
                </h3>

                <p class="manager-card-description">
                    Review your team's cybersecurity readiness,
                    assessment performance, and overall preparedness.
                </p>

                <span class="manager-card-link">
                    Open Team Readiness
                    <span class="arrow">→</span>
                </span>

            </a>


            {{-- Department Reports --}}
            <a
                href="{{ route('manager.department-reports') }}"
                class="manager-card"
            >

                <div class="manager-card-icon">
                    📊
                </div>

                <h3 class="manager-card-title">
                    Department Reports
                </h3>

                <p class="manager-card-description">
                    Review department assessment results,
                    performance trends, and workforce readiness.
                </p>

                <span class="manager-card-link">
                    View Reports
                    <span class="arrow">→</span>
                </span>

            </a>


            {{-- Risk Dashboard --}}
            <a
                href="{{ route('manager.risk-dashboard') }}"
                class="manager-card"
            >

                <div class="manager-card-icon">
                    ⚠️
                </div>

                <h3 class="manager-card-title">
                    Risk Dashboard
                </h3>

                <p class="manager-card-description">
                    Monitor employee risk levels and identify
                    areas requiring cybersecurity improvement.
                </p>

                <span class="manager-card-link">
                    Monitor Risk
                    <span class="arrow">→</span>
                </span>

            </a>

        </div>

    </div>


    {{-- Assessment Management --}}
    <div class="manager-section">

        <div class="manager-section-header">

            <h2 class="manager-section-title">
                Assessment Management
            </h2>

            <p class="manager-section-description">
                Create and manage cybersecurity assessments assigned
                to employees in your department.
            </p>

        </div>


        <div class="manager-grid-2">

            {{-- Create Assessment --}}
            <a
                href="{{ route('manager.assessments.create') }}"
                class="manager-card manager-card-primary"
            >

                <div class="manager-card-icon">
                    📝
                </div>

                <h3 class="manager-card-title">
                    Create Assessment
                </h3>

                <p class="manager-card-description">
                    Select cybersecurity categories and generate
                    a random assessment for an employee.
                </p>

                <span class="manager-card-link">
                    Create Assessment
                    <span class="arrow">→</span>
                </span>

            </a>


            {{-- Assessments --}}
            <a
                href="{{ route('manager.assessments.index') }}"
                class="manager-card"
            >

                <div class="manager-card-icon">
                    📋
                </div>

                <h3 class="manager-card-title">
                    Assessments
                </h3>

                <p class="manager-card-description">
                    Review existing employee assessments,
                    monitor their status, and access assessment results.
                </p>

                <span class="manager-card-link">
                    Manage Assessments
                    <span class="arrow">→</span>
                </span>

            </a>

        </div>

    </div>


    {{-- Quick Access --}}
    <div class="manager-section">

        <div class="manager-quick-panel">

            <h2 class="manager-section-title">
                Quick Access
            </h2>

            <p class="manager-section-description">
                Jump directly to the tools you use most often.
            </p>


            <div class="manager-quick-list">

                {{-- Team Readiness --}}
                <a
                    href="{{ route('manager.team-readiness') }}"
                    class="manager-quick-item"
                >

                    <div class="manager-quick-item-content">

                        <div class="manager-quick-icon">
                            🎓
                        </div>

                        <div>

                            <p class="manager-quick-title">
                                Team Readiness
                            </p>

                            <p class="manager-quick-description">
                                Review team readiness, assessment performance,
                                and employee progress.
                            </p>

                        </div>

                    </div>

                    <span class="manager-quick-arrow">
                        →
                    </span>

                </a>


                {{-- Risk Monitoring --}}
                <a
                    href="{{ route('manager.risk-dashboard') }}"
                    class="manager-quick-item"
                >

                    <div class="manager-quick-item-content">

                        <div class="manager-quick-icon">
                            🔎
                        </div>

                        <div>

                            <p class="manager-quick-title">
                                Risk Monitoring
                            </p>

                            <p class="manager-quick-description">
                                Identify employees and areas requiring attention.
                            </p>

                        </div>

                    </div>

                    <span class="manager-quick-arrow">
                        →
                    </span>

                </a>


                {{-- My Profile --}}
                <a
                    href="{{ route('profile.edit') }}"
                    class="manager-quick-item"
                >

                    <div class="manager-quick-item-content">

                        <div class="manager-quick-icon">
                            👤
                        </div>

                        <div>

                            <p class="manager-quick-title">
                                My Profile
                            </p>

                            <p class="manager-quick-description">
                                Update your account details and password.
                            </p>

                        </div>

                    </div>

                    <span class="manager-quick-arrow">
                        →
                    </span>

                </a>

            </div>

        </div>

    </div>

</div>

@endsection
