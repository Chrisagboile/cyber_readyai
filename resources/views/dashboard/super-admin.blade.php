@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <h2>Super Admin Dashboard</h2>

    <p>Welcome, {{ auth()->user()->name }}</p>

    <div class="row">

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Tenants</h5>
                    <p>Manage organisations.</p>
                    <a href="#" class="btn btn-primary">
                        Manage Tenants
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Subscriptions</h5>
                    <p>Manage platform subscriptions.</p>
                    <a href="#" class="btn btn-primary">
                        Subscriptions
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Analytics</h5>
                    <p>View platform analytics.</p>
                    <a href="#" class="btn btn-primary">
                        Analytics
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Billing</h5>
                    <p>Manage platform billing.</p>
                    <a href="#" class="btn btn-primary">
                        Billing
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection
