@extends('layouts.app')

@section('content')
<div class="organisation-assessments-page">

    <div class="dashboard-header">
        <div>
            <h1 class="dashboard-title">Organisation Assessments</h1>

            <p class="dashboard-description">
                Create, assign and monitor cybersecurity assessments
                across your organisation.
            </p>
        </div>

        <a
            href="{{ route('organisation.assessments.create') }}"
            class="assessment-primary-button"
        >
            + Create Assessment
        </a>
    </div>

    @if(session('success'))
        <div class="assessment-alert">
            {{ session('success') }}
        </div>
    @endif


    {{-- =========================================================
         SUMMARY CARDS
    ========================================================== --}}

    <div class="assessment-stat-grid">

        <div class="assessment-stat-card">
            <span>Total Assessments</span>
            <strong>{{ $totalAssessments }}</strong>
            <small>Created for your organisation</small>
        </div>

        <div class="assessment-stat-card">
            <span>Active Assessments</span>
            <strong>{{ $activeAssessments }}</strong>
            <small>Currently available to employees</small>
        </div>

        <div class="assessment-stat-card">
            <span>Completed Attempts</span>
            <strong>{{ $completedAttempts }}</strong>
            <small>Completed employee assessments</small>
        </div>

        <div class="assessment-stat-card">
            <span>Active Employees</span>
            <strong>{{ $employeeCount }}</strong>
            <small>Employees available for assessment</small>
        </div>

    </div>


    {{-- =========================================================
         ASSESSMENT LIST
    ========================================================== --}}

    <div class="assessment-card">

        <div class="assessment-card-header">
            <div>
                <h2>Assessments</h2>
                <p>Assessments created within your organisation.</p>
            </div>
        </div>

        <div class="assessment-table-wrapper">

            <table class="assessment-table">

                <thead>
                    <tr>
                        <th>Assessment</th>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Questions</th>
                        <th>Duration</th>
                        <th>Attempts</th>
                        <th>Status</th>
                        <th>Created</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($assessments as $assessment)

                        <tr>

                            <td>
                                <strong>
                                    {{ $assessment->name }}
                                </strong>

                                <small class="assessment-created-by">
                                    Created by
                                    {{ optional($assessment->creator)->name ?? 'Unknown' }}
                                </small>
                            </td>

                            <td>
                                {{ optional($assessment->employee)->name ?? '—' }}
                            </td>

                            <td>
                                {{ optional($assessment->department)->name ?? '—' }}
                            </td>

                            <td>
                                {{ $assessment->total_questions }}
                            </td>

                            <td>
                                {{ $assessment->duration_minutes }} min
                            </td>

                            <td>
                                {{ $assessment->attempts_count }}
                            </td>

                            <td>
                                @if($assessment->status === 'active')
                                    <span class="assessment-status-active">
                                        Active
                                    </span>
                                @else
                                    <span class="assessment-status">
                                        {{ ucfirst($assessment->status) }}
                                    </span>
                                @endif
                            </td>

                            <td>
                                {{ optional($assessment->created_at)->format('M j, Y') }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8">
                                <div class="assessment-empty">

                                    <h3>No assessments yet</h3>

                                    <p>
                                        Create your first organisation assessment
                                        to begin measuring employee readiness.
                                    </p>

                                    <a
                                        href="{{ route('organisation.assessments.create') }}"
                                        class="assessment-primary-button"
                                    >
                                        Create Assessment
                                    </a>

                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if($assessments->hasPages())
            <div class="assessment-pagination">
                {{ $assessments->links() }}
            </div>
        @endif

    </div>

</div>


<style>
    .organisation-assessments-page {
        max-width: 1500px;
        margin: 0 auto;
        padding: 28px 32px 50px;
    }

    .organisation-assessments-page .dashboard-header {
        display: flex !important;
        justify-content: space-between !important;
        align-items: flex-start !important;
        gap: 24px !important;
        margin-bottom: 26px !important;
    }

    .assessment-primary-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 11px 16px;
        border-radius: 9px;
        background: #2563eb;
        color: #fff;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        white-space: nowrap;
    }

    .assessment-alert {
        margin-bottom: 20px;
        padding: 13px 15px;
        border-radius: 10px;
        background: #eaf7ef;
        border: 1px solid #ccebd8;
        color: #18794e;
        font-size: 13px;
    }

    .assessment-stat-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .assessment-stat-card {
        padding: 19px 20px;
        border-radius: 15px;
        background: #fff;
        border: 1px solid rgba(15, 23, 42, .08);
        box-shadow: 0 5px 20px rgba(15, 23, 42, .045);
    }

    .assessment-stat-card span,
    .assessment-stat-card small {
        display: block;
    }

    .assessment-stat-card span {
        margin-bottom: 6px;
        font-size: 12px;
        opacity: .6;
    }

    .assessment-stat-card strong {
        display: block;
        margin-bottom: 6px;
        font-size: 28px;
        line-height: 1;
    }

    .assessment-stat-card small {
        font-size: 11px;
        opacity: .52;
    }

    .assessment-card {
        overflow: hidden;
        border-radius: 16px;
        background: #fff;
        border: 1px solid rgba(15, 23, 42, .08);
        box-shadow: 0 6px 24px rgba(15, 23, 42, .05);
    }

    .assessment-card-header {
        padding: 22px 24px;
        border-bottom: 1px solid rgba(15, 23, 42, .08);
    }

    .assessment-card-header h2 {
        margin: 0 0 5px;
        font-size: 19px;
    }

    .assessment-card-header p {
        margin: 0;
        font-size: 13px;
        opacity: .6;
    }

    .assessment-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .assessment-table {
        width: 100%;
        border-collapse: collapse;
    }

    .assessment-table th {
        padding: 12px 18px;
        text-align: left;
        white-space: nowrap;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: .05em;
        opacity: .55;
        border-bottom: 1px solid rgba(15, 23, 42, .09);
    }

    .assessment-table td {
        padding: 15px 18px;
        font-size: 13px;
        vertical-align: middle;
        border-bottom: 1px solid rgba(15, 23, 42, .07);
    }

    .assessment-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .assessment-table td strong,
    .assessment-created-by {
        display: block;
    }

    .assessment-created-by {
        margin-top: 3px;
        font-size: 10px;
        opacity: .5;
    }

    .assessment-status-active,
    .assessment-status {
        display: inline-flex;
        padding: 5px 9px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
    }

    .assessment-status-active {
        background: #e6f6ee;
        color: #18794e;
    }

    .assessment-status {
        background: #eef1f4;
        color: #667085;
    }

    .assessment-empty {
        padding: 50px 20px;
        text-align: center;
    }

    .assessment-empty h3 {
        margin: 0 0 7px;
        font-size: 16px;
    }

    .assessment-empty p {
        margin: 0 0 18px;
        font-size: 13px;
        opacity: .6;
    }

    .assessment-pagination {
        padding: 18px 24px;
        border-top: 1px solid rgba(15, 23, 42, .07);
    }

    @media (max-width: 1050px) {
        .assessment-stat-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 650px) {
        .organisation-assessments-page {
            padding: 20px 16px 40px;
        }

        .organisation-assessments-page .dashboard-header {
            flex-direction: column;
        }

        .assessment-stat-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection
