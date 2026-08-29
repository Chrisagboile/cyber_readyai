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
                    <a href="#"
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
                    <a href="#" class="btn btn-primary">
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

    </div>

</div>

@endsection
