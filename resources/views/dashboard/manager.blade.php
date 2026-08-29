@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <h2>Manager Dashboard</h2>

    <p>Welcome, {{ auth()->user()->name }}</p>

    <div class="row">

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Team Readiness</h5>
                    <p>View your team's cybersecurity readiness.</p>
                    <a href="#" class="btn btn-primary">
                        Team Readiness
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Department Reports</h5>
                    <p>View department assessment reports.</p>
                    <a href="#" class="btn btn-primary">
                        Department Reports
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Risk Dashboard</h5>
                    <p>Monitor cybersecurity risks.</p>
                    <a href="#" class="btn btn-primary">
                        Risk Dashboard
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection
