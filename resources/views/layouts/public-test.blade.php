<!DOCTYPE html>

<html lang="en">

<head>

```
<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>
    @yield('title', 'Cyber ReadyAI')
</title>

@vite([
    'resources/css/app.css',
    'resources/js/app.js'
])

<style>

    * {
        box-sizing: border-box;
    }

    html,
    body {
        margin: 0;
        padding: 0;
    }

    body {
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
        margin-top: 0;
        padding: 0;
    }

    .content {
        width: 100%;
        margin: 0;
        padding: 30px;
    }

    .logout-btn {
        width: 100%;
        border: 0;
        background: transparent;
        color: #d1d5db;
        cursor: pointer;
        font-size: 14px;
        text-align: left;
        padding: 11px 12px;
        border-radius: 8px;
    }

    .logout-btn:hover {
        background: #1f2937;
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
```

</head>

<body>

<div class="app">

```
{{-- ============================================================
     SIDEBAR
     ============================================================ --}}

<aside class="sidebar">

    <div class="brand">
        Cyber <span>ReadyAI</span>
    </div>

    <nav>

        {{-- ====================================================
             AUTHENTICATED PUBLIC TEST USER
             ==================================================== --}}

        @auth('public_test')

            <div class="nav-title">
                Main
            </div>


            {{-- Dashboard --}}

            @if (Route::has('public-test.dashboard'))

                <a
                    href="{{ route('public-test.dashboard') }}"
                    class="nav-link"
                >
                    Dashboard
                </a>

            @endif


            {{-- Assessments --}}

            @if (Route::has('public-test.assessments'))

                <a
                    href="{{ route('public-test.assessments') }}"
                    class="nav-link"
                >
                    Assessments
                </a>

            @endif


            {{-- Learning --}}

            @if (Route::has('public-test.learning'))

                <a
                    href="{{ route('public-test.learning') }}"
                    class="nav-link"
                >
                    Learning
                </a>

            @endif


            {{-- =================================================
                 Get latest attempt for Review / Result
                 ================================================= --}}

            @php

                $latestAttempt = \App\Modules\PublicTest\Models\PublicTestAttempt::where(
                    'public_test_user_id',
                    auth('public_test')->id()
                )
                ->latest('id')
                ->first();

            @endphp


            {{-- Review --}}
            {{-- Result --}}

            @if (
                $latestAttempt &&
                in_array(
                    $latestAttempt->status,
                    ['completed', 'expired'],
                    true
                ) &&
                Route::has('public-test.result')
            )

                <a
                    href="{{ route(
                        'public-test.result',
                        ['attempt' => $latestAttempt->id]
                    ) }}"
                    class="nav-link"
                >
                    Result
                </a>

            @endif


            {{-- =================================================
                 Account
                 ================================================= --}}

            <div class="nav-title">
                Account
            </div>

            @if (Route::has('public-test.logout'))

                <form
                    method="POST"
                    action="{{ route('public-test.logout') }}"
                >

                    @csrf

                    <button
                        type="submit"
                        class="logout-btn"
                    >
                        Logout
                    </button>

                </form>

            @endif


        {{-- ====================================================
             GUEST USER
             ==================================================== --}}

        @else

            <div class="nav-title">
                Account
            </div>


            @if (Route::has('public-test.login'))

                <a
                    href="{{ route('public-test.login') }}"
                    class="nav-link"
                >
                    Login
                </a>

            @endif


            @if (Route::has('public-test.register'))

                <a
                    href="{{ route('public-test.register') }}"
                    class="nav-link"
                >
                    Register
                </a>

            @endif

        @endauth

    </nav>

</aside>


{{-- ============================================================
     MAIN CONTENT
     ============================================================ --}}

<main class="main">

    <div class="content">

        @yield('content')

    </div>

</main>

</div>

@stack('scripts')

</body>

</html>
