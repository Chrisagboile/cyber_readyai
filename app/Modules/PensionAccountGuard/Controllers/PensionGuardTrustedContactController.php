<?php

namespace App\Modules\PensionAccountGuard\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\PensionAccountGuard\Models\PensionGuardTrustedContact;
use App\Notifications\PensionGuardTrustedContactVerification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PensionGuardTrustedContactController extends Controller
{
    /**
     * Alert categories that a trusted contact may be authorised to receive.
     * These categories must also be enforced by the alert-delivery service.
     */
    private const ALERT_CATEGORIES = [
        'security_reminders' => 'Security reminders',
        'breach_alerts' => 'Relevant data-breach alerts',
        'provider_incidents' => 'Pension-provider cyber incident alerts',
        'fraud_warnings' => 'Relevant pension fraud warnings',
    ];

    /**
     * Display the signed-in user's trusted contacts.
     */
    public function index(Request $request): View
    {
        $user = $request->user('pension_guard');

        $contacts = PensionGuardTrustedContact::query()
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('pension-guard.trusted-contacts.index', [
            'contacts' => $contacts,
            'alertCategories' => self::ALERT_CATEGORIES,
        ]);
    }

    /**
     * Show the form for adding a trusted contact.
     */
    public function create(): View
    {
        return view('pension-guard.trusted-contacts.create');
    }

    /**
     * Add a contact and request email verification.
     * Adding a contact never grants alert-sharing consent.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user('pension_guard');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'relationship' => ['nullable', 'string', 'max:80'],
        ]);

        $email = mb_strtolower(trim($validated['email']));

        $exists = PensionGuardTrustedContact::query()
            ->where('user_id', $user->id)
            ->where('email', $email)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'email' => 'This email is already in your trusted contacts.',
                ]);
        }

        $contact = PensionGuardTrustedContact::create([
            'user_id' => $user->id,
            'name' => trim($validated['name']),
            'email' => $email,
            'relationship' => isset($validated['relationship'])
                ? trim($validated['relationship'])
                : null,
            'verification_token_hash' => null,
            'verification_expires_at' => null,
            'email_verified_at' => null,
            'consent_given' => false,
            'alert_categories' => [],
            'consent_given_at' => null,
            'consent_revoked_at' => null,
        ]);

        try {
            $this->sendVerification($contact);
        } catch (\Throwable $exception) {
            report($exception);

            return redirect()
                ->route('pension-guard.trusted-contacts.index')
                ->with(
                    'warning',
                    'The contact was saved, but the verification email could not be sent. Please try again later.'
                );
        }

        return redirect()
            ->route('pension-guard.trusted-contacts.index')
            ->with(
                'success',
                'Trusted contact added. Alert sharing remains disabled until email verification and explicit consent are completed.'
            );
    }

    /**
     * Grant or update consent for selected alert categories.
     */
    public function updateConsent(
        Request $request,
        int $contact
    ): RedirectResponse {
        $user = $request->user('pension_guard');

        $trustedContact = PensionGuardTrustedContact::query()
            ->where('user_id', $user->id)
            ->findOrFail($contact);

        if (!$trustedContact->isVerified()) {
            return back()->with(
                'warning',
                'This contact must verify their email before alert-sharing consent can be granted.'
            );
        }

        $validated = $request->validate([
            'alert_categories' => ['required', 'array', 'min:1'],
            'alert_categories.*' => [
                'required',
                'string',
                'distinct',
                'in:' . implode(',', array_keys(self::ALERT_CATEGORIES)),
            ],
            'confirm_consent' => ['accepted'],
        ]);

        DB::transaction(function () use ($trustedContact, $validated) {
            $contact = PensionGuardTrustedContact::query()
                ->whereKey($trustedContact->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Recheck verification inside the transaction.
            if (!$contact->isVerified()) {
                abort(403, 'Email verification is required.');
            }

            $contact->forceFill([
                'consent_given' => true,
                'alert_categories' => array_values(
                    array_unique($validated['alert_categories'])
                ),
                'consent_given_at' => now(),
                'consent_revoked_at' => null,
            ])->save();
        });

        return redirect()
            ->route('pension-guard.trusted-contacts.index')
            ->with(
                'success',
                'Consent saved. Only the selected alert categories are authorised for this verified contact.'
            );
    }

    /**
     * Revoke consent immediately.
     */
    public function revokeConsent(
        Request $request,
        int $contact
    ): RedirectResponse {
        $user = $request->user('pension_guard');

        $trustedContact = PensionGuardTrustedContact::query()
            ->where('user_id', $user->id)
            ->findOrFail($contact);

        DB::transaction(function () use ($trustedContact) {
            $contact = PensionGuardTrustedContact::query()
                ->whereKey($trustedContact->id)
                ->lockForUpdate()
                ->firstOrFail();

            $contact->forceFill([
                'consent_given' => false,
                'alert_categories' => [],
                'consent_revoked_at' => now(),
            ])->save();
        });

        return redirect()
            ->route('pension-guard.trusted-contacts.index')
            ->with(
                'success',
                'Consent revoked. This contact is no longer authorised to receive future alerts.'
            );
    }

    /**
     * Send or resend verification to a contact owned by this user.
     */
    public function resendVerification(
        Request $request,
        int $contact
    ): RedirectResponse {
        $user = $request->user('pension_guard');

        $trustedContact = PensionGuardTrustedContact::query()
            ->where('user_id', $user->id)
            ->findOrFail($contact);

        if ($trustedContact->email_verified_at !== null) {
            return back()->with(
                'success',
                'This email address has already been verified.'
            );
        }

        try {
            $this->sendVerification($trustedContact);
        } catch (\Throwable $exception) {
            report($exception);

            return back()->with(
                'warning',
                'The verification email could not be sent. Please try again later.'
            );
        }

        return back()->with(
            'success',
            'A new verification email has been requested. Any previous link is now invalid.'
        );
    }

    /**
     * Verify an email using a one-time token.
     * This route must remain outside auth:pension_guard.
     */
    public function verify(
        Request $request,
        int $contact,
        string $token
    ): View {
        $trustedContact = PensionGuardTrustedContact::query()
            ->findOrFail($contact);

        $storedHash = $trustedContact->verification_token_hash;
        $expiresAt = $trustedContact->verification_expires_at;

        $valid = is_string($storedHash)
            && $storedHash !== ''
            && $expiresAt !== null
            && $expiresAt->isFuture()
            && hash_equals($storedHash, hash('sha256', $token));

        if (!$valid) {
            abort(
                403,
                'This verification link is invalid or has expired. Ask the account owner to send a new one.'
            );
        }

        $updated = PensionGuardTrustedContact::query()
            ->whereKey($trustedContact->id)
            ->where('verification_token_hash', $storedHash)
            ->where('verification_expires_at', '>', now())
            ->whereNull('email_verified_at')
            ->update([
                'email_verified_at' => now(),
                'verification_token_hash' => null,
                'verification_expires_at' => null,
                'updated_at' => now(),
            ]);

        if ($updated !== 1) {
            abort(
                403,
                'This verification link has already been used or has expired.'
            );
        }

        return view('pension-guard.trusted-contacts.verified');
    }

    /**
     * Remove a contact belonging to the signed-in user.
     */
    public function destroy(
        Request $request,
        int $contact
    ): RedirectResponse {
        $user = $request->user('pension_guard');

        $trustedContact = PensionGuardTrustedContact::query()
            ->where('user_id', $user->id)
            ->findOrFail($contact);

        $trustedContact->delete();

        return redirect()
            ->route('pension-guard.trusted-contacts.index')
            ->with('success', 'Trusted contact removed.');
    }

    /**
     * Generate a fresh token, store only its hash, and send verification.
     */
    private function sendVerification(
        PensionGuardTrustedContact $contact
    ): void {
        $token = Str::random(64);

        $contact->forceFill([
            'verification_token_hash' => hash('sha256', $token),
            'verification_expires_at' => now()->addMinutes(60),
        ])->save();

        $verificationUrl = URL::route(
            'pension-guard.trusted-contacts.verify',
            [
                'contact' => $contact->id,
                'token' => $token,
            ]
        );

        Notification::route('mail', $contact->email)
            ->notify(
                new PensionGuardTrustedContactVerification(
                    $verificationUrl
                )
            );
    }
}
