
@extends('layouts.app')

@section('content')
<div class="phishing-landing">
    <div class="pl-container">

        {{-- Learning outcome banner --}}
        <div class="pl-banner">
            <div class="pl-banner-icon">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M12 3L20 6V11C20 16 16.6 19.5 12 21C7.4 19.5 4 16 4 11V6L12 3Z"
                          stroke="currentColor" stroke-width="1.7"
                          stroke-linejoin="round"/>
                    <path d="M9 12L11 14L15.5 9.5"
                          stroke="currentColor" stroke-width="1.7"
                          stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>

            <div>
                <span class="pl-banner-label">CYBERREADYAI · SECURITY AWARENESS</span>
                <h1>Simulation complete. Learning starts here.</h1>
                <p>
                    Recognising the warning signs is an important step towards
                    safer decisions online.
                </p>
            </div>
        </div>

        {{-- Main learning card --}}
        <main class="pl-card">
            <div class="pl-result">
                <div class="pl-result-icon">
                    <svg viewBox="0 0 64 64" fill="none" aria-hidden="true">
                        <rect x="9" y="15" width="46" height="34" rx="7"
                              stroke="currentColor" stroke-width="2.5"/>
                        <path d="M12 20L32 35L52 20"
                              stroke="currentColor" stroke-width="2.5"
                              stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="48" cy="45" r="11" fill="#E6F7EE"
                                stroke="#168454" stroke-width="2"/>
                        <path d="M43 45L46.5 48.5L53 41.5"
                              stroke="#168454" stroke-width="2.2"
                              stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>

                <span class="pl-result-label">AWARENESS EXERCISE</span>
                <h2>This was a phishing simulation</h2>
                <p>
                    You opened a simulated message as part of a controlled
                    cybersecurity awareness exercise. This is a learning
                    opportunity, not a disciplinary judgement.
                </p>
            </div>

            <div class="pl-divider"></div>

            {{-- Learning points --}}
            <section class="pl-learning">
                <div class="pl-section-heading">
                    <span class="pl-section-icon">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M12 3L20 6V11C20 16 16.6 19.5 12 21C7.4 19.5 4 16 4 11V6L12 3Z"
                                  stroke="currentColor" stroke-width="1.7"
                                  stroke-linejoin="round"/>
                        </svg>
                    </span>

                    <div>
                        <h3>What to check next time</h3>
                        <p>Build these quick checks into your everyday routine.</p>
                    </div>
                </div>

                <div class="pl-checklist">
                    <div class="pl-check-item">
                        <span class="pl-check-number">01</span>
                        <div>
                            <h4>Verify the sender</h4>
                            <p>
                                Check the full email address, not just the display
                                name. Ask yourself whether the message is expected.
                            </p>
                        </div>
                    </div>

                    <div class="pl-check-item">
                        <span class="pl-check-number">02</span>
                        <div>
                            <h4>Inspect links before opening them</h4>
                            <p>
                                Check the destination carefully and look for
                                misspellings, unexpected domains, or misleading links.
                            </p>
                        </div>
                    </div>

                    <div class="pl-check-item">
                        <span class="pl-check-number">03</span>
                        <div>
                            <h4>Recognise pressure tactics</h4>
                            <p>
                                Be cautious of urgent deadlines, threats, unexpected
                                attachments, prizes, or requests for immediate action.
                            </p>
                        </div>
                    </div>

                    <div class="pl-check-item">
                        <span class="pl-check-number">04</span>
                        <div>
                            <h4>Protect passwords and sensitive data</h4>
                            <p>
                                Never enter a real password or confidential information
                                into an unexpected or unverified page.
                            </p>
                        </div>
                    </div>

                    <div class="pl-check-item">
                        <span class="pl-check-number">05</span>
                        <div>
                            <h4>Report suspicious messages</h4>
                            <p>
                                Use your organisation's approved reporting process.
                                If unsure, verify the request through a trusted channel.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Report action --}}
            <div class="pl-report-panel">
                <div class="pl-report-icon">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M5 4H19V20L12 16L5 20V4Z"
                              stroke="currentColor" stroke-width="1.7"
                              stroke-linejoin="round"/>
                        <path d="M9 9H15M9 12H13"
                              stroke="currentColor" stroke-width="1.7"
                              stroke-linecap="round"/>
                    </svg>
                </div>

                <div class="pl-report-copy">
                    <h3>Practise reporting this message</h3>
                    <p>
                        Record that you have completed the reporting exercise.
                        This helps track your participation in the awareness activity.
                    </p>
                </div>
            </div>

            <form method="POST"
                  action="{{ route('phishing.report', $recipient) }}"
                  class="pl-actions">
                @csrf

                <button type="submit" class="pl-primary-button">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M5 4H19V20L12 16L5 20V4Z"
                              stroke="currentColor" stroke-width="1.8"
                              stroke-linejoin="round"/>
                        <path d="M9 9H15"
                              stroke="currentColor" stroke-width="1.8"
                              stroke-linecap="round"/>
                    </svg>
                    Practise reporting this message
                    <svg class="pl-arrow" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M5 12H19M13 6L19 12L13 18"
                              stroke="currentColor" stroke-width="1.8"
                              stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>

                <a href="{{ route('phishing.employee') }}" class="pl-secondary-button">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M19 12H5M11 18L5 12L11 6"
                              stroke="currentColor" stroke-width="1.8"
                              stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Return to my simulations
                </a>
            </form>

            <div class="pl-privacy-note">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <rect x="5" y="10" width="14" height="11" rx="2"
                          stroke="currentColor" stroke-width="1.7"/>
                    <path d="M8 10V7C8 4.8 9.8 3 12 3C14.2 3 16 4.8 16 7V10"
                          stroke="currentColor" stroke-width="1.7"
                          stroke-linecap="round"/>
                </svg>
                <span>
                    This is a controlled, in-app training exercise. Never provide
                    real credentials or sensitive personal information.
                </span>
            </div>
        </main>

        <footer class="pl-footer">
            <span>CYBERREADYAI</span>
            <span>Learn · Recognise · Report · Improve</span>
        </footer>

    </div>
</div>

<style>
    .phishing-landing {
        --pl-navy: #14243b;
        --pl-blue: #315be8;
        --pl-blue-dark: #2448c7;
        --pl-text: #26364d;
        --pl-muted: #718096;
        --pl-border: #e4eaf2;
        background: #f4f7fc;
        color: var(--pl-text);
        padding: 34px 0 40px;
        min-height: 75vh;
    }

    .phishing-landing,
    .phishing-landing * {
        box-sizing: border-box;
    }

    .phishing-landing .pl-container {
        width: min(850px, calc(100% - 36px));
        margin: 0 auto;
    }

    .phishing-landing .pl-banner {
        display: flex;
        align-items: center;
        gap: 19px;
        padding: 25px 27px;
        margin-bottom: 21px;
        border-radius: 17px;
        background: linear-gradient(115deg, #172b49 0%, #233e6b 60%, #315be8 100%);
        box-shadow: 0 12px 28px rgba(29, 53, 95, .12);
    }

    .phishing-landing .pl-banner-icon {
        display: grid;
        place-items: center;
        width: 57px;
        height: 57px;
        flex: 0 0 57px;
        color: #dbe5ff;
        background: rgba(255,255,255,.11);
        border: 1px solid rgba(255,255,255,.2);
        border-radius: 15px;
    }

    .phishing-landing .pl-banner-icon svg {
        width: 33px;
        height: 33px;
    }

    .phishing-landing .pl-banner-label {
        display: block;
        color: #c5d5ff;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 1.2px;
        margin-bottom: 8px;
    }

    .phishing-landing .pl-banner h1 {
        color: #fff;
        font-size: clamp(20px, 3vw, 26px);
        line-height: 1.3;
        letter-spacing: -.5px;
        font-weight: 800;
        margin: 0 0 7px;
    }

    .phishing-landing .pl-banner p {
        color: #d7e2f8;
        font-size: 12px;
        line-height: 1.7;
        margin: 0;
    }

    .phishing-landing .pl-card {
        padding: 35px 38px 26px;
        background: #fff;
        border: 1px solid var(--pl-border);
        border-radius: 18px;
        box-shadow: 0 5px 20px rgba(23, 42, 73, .035);
    }

    .phishing-landing .pl-result {
        text-align: center;
        max-width: 600px;
        margin: 0 auto;
    }

    .phishing-landing .pl-result-icon {
        display: grid;
        place-items: center;
        width: 83px;
        height: 83px;
        margin: 0 auto 16px;
        background: #edf3ff;
        border: 1px solid #dce6ff;
        border-radius: 24px;
    }

    .phishing-landing .pl-result-icon svg {
        width: 59px;
        height: 59px;
    }

    .phishing-landing .pl-result-label {
        display: inline-block;
        color: #315be8;
        background: #edf2ff;
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: .9px;
        margin-bottom: 12px;
    }

    .phishing-landing .pl-result h2 {
        color: var(--pl-navy);
        font-size: clamp(22px, 3vw, 29px);
        font-weight: 800;
        letter-spacing: -.7px;
        line-height: 1.3;
        margin: 0 0 11px;
    }

    .phishing-landing .pl-result > p {
        color: var(--pl-muted);
        font-size: 13px;
        line-height: 1.85;
        margin: 0;
    }

    .phishing-landing .pl-divider {
        height: 1px;
        background: #edf0f5;
        margin: 30px 0;
    }

    .phishing-landing .pl-section-heading {
        display: flex;
        align-items: center;
        gap: 13px;
        margin-bottom: 22px;
    }

    .phishing-landing .pl-section-icon {
        display: grid;
        place-items: center;
        width: 43px;
        height: 43px;
        flex: 0 0 43px;
        color: var(--pl-blue);
        background: #edf2ff;
        border-radius: 12px;
    }

    .phishing-landing .pl-section-icon svg {
        width: 24px;
        height: 24px;
    }

    .phishing-landing .pl-section-heading h3 {
        color: var(--pl-navy);
        font-size: 17px;
        font-weight: 800;
        margin: 0 0 5px;
    }

    .phishing-landing .pl-section-heading p {
        color: var(--pl-muted);
        font-size: 11px;
        line-height: 1.6;
        margin: 0;
    }

    .phishing-landing .pl-checklist {
        display: grid;
        gap: 0;
    }

    .phishing-landing .pl-check-item {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        padding: 17px 0;
        border-bottom: 1px solid #edf0f5;
    }

    .phishing-landing .pl-check-item:first-child {
        padding-top: 0;
    }

    .phishing-landing .pl-check-item:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .phishing-landing .pl-check-number {
        display: grid;
        place-items: center;
        width: 34px;
        height: 34px;
        flex: 0 0 34px;
        color: #315be8;
        background: #edf2ff;
        border-radius: 10px;
        font-size: 10px;
        font-weight: 800;
        font-variant-numeric: tabular-nums;
    }

    .phishing-landing .pl-check-item h4 {
        color: #253650;
        font-size: 12px;
        font-weight: 800;
        margin: 1px 0 5px;
    }

    .phishing-landing .pl-check-item p {
        color: #718096;
        font-size: 11px;
        line-height: 1.8;
        margin: 0;
    }

    .phishing-landing .pl-report-panel {
        display: flex;
        align-items: flex-start;
        gap: 13px;
        margin-top: 29px;
        padding: 17px;
        border: 1px solid #dce6ff;
        border-radius: 13px;
        background: #f0f5ff;
    }

    .phishing-landing .pl-report-icon {
        display: grid;
        place-items: center;
        width: 38px;
        height: 38px;
        flex: 0 0 38px;
        color: #315be8;
        background: #fff;
        border: 1px solid #dce6ff;
        border-radius: 10px;
    }

    .phishing-landing .pl-report-icon svg {
        width: 22px;
        height: 22px;
    }

    .phishing-landing .pl-report-copy h3 {
        color: #233b68;
        font-size: 12px;
        font-weight: 800;
        margin: 1px 0 5px;
    }

    .phishing-landing .pl-report-copy p {
        color: #596e91;
        font-size: 11px;
        line-height: 1.75;
        margin: 0;
    }

    .phishing-landing .pl-actions {
        display: grid;
        grid-template-columns: 1fr;
        gap: 10px;
        margin-top: 19px;
    }

    .phishing-landing .pl-primary-button,
    .phishing-landing .pl-secondary-button {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        min-height: 47px;
        padding: 12px 16px;
        border-radius: 10px;
        text-align: center;
        text-decoration: none !important;
        font-size: 12px;
        font-weight: 750;
        line-height: 1.5;
        cursor: pointer;
        transition: background .18s ease, border-color .18s ease;
    }

    .phishing-landing .pl-primary-button {
        color: #fff !important;
        background: var(--pl-blue);
        border: 1px solid var(--pl-blue);
    }

    .phishing-landing .pl-primary-button:hover {
        color: #fff !important;
        background: var(--pl-blue-dark);
        border-color: var(--pl-blue-dark);
    }

    .phishing-landing .pl-secondary-button {
        color: #42536d !important;
        background: #fff;
        border: 1px solid #dce3ed;
    }

    .phishing-landing .pl-secondary-button:hover {
        color: var(--pl-navy) !important;
        background: #f7f9fc;
        border-color: #bdc9da;
    }

    .phishing-landing .pl-primary-button svg,
    .phishing-landing .pl-secondary-button svg {
        width: 18px;
        height: 18px;
        flex: 0 0 18px;
    }

    .phishing-landing .pl-primary-button .pl-arrow {
        margin-left: auto;
    }

    .phishing-landing .pl-privacy-note {
        display: flex;
        align-items: flex-start;
        justify-content: center;
        gap: 8px;
        margin-top: 20px;
        color: #8390a3;
        font-size: 10px;
        line-height: 1.7;
        text-align: center;
    }

    .phishing-landing .pl-privacy-note svg {
        width: 15px;
        height: 15px;
        flex: 0 0 15px;
        margin-top: 1px;
    }

    .phishing-landing .pl-footer {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        padding: 18px 4px 0;
        color: #8793a5;
        font-size: 9px;
        letter-spacing: .3px;
    }

    .phishing-landing .pl-footer span:first-child {
        color: #5e6e86;
        font-weight: 800;
        letter-spacing: 1px;
    }

    @media (max-width: 600px) {
        .phishing-landing {
            padding: 20px 0 28px;
        }

        .phishing-landing .pl-container {
            width: calc(100% - 26px);
        }

        .phishing-landing .pl-banner {
            align-items: flex-start;
            gap: 13px;
            padding: 19px;
            border-radius: 14px;
        }

        .phishing-landing .pl-banner-icon {
            width: 43px;
            height: 43px;
            flex-basis: 43px;
            border-radius: 12px;
        }

        .phishing-landing .pl-banner-icon svg {
            width: 27px;
            height: 27px;
        }

        .phishing-landing .pl-banner-label {
            font-size: 8px;
            letter-spacing: .7px;
        }

        .phishing-landing .pl-banner h1 {
            font-size: 20px;
        }

        .phishing-landing .pl-banner p {
            font-size: 11px;
        }

        .phishing-landing .pl-card {
            padding: 25px 19px 20px;
            border-radius: 14px;
        }

        .phishing-landing .pl-result-icon {
            width: 69px;
            height: 69px;
            border-radius: 19px;
        }

        .phishing-landing .pl-result-icon svg {
            width: 49px;
            height: 49px;
        }

        .phishing-landing .pl-result h2 {
            font-size: 23px;
        }

        .phishing-landing .pl-result > p {
            font-size: 12px;
        }

        .phishing-landing .pl-divider {
            margin: 23px 0;
        }

        .phishing-landing .pl-section-heading {
            align-items: flex-start;
            gap: 10px;
        }

        .phishing-landing .pl-section-heading h3 {
            font-size: 15px;
        }

        .phishing-landing .pl-check-item {
            gap: 11px;
            padding: 15px 0;
        }

        .phishing-landing .pl-check-number {
            width: 30px;
            height: 30px;
            flex-basis: 30px;
        }

        .phishing-landing .pl-report-panel {
            padding: 13px;
        }

        .phishing-landing .pl-primary-button {
            justify-content: flex-start;
            text-align: left;
            font-size: 11px;
        }

        .phishing-landing .pl-primary-button .pl-arrow {
            margin-left: auto;
        }

        .phishing-landing .pl-footer {
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 6px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .phishing-landing .pl-primary-button,
        .phishing-landing .pl-secondary-button {
            transition: none;
        }
    }
</style>
@endsection
