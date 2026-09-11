@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <h2>Employee Dashboard</h2>

    <p>Welcome, {{ auth()->user()->name }}</p>

    <div class="row">

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Assessments</h5>
                    <p>Complete your cybersecurity assessments.</p>
                    <a href="href="{{ route('assessment.index') }}"
                       class="btn btn-primary">
                        Start Assessment
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>My Score</h5>
                    <p>View your cybersecurity readiness score.</p>
                    <a href="{{ route('employee.score') }}" class="btn btn-primary">
                        View Score
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Learning Plans</h5>
                    <p>Complete your assigned cybersecurity training.</p>
                    <a href="#" class="btn btn-primary">
                        Learning Plans
                    </a>
                </div>
            </div>
        </div>
<div class="action-grid">

    <a
        href="#"
        class="action-card"
    >

        <div class="action-icon">
            🛡️
        </div>

        <h3 class="action-title">
            Cybersecurity Assessment
        </h3>

        <p class="action-description">
            Complete your cybersecurity awareness
            assessment and receive your security score.
        </p>

        <span class="action-button">
            Start Assessment →
        </span>

    </a>

    <a
        href="{{ route('assessment.index') }}"
        class="action-card"
    >
        <div class="action-icon">
            📋
        </div>

        <div class="action-title">
            My Assessments
        </div>

        <div class="action-description">
            Complete your assigned cybersecurity assessments.
        </div>
    </a>
</div>
    </div>

</div>

@endsection
