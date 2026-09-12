@extends('layouts.app')

@section('content')

<div class="org-training-page">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="org-training-header">
        <div>
            <span class="org-training-eyebrow">Organisation Learning</span>
            <h1>Training &amp; Remediation</h1>
            <p>
                Monitor learning-plan activity, employee progress,
                overdue remediation and organisation-wide training readiness.
            </p>
        </div>

        <div class="org-training-header-actions">
            <a href="{{ route('organisation.reports') }}" class="org-training-btn org-training-btn-secondary">
                View Reports
            </a>

            <a href="{{ route('organisation.assessments') }}" class="org-training-btn org-training-btn-primary">
                Manage Assessments
            </a>
        </div>
    </div>


    {{-- =========================================================
        KPI CARDS
    ========================================================== --}}
    <div class="org-training-kpis">

        <div class="org-training-kpi">
            <div class="org-training-kpi-icon">LP</div>
            <div>
                <span>Total Plans</span>
                <strong>{{ $totalPlans }}</strong>
                <small>Organisation learning plans</small>
            </div>
        </div>

        <div class="org-training-kpi">
            <div class="org-training-kpi-icon">✓</div>
            <div>
                <span>Completed</span>
                <strong>{{ $completedPlans }}</strong>
                <small>Successfully completed plans</small>
            </div>
        </div>

        <div class="org-training-kpi">
            <div class="org-training-kpi-icon">→</div>
            <div>
                <span>In Progress</span>
                <strong>{{ $inProgressPlans }}</strong>
                <small>Employees actively learning</small>
            </div>
        </div>

        <div class="org-training-kpi">
            <div class="org-training-kpi-icon">!</div>
            <div>
                <span>Overdue</span>
                <strong>{{ $overduePlans }}</strong>
                <small>Plans requiring attention</small>
            </div>
        </div>

        <div class="org-training-kpi">
            <div class="org-training-kpi-icon">%</div>
            <div>
                <span>Average Progress</span>
                <strong>{{ number_format($averageProgress, 1) }}%</strong>
                <small>Across all learning plans</small>
            </div>
        </div>

        <div class="org-training-kpi">
            <div class="org-training-kpi-icon">H</div>
            <div>
                <span>High Priority</span>
                <strong>{{ $highPriorityPlans }}</strong>
                <small>Open high-priority plans</small>
            </div>
        </div>

    </div>


    {{-- =========================================================
        OVERVIEW
    ========================================================== --}}
    <div class="org-training-main-grid">

        <div class="org-training-card">

            <div class="org-training-section-head">
                <div>
                    <h2>Learning Progress</h2>
                    <p>Current organisation-wide remediation progress.</p>
                </div>
            </div>

            <div class="org-training-progress-summary">
                <div class="org-training-progress-number">
                    <strong>{{ number_format($averageProgress, 1) }}%</strong>
                    <span>Average progress</span>
                </div>

                <div class="org-training-progress-track">
                    <div
                        class="org-training-progress-fill"
                        style="width: {{ min(100, max(0, $averageProgress)) }}%;"
                    ></div>
                </div>
            </div>

            <div class="org-training-mini-grid">

                <div>
                    <span>Not Started</span>
                    <strong>{{ $notStartedPlans }}</strong>
                </div>

                <div>
                    <span>In Progress</span>
                    <strong>{{ $inProgressPlans }}</strong>
                </div>

                <div>
                    <span>Completed</span>
                    <strong>{{ $completedPlans }}</strong>
                </div>

                <div>
                    <span>Employees With Plans</span>
                    <strong>{{ $employeesWithPlans }}</strong>
                </div>

            </div>
        </div>


        <div class="org-training-card">

            <div class="org-training-section-head">
                <div>
                    <h2>Training Coverage</h2>
                    <p>Employee coverage across the organisation.</p>
                </div>
            </div>

            <div class="org-training-coverage">

                @php
                    $employeeTotal = $employees->count();

                    $coverage = $employeeTotal > 0
                        ? ($employeesWithPlans / $employeeTotal) * 100
                        : 0;
                @endphp

                <div class="org-training-coverage-main">
                    <strong>{{ number_format($coverage, 1) }}%</strong>
                    <span>Employees with learning plans</span>
                </div>

                <div class="org-training-progress-track">
                    <div
                        class="org-training-progress-fill"
                        style="width: {{ min(100, max(0, $coverage)) }}%;"
                    ></div>
                </div>

                <div class="org-training-coverage-stats">
                    <div>
                        <span>Total Employees</span>
                        <strong>{{ $employeeTotal }}</strong>
                    </div>

                    <div>
                        <span>With Plans</span>
                        <strong>{{ $employeesWithPlans }}</strong>
                    </div>

                    <div>
                        <span>Without Plans</span>
                        <strong>{{ $employeesWithoutPlans }}</strong>
                    </div>
                </div>

            </div>
        </div>

    </div>


    {{-- =========================================================
        FILTERS
    ========================================================== --}}
    <div class="org-training-card">

        <div class="org-training-section-head">
            <div>
                <h2>Training Filters</h2>
                <p>Focus the learning-plan register on the activity you need to review.</p>
            </div>
        </div>

        <form method="GET" action="{{ route('organisation.training') }}" class="org-training-filters">

            <div class="org-training-field">
                <label for="department_id">Department</label>

                <select id="department_id" name="department_id">
                    <option value="">All departments</option>

                    @foreach($departments as $department)
                        <option
                            value="{{ $department->id }}"
                            @selected((string) $departmentId === (string) $department->id)
                        >
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
            </div>


            <div class="org-training-field">
                <label for="employee_id">Employee</label>

                <select id="employee_id" name="employee_id">
                    <option value="">All employees</option>

                    @foreach($employees as $employee)
                        <option
                            value="{{ $employee->id }}"
                            @selected((string) $employeeId === (string) $employee->id)
                        >
                            {{ $employee->name }}
                            @if($employee->department)
                                — {{ $employee->department->name }}
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>


            <div class="org-training-field">
                <label for="status">Status</label>

                <select id="status" name="status">
                    <option value="">All statuses</option>
                    <option value="not_started" @selected($status === 'not_started')>
                        Not Started
                    </option>
                    <option value="in_progress" @selected($status === 'in_progress')>
                        In Progress
                    </option>
                    <option value="completed" @selected($status === 'completed')>
                        Completed
                    </option>
                </select>
            </div>


            <div class="org-training-field">
                <label for="priority">Priority</label>

                <select id="priority" name="priority">
                    <option value="">All priorities</option>
                    <option value="high" @selected($priority === 'high')>
                        High
                    </option>
                    <option value="medium" @selected($priority === 'medium')>
                        Medium
                    </option>
                    <option value="low" @selected($priority === 'low')>
                        Low
                    </option>
                </select>
            </div>


            <div class="org-training-field">
                <label for="progress">Progress</label>

                <select id="progress" name="progress">
                    <option value="">All progress levels</option>
                    <option value="0" @selected($progress === '0')>
                        0%
                    </option>
                    <option value="1_49" @selected($progress === '1_49')>
                        1–49%
                    </option>
                    <option value="50_99" @selected($progress === '50_99')>
                        50–99%
                    </option>
                    <option value="100" @selected($progress === '100')>
                        100%
                    </option>
                </select>
            </div>


            <div class="org-training-filter-actions">
                <button type="submit" class="org-training-btn org-training-btn-primary">
                    Apply Filters
                </button>

                <a href="{{ route('organisation.training') }}" class="org-training-btn org-training-btn-secondary">
                    Reset
                </a>
            </div>

        </form>

    </div>


    {{-- =========================================================
        ATTENTION
    ========================================================== --}}
    <div class="org-training-card">

        <div class="org-training-section-head">
            <div>
                <h2>Employees Requiring Attention</h2>
                <p>
                    Employees with overdue plans, low progress or no current learning plan.
                </p>
            </div>
        </div>

        @if($attentionEmployees->count())

            <div class="org-training-attention-list">

                @foreach($attentionEmployees as $row)

                    @php
                        $employee = $row->employee;
                    @endphp

                    <div class="org-training-attention-row">

                        <div class="org-training-person">

                            <div class="org-training-avatar">
                                {{ strtoupper(substr($employee->name, 0, 1)) }}
                            </div>

                            <div>
                                <a
                                    href="{{ route('organisation.training.employee', $employee) }}"
                                    class="org-training-person-link"
                                >
                                    <strong>{{ $employee->name }}</strong>
                                </a>
                                <span>
                                    {{ optional($employee->department)->name ?? 'No department' }}
                                </span>
                            </div>

                        </div>


                        <div class="org-training-attention-tags">

                            @if($row->plans === 0)
                                <span class="org-training-badge org-training-badge-neutral">
                                    No Plan
                                </span>
                            @endif

                            @if($row->overdue > 0)
                                <span class="org-training-badge org-training-badge-danger">
                                    {{ $row->overdue }} Overdue
                                </span>
                            @endif

                            @if($row->average_progress < 50 && $row->plans > 0)
                                <span class="org-training-badge org-training-badge-warning">
                                    Low Progress
                                </span>
                            @endif

                            @if($row->in_progress > 0)
                                <span class="org-training-badge org-training-badge-info">
                                    {{ $row->in_progress }} In Progress
                                </span>
                            @endif

                        </div>


                        <div class="org-training-attention-progress">

                            <span>
                                {{ number_format($row->average_progress, 0) }}%
                            </span>

                            <div class="org-training-progress-track">
                                <div
                                    class="org-training-progress-fill"
                                    style="width: {{ min(100, max(0, $row->average_progress)) }}%;"
                                ></div>
                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <div class="org-training-empty">
                <div class="org-training-empty-icon">✓</div>
                <h3>No immediate training issues</h3>
                <p>
                    Your employees currently have no overdue or significantly incomplete
                    learning-plan activity requiring attention.
                </p>
            </div>

        @endif

    </div>


    {{-- =========================================================
        DEPARTMENT TRAINING
    ========================================================== --}}
    <div class="org-training-card">

        <div class="org-training-section-head">
            <div>
                <h2>Department Training Performance</h2>
                <p>Compare learning activity across departments.</p>
            </div>
        </div>

        @if($departmentTraining->count())

            <div class="org-training-table-wrap">

                <table class="org-training-table">

                    <thead>
                        <tr>
                            <th>Department</th>
                            <th>Employees</th>
                            <th>Plans</th>
                            <th>Completed</th>
                            <th>In Progress</th>
                            <th>Overdue</th>
                            <th>Avg. Progress</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($departmentTraining as $row)

                            <tr>

                                <td>
                                    <strong>{{ $row->department->name }}</strong>
                                </td>

                                <td>
                                    {{ $row->employees }}
                                </td>

                                <td>
                                    {{ $row->plans }}
                                </td>

                                <td>
                                    <span class="org-training-badge org-training-badge-success">
                                        {{ $row->completed }}
                                    </span>
                                </td>

                                <td>
                                    {{ $row->in_progress }}
                                </td>

                                <td>

                                    @if($row->overdue > 0)
                                        <span class="org-training-badge org-training-badge-danger">
                                            {{ $row->overdue }}
                                        </span>
                                    @else
                                        <span class="org-training-muted">
                                            0
                                        </span>
                                    @endif

                                </td>

                                <td>

                                    <div class="org-training-table-progress">

                                        <div class="org-training-table-progress-top">
                                            <span>{{ number_format($row->average_progress, 0) }}%</span>
                                        </div>

                                        <div class="org-training-progress-track">
                                            <div
                                                class="org-training-progress-fill"
                                                style="width: {{ min(100, max(0, $row->average_progress)) }}%;"
                                            ></div>
                                        </div>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="org-training-empty">
                <h3>No department data available</h3>
                <p>Training information will appear here once learning plans are created.</p>
            </div>

        @endif

    </div>


    {{-- =========================================================
        LEARNING PLAN REGISTER
    ========================================================== --}}
    <div class="org-training-card">

        <div class="org-training-section-head">

            <div>
                <h2>Learning Plan Register</h2>
                <p>
                    Current learning and remediation plans matching your filters.
                </p>
            </div>

            <div class="org-training-result-count">
                {{ $plans->count() }} result{{ $plans->count() === 1 ? '' : 's' }}
            </div>

        </div>


        @if($plans->count())

            <div class="org-training-plan-list">

                @foreach($plans as $plan)

                    @php
                        $planStatus = strtolower((string) $plan->status);
                        $planPriority = strtolower((string) $plan->priority);
                        $planProgress = min(100, max(0, (int) $plan->progress_percentage));
                        $isOverdue = $plan->due_date
                            && $planStatus !== 'completed'
                            && $plan->due_date->isPast();
                    @endphp

                    <div class="org-training-plan-row">

                        <div class="org-training-plan-main">

                            <div class="org-training-plan-icon">
                                ✓
                            </div>

                            <div>

                                <h3>{{ $plan->title }}</h3>

                                <p>
                                    {{ optional($plan->user)->name ?? 'Unknown employee' }}

                                    @if(optional($plan->user)->department)
                                        · {{ $plan->user->department->name }}
                                    @endif
                                </p>

                                @if($plan->assessmentAttempt && $plan->assessmentAttempt->assessment)

                                    <small>
                                        From assessment:
                                        <strong>
                                            {{ $plan->assessmentAttempt->assessment->name }}
                                        </strong>
                                    </small>

                                @endif

                            </div>

                        </div>


                        <div class="org-training-plan-progress">

                            <div class="org-training-plan-progress-top">
                                <span>Progress</span>
                                <strong>{{ $planProgress }}%</strong>
                            </div>

                            <div class="org-training-progress-track">
                                <div
                                    class="org-training-progress-fill"
                                    style="width: {{ $planProgress }}%;"
                                ></div>
                            </div>

                        </div>


                        <div class="org-training-plan-meta">

                            <div>

                                @if($planStatus === 'completed')

                                    <span class="org-training-badge org-training-badge-success">
                                        Completed
                                    </span>

                                @elseif($planStatus === 'in_progress')

                                    <span class="org-training-badge org-training-badge-info">
                                        In Progress
                                    </span>

                                @else

                                    <span class="org-training-badge org-training-badge-neutral">
                                        Not Started
                                    </span>

                                @endif

                            </div>


                            <div>

                                @if($planPriority === 'high')
                                    <span class="org-training-badge org-training-badge-danger">
                                        High Priority
                                    </span>
                                @elseif($planPriority === 'medium')
                                    <span class="org-training-badge org-training-badge-warning">
                                        Medium Priority
                                    </span>
                                @elseif($planPriority === 'low')
                                    <span class="org-training-badge org-training-badge-success">
                                        Low Priority
                                    </span>
                                @endif

                            </div>


                            <div class="org-training-plan-due">

                                @if($plan->due_date)

                                    @if($isOverdue)
                                        <span class="org-training-overdue">
                                            Overdue:
                                            {{ $plan->due_date->format('M j, Y') }}
                                        </span>
                                    @else
                                        <span>
                                            Due:
                                            {{ $plan->due_date->format('M j, Y') }}
                                        </span>
                                    @endif

                                @else

                                    <span>No due date</span>

                                @endif

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <div class="org-training-empty">
                <div class="org-training-empty-icon">LP</div>

                <h3>No learning plans found</h3>

                <p>
                    No plans match the current filters.
                    Try resetting the filters or complete an assessment to generate
                    new remediation plans.
                </p>

                <a
                    href="{{ route('organisation.assessments') }}"
                    class="org-training-btn org-training-btn-primary"
                >
                    Manage Assessments
                </a>
            </div>

        @endif

    </div>

