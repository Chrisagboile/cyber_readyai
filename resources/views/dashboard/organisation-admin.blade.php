@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <h2>Organisation Admin Dashboard</h2>

    <p>Welcome, {{ auth()->user()->name }}</p>

    <div class="row">

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Users</h5>
                    <p>Manage organisation users.</p>
                    <a href="#" class="btn btn-primary">
                        Manage Users
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Assessments</h5>
                    <p>Launch cybersecurity assessments.</p>
                    <a href="#"
                       class="btn btn-primary">
                        Assessments
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Reports</h5>
                    <p>View organisation reports.</p>
                    <a href="#" class="btn btn-primary">
                        Reports
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Training</h5>
                    <p>Assign cybersecurity training.</p>
                    <a href="#" class="btn btn-primary">
                        Assign Training
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection
