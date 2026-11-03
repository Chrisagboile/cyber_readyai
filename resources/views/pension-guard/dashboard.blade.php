@extends('pension-guard.layout')

@section('title', 'Pension Account Guard')

@section('content')
    <section class="panel">
        <h2>Protect your pension, one step at a time</h2>
        <p>
            Pension Account Guard helps you organise pension records, follow
            practical security steps and recognise suspicious offers,
            messages and requests.
        </p>
        <p>
            Get information to help you protect your pension security.
            This service does not provide financial advice and will never
            ask for your pension login credentials.
        </p>

        <a class="button" href="{{ route('pension-guard.assessments.create') }}">
            Start security assessment
        </a>

        <a class="button" href="{{ route('pension-guard.threat-checker.create') }}">
            Check a suspicious offer
        </a>
    </section>

    <section class="panel">
        <h2>My pensions and security checklist</h2>
        <p>
            Keep a record of your pension providers and track the security
            steps you have completed for each account.
        </p>

        @auth('pension_guard')
            <a class="button" href="{{ route('pension-guard.accounts.index') }}">
                View my pensions
            </a>

            <a class="button" href="{{ route('pension-guard.accounts.create') }}">
                Add a pension
            </a>

            <p>
                To open a security checklist, select a pension account and
                open its checklist from the account details.
            </p>
        @else
            <p>
                Sign in to save pension records and manage your security
                checklists.
            </p>

            <a class="button" href="{{ route('pension-guard.login') }}">
                Sign in
            </a>

            <a class="button" href="{{ route('pension-guard.register') }}">
                Create an account
            </a>
        @endauth
    </section>

    <section class="panel">
        <h2>Check, learn and stay informed</h2>
        <p>
            Use the available tools to review warning signs, find trusted
            guidance and revisit your security information.
        </p>

        <p>
            <a class="button" href="{{ route('pension-guard.threat-checker.create') }}">
                Scam checker
            </a>
        </p>

        <p>
            <a class="button" href="{{ route('pension-guard.sources.index') }}">
                Official guidance
            </a>
        </p>

        @auth('pension_guard')
            <p>
                <a class="button" href="{{ route('pension-guard.assessments.history') }}">
                    Assessment history
                </a>
            </p>

            <p>
                <a class="button" href="{{ route('pension-guard.trusted-contacts.index') }}">
                    Trusted contacts
                </a>
            </p>

            <p>
                <a class="button" href="{{ route('pension-guard.notifications.index') }}">
                    Notifications and reminders
                </a>
            </p>
        @endauth
    </section>

    @auth('pension_guard')
        @if (auth('pension_guard')->user()?->is_admin)
            <section class="panel">
                <h2>Administration</h2>
                <p>
                    Manage official information sources, review audit records
                    and maintain the security incident register.
                </p>

                <p>
                    <a class="button" href="{{ route('pension-guard.admin.index') }}">
                        Admin dashboard
                    </a>
                </p>

                <p>
                    <a class="button" href="{{ route('pension-guard.admin.audit.index') }}">
                        Audit logs
                    </a>
                </p>

                <p>
                    <a class="button" href="{{ route('pension-guard.admin.sources.index') }}">
                        Manage official sources
                    </a>
                </p>

                <p>
                    <a class="button" href="{{ route('pension-guard.admin.incidents.index') }}">
                        Manage security incidents
                    </a>
                </p>
            </section>
        @endif
    @endauth

    <section class="panel">
        <h2>Important security reminder</h2>
        <p>
            Never share your pension password, one-time verification code
            or online banking credentials with someone contacting you
            unexpectedly. Use official provider contact details to verify
            suspicious requests.
        </p>
        <p>
            Pension Account Guard provides security information and
            educational guidance, not financial or investment advice.
        </p>
    </section>
@endsection