</div>


<style>
.org-training-person-link {
    color: inherit;
    text-decoration: none;
}

.org-training-person-link:hover {
    text-decoration: underline;
}

.org-training-page {
    max-width: 1500px;
    margin: 0 auto;
    padding: 8px 0 40px;
}

.org-training-header {
    display: flex;
    justify-content: space-between;
    gap: 30px;
    align-items: flex-end;
    margin-bottom: 26px;
}

.org-training-eyebrow {
    display: block;
    margin-bottom: 7px;
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .09em;
    opacity: .58;
}

.org-training-header h1 {
    margin: 0;
    font-size: 30px;
    line-height: 1.15;
}

.org-training-header p {
    margin: 8px 0 0;
    max-width: 760px;
    opacity: .68;
}

.org-training-header-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.org-training-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 42px;
    padding: 0 16px;
    border-radius: 10px;
    text-decoration: none;
    border: 1px solid transparent;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    white-space: nowrap;
}

.org-training-btn-primary {
    background: #111827;
    color: #fff;
}

.org-training-btn-primary:hover {
    color: #fff;
    opacity: .92;
}

.org-training-btn-secondary {
    background: rgba(127, 127, 127, .08);
    color: inherit;
    border-color: rgba(127, 127, 127, .16);
}

.org-training-btn-secondary:hover {
    color: inherit;
    background: rgba(127, 127, 127, .13);
}

.org-training-kpis {
    display: grid;
    grid-template-columns: repeat(6, minmax(0, 1fr));
    gap: 14px;
    margin-bottom: 22px;
}

.org-training-kpi {
    display: flex;
    gap: 12px;
    align-items: center;
    padding: 16px;
    border-radius: 14px;
    border: 1px solid rgba(127, 127, 127, .14);
    background: var(--card-bg, #fff);
    box-shadow: 0 5px 18px rgba(0, 0, 0, .03);
}

.org-training-kpi-icon {
    width: 40px;
    height: 40px;
    flex: 0 0 40px;
    border-radius: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(127, 127, 127, .10);
    font-size: 12px;
    font-weight: 800;
}

.org-training-kpi span,
.org-training-kpi small {
    display: block;
}

.org-training-kpi span {
    font-size: 12px;
    opacity: .62;
    margin-bottom: 3px;
}

.org-training-kpi strong {
    display: block;
    font-size: 22px;
    line-height: 1.1;
}

.org-training-kpi small {
    margin-top: 4px;
    font-size: 10px;
    opacity: .52;
}

.org-training-main-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 22px;
    margin-bottom: 22px;
}

.org-training-card {
    border: 1px solid rgba(127, 127, 127, .14);
    border-radius: 16px;
    background: var(--card-bg, #fff);
    box-shadow: 0 7px 24px rgba(0, 0, 0, .035);
    padding: 22px;
    margin-bottom: 22px;
}

.org-training-section-head {
    display: flex;
    justify-content: space-between;
    gap: 15px;
    align-items: flex-start;
    margin-bottom: 20px;
}

.org-training-section-head h2 {
    margin: 0;
    font-size: 18px;
}

.org-training-section-head p {
    margin: 5px 0 0;
    font-size: 13px;
    opacity: .62;
}

.org-training-progress-summary {
    display: grid;
    grid-template-columns: 130px 1fr;
    align-items: center;
    gap: 18px;
    margin-bottom: 18px;
}

.org-training-progress-number strong {
    display: block;
    font-size: 31px;
    line-height: 1;
}

.org-training-progress-number span {
    display: block;
    margin-top: 7px;
    font-size: 11px;
    opacity: .58;
}

.org-training-progress-track {
    height: 8px;
    overflow: hidden;
    border-radius: 999px;
    background: rgba(127, 127, 127, .12);
}

.org-training-progress-fill {
    height: 100%;
    border-radius: inherit;
    background: currentColor;
    opacity: .78;
}

.org-training-mini-grid,
.org-training-coverage-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
}

