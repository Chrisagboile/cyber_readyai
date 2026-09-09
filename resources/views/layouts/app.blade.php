<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'Cyber ReadyAI')
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f7fb;
            color: #1f2937;
        }

        .app {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            background: #111827;
            color: white;
            padding: 24px 16px;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            overflow-y: auto;
        }

        .brand {
            font-size: 22px;
            font-weight: 700;
            padding: 0 12px 28px;
        }

        .brand span {
            color: #38bdf8;
        }

        .nav-title {
            color: #9ca3af;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 16px 12px 8px;
        }

        .nav-link {
            display: block;
            padding: 11px 12px;
            margin: 4px 0;
            border-radius: 8px;
            color: #d1d5db;
            text-decoration: none;
            transition: .2s;
        }

        .nav-link:hover {
            background: #1f2937;
            color: white;
        }

        .nav-link.active {
            background: #2563eb;
            color: white;
        }

        .main {
            margin-left: 260px;
            width: calc(100% - 260px);
            min-height: 100vh;
        }

        .topbar {
            height: 72px;
            background: white;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #2563eb;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        .content {
            padding: 30px;
        }

        .logout-btn {
            border: 0;
            background: transparent;
            color: #6b7280;
            cursor: pointer;
            font-size: 14px;
        }

        .logout-btn:hover {
            color: #dc2626;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 220px;
            }

            .main {
                margin-left: 220px;
                width: calc(100% - 220px);
            }

            .content {
                padding: 20px;
            }
        }
    </style>
</head>

<body>

<div class="app">

    {{-- Sidebar --}}
    <aside class="sidebar">

        <div class="brand">
            Cyber <span>ReadyAI</span>
        </div>

        <nav>

            {{-- Common --}}
            <div class="nav-title">Main</div>

            <a
                href="{{ route('dashboard') }}"
                class="nav-link"
            >
                Dashboard
            </a>


            {{-- Super Admin --}}
            @if(auth()->check() && auth()->user()->hasRole('super-admin'))
                <div class="nav-title">Administration</div>

                <a
                    href="{{ route('admin.dashboard') }}"
                    class="nav-link"
                >
                    Admin Dashboard
                </a>

                <a
                    href="{{ route('tenants.index') }}"
                    class="nav-link"
                >
                    Organisations
                </a>

                <a
                    href="{{ route('subscriptions.index') }}"
                    class="nav-link"
                >
                    Subscriptions
                </a>

                <a
                    href="{{ route('analytics.index') }}"
                    class="nav-link"
                >
                    Analytics
                </a>

                <a
                    href="{{ route('billing.index') }}"
                    class="nav-link"
                >
                    Billing
                </a>

            @endif


            {{-- Organisation Admin --}}
            @if(auth()->check() && auth()->user()->hasRole('organisation-admin'))
                <div class="nav-title">Organisation</div>

                <a
                    href="{{ route('organisation.dashboard') }}"
                    class="nav-link"
                >
                    Dashboard
                </a>

                <a
                    href="{{ route('organisation.users') }}"
                    class="nav-link"
                >
                    Users
                </a>

                <a
                    href="{{ route('organisation.assessments') }}"
                    class="nav-link"
                >
                    Assessments
                </a>

                <a
                    href="{{ route('organisation.reports') }}"
                    class="nav-link"
                >
                    Reports
                </a>

                <a
                    href="{{ route('organisation.training') }}"
                    class="nav-link"
                >
                    Training
                </a>

            @endif


            {{-- Manager --}}
            @if(auth()->check() && auth()->user()->hasRole('manager'))
                <div class="nav-title">Management</div>

                <a
                    href="{{ route('manager.dashboard') }}"
                    class="nav-link"
                >
                    Dashboard
                </a>

                <a
                    href="{{ route('manager.team-readiness') }}"
                    class="nav-link"
                >
                    Team Readiness
                </a>

                <a
                    href="{{ route('manager.department-reports') }}"
                    class="nav-link"
                >
                    Department Reports
                </a>

                <a
                    href="{{ route('manager.risk-dashboard') }}"
                    class="nav-link"
                >
                    Risk Dashboard
                </a>

            @endif


            {{-- Employee --}}
            @if(auth()->check() && auth()->user()->hasRole('employee'))

                <div class="nav-title">My Learning</div>

                <a
                    href="{{ route('employee.dashboard') }}"
                    class="nav-link"
                >
                    Dashboard
                </a>

                <a
                    href="{{ route('employee.assessments') }}"
                    class="nav-link"
                >
                    Assessments
                </a>

                <a
                    href="{{ route('employee.score') }}"
                    class="nav-link"
                >
                    My Score
                </a>

                <a
                    href="{{ route('employee.learning-plans') }}"
                    class="nav-link"
                >
                    Learning Plans
                </a>

            @endif

        </nav>

    </aside>

@auth
    {{-- Main Content --}}
    <main class="main">

        <header class="topbar">

            <div>
                <strong>
                    @yield('page-heading', 'Dashboard')
                </strong>
            </div>

            <div class="user-info">

                <div class="avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

                <div>
                    <strong>
                        {{ auth()->user()->name }}
                    </strong>

                    <br>

                    <small style="color: #6b7280;">
                        {{ auth()->user()->role?->name }}
                    </small>
                </div>
    @endauth
            <a href="{{ route('profile.edit') }}">
                My Profile
            </a>
        <form
                    method="POST"
                    action="{{ route('logout') }}"
                >
                    @csrf

                    <button
                        type="submit"
                        class="logout-btn"
                    >
                        Logout
                    </button>
                </form>

            </div>

        </header>


        <section class="content">

            @yield('content')

        </section>

    </main>

</div>

</body>
</html>