.org-training-mini-grid > div,
.org-training-coverage-stats > div {
    padding: 13px;
    border-radius: 11px;
    background: rgba(127, 127, 127, .07);
}

.org-training-mini-grid span,
.org-training-coverage-stats span {
    display: block;
    font-size: 11px;
    opacity: .58;
    margin-bottom: 4px;
}

.org-training-mini-grid strong,
.org-training-coverage-stats strong {
    font-size: 18px;
}

.org-training-coverage-main strong {
    display: block;
    font-size: 36px;
}

.org-training-coverage-main span {
    display: block;
    margin-top: 5px;
    margin-bottom: 16px;
    font-size: 12px;
    opacity: .58;
}

.org-training-coverage .org-training-progress-track {
    margin-bottom: 18px;
}

.org-training-filters {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr)) auto;
    gap: 13px;
    align-items: end;
}

.org-training-field label {
    display: block;
    margin-bottom: 7px;
    font-size: 12px;
    font-weight: 700;
}

.org-training-field select {
    width: 100%;
    min-height: 42px;
    padding: 0 11px;
    border-radius: 9px;
    border: 1px solid rgba(127, 127, 127, .18);
    background: transparent;
    color: inherit;
    font-size: 13px;
}

.org-training-field select:focus {
    outline: none;
    border-color: rgba(90, 90, 90, .55);
}

.org-training-filter-actions {
    display: flex;
    gap: 8px;
}

.org-training-attention-list {
    border-top: 1px solid rgba(127, 127, 127, .10);
}

.org-training-attention-row {
    display: grid;
    grid-template-columns: minmax(220px, 1.3fr) minmax(230px, 1fr) minmax(160px, .7fr);
    gap: 18px;
    align-items: center;
    padding: 15px 0;
    border-bottom: 1px solid rgba(127, 127, 127, .10);
}

.org-training-person {
    display: flex;
    align-items: center;
    gap: 12px;
}

.org-training-avatar {
    width: 38px;
    height: 38px;
    flex: 0 0 38px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(127, 127, 127, .10);
    font-weight: 800;
}

.org-training-person strong,
.org-training-person span {
    display: block;
}

.org-training-person span {
    margin-top: 3px;
    font-size: 11px;
    opacity: .56;
}

.org-training-attention-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 7px;
}

.org-training-badge {
    display: inline-flex;
    align-items: center;
    min-height: 25px;
    padding: 0 8px;
    border-radius: 999px;
    font-size: 10px;
    font-weight: 800;
}

.org-training-badge-neutral {
    background: rgba(127, 127, 127, .10);
}

.org-training-badge-danger {
    background: rgba(220, 53, 69, .11);
    color: #b42332;
}

.org-training-badge-warning {
    background: rgba(240, 173, 0, .13);
    color: #966b00;
}

.org-training-badge-info {
    background: rgba(30, 110, 190, .11);
    color: #1f6099;
}

.org-training-badge-success {
    background: rgba(46, 157, 99, .11);
    color: #25784c;
}

.org-training-attention-progress {
    min-width: 0;
}

.org-training-attention-progress > span {
    display: block;
    margin-bottom: 6px;
    text-align: right;
    font-size: 12px;
    font-weight: 700;
}

.org-training-table-wrap {
    width: 100%;
    overflow-x: auto;
}

.org-training-table {
    width: 100%;
    border-collapse: collapse;
}

.org-training-table th {
    padding: 11px 10px;
    text-align: left;
    border-bottom: 1px solid rgba(127, 127, 127, .14);
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: .05em;
    opacity: .58;
    white-space: nowrap;
}

.org-training-table td {
    padding: 15px 10px;
    border-bottom: 1px solid rgba(127, 127, 127, .09);
    font-size: 13px;
}

.org-training-table tr:last-child td {
    border-bottom: 0;
}

.org-training-table-progress {
    min-width: 130px;
}

.org-training-table-progress-top {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 5px;
    font-size: 11px;
    font-weight: 700;
}

.org-training-plan-list {
    display: grid;
    gap: 10px;
}

.org-training-plan-row {
    display: grid;
    grid-template-columns: minmax(260px, 1.25fr) minmax(180px, .85fr) minmax(210px, .85fr);
    gap: 18px;
    align-items: center;
    padding: 16px;
    border: 1px solid rgba(127, 127, 127, .11);
    border-radius: 12px;
    background: rgba(127, 127, 127, .025);
}

.org-training-plan-main {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.org-training-plan-icon {
    width: 37px;
    height: 37px;
    flex: 0 0 37px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(127, 127, 127, .10);
    font-size: 13px;
    font-weight: 800;
}

.org-training-plan-main h3 {
    margin: 0;
    font-size: 14px;
}

.org-training-plan-main p {
    margin: 4px 0 0;
    font-size: 12px;
    opacity: .62;
}

.org-training-plan-main small {
    display: block;
    margin-top: 7px;
    font-size: 10px;
    opacity: .55;
}

.org-training-plan-progress-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 6px;
    font-size: 11px;
    opacity: .68;
}

.org-training-plan-progress-top strong {
    font-size: 12px;
    opacity: 1;
}

.org-training-plan-meta {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    flex-wrap: wrap;
    gap: 7px;
}

.org-training-plan-due {
    width: 100%;
    text-align: right;
    margin-top: 3px;
    font-size: 11px;
    opacity: .58;
}

.org-training-overdue {
    color: #b42332;
    font-weight: 700;
}

.org-training-result-count {
    padding: 7px 10px;
    border-radius: 999px;
    background: rgba(127, 127, 127, .08);
    font-size: 11px;
    font-weight: 700;
}

.org-training-muted {
    opacity: .52;
}

.org-training-empty {
    padding: 36px 15px 22px;
    text-align: center;
}

.org-training-empty-icon {
    width: 44px;
    height: 44px;
    margin: 0 auto 12px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(127, 127, 127, .09);
    font-size: 13px;
    font-weight: 800;
}

.org-training-empty h3 {
    margin: 0 0 6px;
    font-size: 16px;
}

.org-training-empty p {
    max-width: 620px;
    margin: 0 auto 18px;
    font-size: 13px;
    opacity: .62;
}

@media (max-width: 1250px) {
    .org-training-kpis {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .org-training-filters {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .org-training-filter-actions {
        grid-column: 1 / -1;
    }

    .org-training-plan-row {
        grid-template-columns: 1fr 1fr;
    }

    .org-training-plan-meta {
        justify-content: flex-start;
    }

    .org-training-plan-due {
        text-align: left;
    }
}

@media (max-width: 980px) {
    .org-training-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .org-training-main-grid {
        grid-template-columns: 1fr;
    }

    .org-training-attention-row {
        grid-template-columns: 1fr;
    }

    .org-training-attention-progress {
        max-width: 260px;
    }

    .org-training-attention-progress > span {
        text-align: left;
    }
}

@media (max-width: 700px) {
    .org-training-kpis {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .org-training-filters {
        grid-template-columns: 1fr;
    }

    .org-training-filter-actions {
        grid-column: auto;
    }

    .org-training-mini-grid,
    .org-training-coverage-stats {
        grid-template-columns: repeat(2, 1fr);
    }

    .org-training-plan-row {
        grid-template-columns: 1fr;
    }

    .org-training-progress-summary {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 470px) {
    .org-training-kpis {
        grid-template-columns: 1fr;
    }

    .org-training-card {
        padding: 16px;
    }

    .org-training-header h1 {
        font-size: 25px;
    }
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const employeeSelect = document.getElementById('employee_id');

    if (!employeeSelect) {
        return;
    }

    employeeSelect.addEventListener('dblclick', function () {
        const employeeId = this.value;

        if (!employeeId) {
            return;
        }

        window.location.href =
            "{{ url('/organisation/training/employee') }}/" + employeeId;
    });
});
</script>
@endsection
