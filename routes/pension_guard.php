<?php

use App\Modules\PensionAccountGuard\Controllers\PensionGuardTrustedContactController;
use App\Modules\PensionAccountGuard\Controllers\PensionGuardNotificationController;
use App\Modules\PensionAccountGuard\Controllers\PensionGuardAccountController;
use App\Modules\PensionAccountGuard\Controllers\PensionGuardAdminController;
use App\Modules\PensionAccountGuard\Controllers\PensionGuardAssessmentController;
use App\Modules\PensionAccountGuard\Controllers\PensionGuardAuditController;
use App\Modules\PensionAccountGuard\Controllers\PensionGuardAuthController;
use App\Modules\PensionAccountGuard\Controllers\PensionGuardChecklistController;
use App\Modules\PensionAccountGuard\Controllers\PensionGuardSecurityIncidentController;
use App\Modules\PensionAccountGuard\Controllers\PensionGuardSubscriptionController;
use App\Modules\PensionAccountGuard\Controllers\PensionGuardSourceAdminController;
use App\Modules\PensionAccountGuard\Controllers\PensionGuardSourceController;
use App\Modules\PensionAccountGuard\Controllers\PensionGuardThreatController;
use Illuminate\Support\Facades\Route;

Route::prefix('pension-guard')
    ->name('pension-guard.')
    ->group(function () {


        /*
        |--------------------------------------------------------------------------
        | Stripe webhook
        |--------------------------------------------------------------------------
        | Stripe authenticates this endpoint using its webhook signature.
        | It must not require Pension Guard user login.
        */

        Route::post('/stripe/webhook', [
            \App\Modules\PensionAccountGuard\Controllers\PensionGuardStripeWebhookController::class,
            'handle',
        ])->name('stripe.webhook');



        /*
        |--------------------------------------------------------------------------
        | Public pages
        |--------------------------------------------------------------------------
        */

        Route::get('/', fn () => view('pension-guard.dashboard'))
            ->name('dashboard');

        Route::get('/threat-checker', [
            PensionGuardThreatController::class,
            'create',
        ])->name('threat-checker.create');

        Route::post('/threat-checker', [
            PensionGuardThreatController::class,
            'store',
        ])->middleware('throttle:10,1')
          ->name('threat-checker.store');

        Route::get('/sources', [
            PensionGuardSourceController::class,
            'index',
        ])->name('sources.index');

        /*
        |--------------------------------------------------------------------------
        | Public trusted-contact email verification
        |--------------------------------------------------------------------------
        | Recipients must be able to verify their email without logging in.
        */

        Route::get(
            '/trusted-contacts/{contact}/verify/{token}',
            [PensionGuardTrustedContactController::class, 'verify']
        )
            ->whereNumber('contact')
            ->name('trusted-contacts.verify');

        /*
        |--------------------------------------------------------------------------
        | Pension Guard authentication
        |--------------------------------------------------------------------------
        */

        Route::get('/login', [
            PensionGuardAuthController::class,
            'showLogin',
        ])->name('login');

        Route::post('/login', [
            PensionGuardAuthController::class,
            'login',
        ])->middleware(['guest:pension_guard', 'throttle:10,1'])
          ->name('login.submit');

        Route::get('/register', [
            PensionGuardAuthController::class,
            'showRegister',
        ])->middleware('guest:pension_guard')
          ->name('register');

        Route::post('/register', [
            PensionGuardAuthController::class,
            'register',
        ])->middleware(['guest:pension_guard', 'throttle:5,1'])
          ->name('register.submit');

        /*
        |--------------------------------------------------------------------------
        | Public assessments
        |--------------------------------------------------------------------------
        */

        Route::get('/assessments/create', [
            PensionGuardAssessmentController::class,
            'create',
        ])->name('assessments.create');

        Route::post('/assessments', [
            PensionGuardAssessmentController::class,
            'store',
        ])->middleware('throttle:10,1')
          ->name('assessments.store');

        Route::get('/assessments/result', [
            PensionGuardAssessmentController::class,
            'result',
        ])->name('assessments.result');

        /*
        |--------------------------------------------------------------------------
        | Administrator dashboard
        |--------------------------------------------------------------------------
        */

        Route::get('/admin', [
            PensionGuardAdminController::class,
            'index',
        ])->middleware(['auth:pension_guard', 'pension_guard.admin'])
          ->name('admin.index');

        /*
        |--------------------------------------------------------------------------
        | Administrator audit log
        |--------------------------------------------------------------------------
        */

        Route::get('/admin/audit', [
            PensionGuardAuditController::class,
            'index',
        ])->middleware(['auth:pension_guard', 'pension_guard.admin'])
          ->name('admin.audit.index');

        /*
        |--------------------------------------------------------------------------
        | Administrator official-source management
        |--------------------------------------------------------------------------
        */

        Route::prefix('/admin/sources')
            ->name('admin.sources.')
            ->middleware(['auth:pension_guard', 'pension_guard.admin'])
            ->controller(PensionGuardSourceAdminController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{source}/edit', 'edit')->name('edit');
                Route::put('/{source}', 'update')->name('update');
            });

        /*
        |--------------------------------------------------------------------------
        | Administrator security incident register
        |--------------------------------------------------------------------------
        */

        Route::prefix('/admin/incidents')
            ->name('admin.incidents.')
            ->middleware(['auth:pension_guard', 'pension_guard.admin'])
            ->controller(PensionGuardSecurityIncidentController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');

                Route::post('/', 'store')
                    ->middleware('throttle:10,1')
                    ->name('store');

                Route::get('/{incident}/edit', 'edit')
                    ->whereNumber('incident')
                    ->name('edit');

                Route::put('/{incident}', 'update')
                    ->whereNumber('incident')
                    ->middleware('throttle:10,1')
                    ->name('update');
            });

        /*
        |--------------------------------------------------------------------------
        | Authenticated user pages
        |--------------------------------------------------------------------------
        */

        Route::middleware('auth:pension_guard')->group(function () {

            Route::post('/logout', [
                PensionGuardAuthController::class,
                'logout',
            ])->name('logout');

            /*
            |------------------------------------------------------------------
            | Pension accounts
            |------------------------------------------------------------------
            */

            Route::get('/accounts', [
                PensionGuardAccountController::class,
                'index',
            ])->name('accounts.index');

            Route::get('/accounts/create', [
                PensionGuardAccountController::class,
                'create',
            ])->name('accounts.create');

            Route::post('/accounts', [
                PensionGuardAccountController::class,
                'store',
            ])->name('accounts.store');

            Route::get('/accounts/{account}', [
                PensionGuardAccountController::class,
                'show',
            ])->whereNumber('account')
              ->name('accounts.show');

            /*
            |------------------------------------------------------------------
            | Security checklist
            |------------------------------------------------------------------
            */

            Route::get('/accounts/{account}/checklist', [
                PensionGuardChecklistController::class,
                'index',
            ])->whereNumber('account')
              ->name('checklist.index');

            Route::patch('/accounts/{account}/checklist/{check}', [
                PensionGuardChecklistController::class,
                'update',
            ])->whereNumber('account')
              ->whereNumber('check')
              ->name('checklist.update');

            /*
            |------------------------------------------------------------------
            | Saved assessments
            |------------------------------------------------------------------
            */

            Route::get('/assessments', [
                PensionGuardAssessmentController::class,
                'history',
            ])->name('assessments.history');

            Route::get('/assessments/{assessment}', [
                PensionGuardAssessmentController::class,
                'show',
            ])->whereNumber('assessment')
              ->name('assessments.show');

            /*
            |------------------------------------------------------------------
            | Notifications
            |------------------------------------------------------------------
            */

            Route::get('/notifications', [
                PensionGuardNotificationController::class,
                'index',
            ])->name('notifications.index');

            Route::patch('/notifications/{notification}/read', [
                PensionGuardNotificationController::class,
                'markAsRead',
            ])->name('notifications.read');

            /*
            |------------------------------------------------------------------
            | Trusted contacts
            |------------------------------------------------------------------
            */

            Route::get('/trusted-contacts', [
                PensionGuardTrustedContactController::class,
                'index',
            ])->name('trusted-contacts.index');

            Route::get('/trusted-contacts/create', [
                PensionGuardTrustedContactController::class,
                'create',
            ])->name('trusted-contacts.create');

            Route::post('/trusted-contacts', [
                PensionGuardTrustedContactController::class,
                'store',
            ])->name('trusted-contacts.store');

            Route::delete('/trusted-contacts/{contact}', [
                PensionGuardTrustedContactController::class,
                'destroy',
            ])->whereNumber('contact')
              ->name('trusted-contacts.destroy');

            Route::post(
                '/trusted-contacts/{contact}/resend-verification',
                [PensionGuardTrustedContactController::class, 'resendVerification']
            )
                ->whereNumber('contact')
                ->middleware('throttle:5,1')
                ->name('trusted-contacts.resend-verification');

            Route::put('/trusted-contacts/{contact}/consent', [
                PensionGuardTrustedContactController::class,
                'updateConsent',
            ])->whereNumber('contact')
              ->name('trusted-contacts.consent.update');

            Route::delete('/trusted-contacts/{contact}/consent', [
                PensionGuardTrustedContactController::class,
                'revokeConsent',
            ])->whereNumber('contact')
              ->name('trusted-contacts.consent.revoke');
        /*
        |--------------------------------------------------------------------------
        | Pension Guard subscriptions
        |--------------------------------------------------------------------------
        */

        Route::get('/subscriptions', [
            PensionGuardSubscriptionController::class,
            'index',
        ])->name('subscriptions.index');

        Route::post('/subscriptions/checkout', [
            PensionGuardSubscriptionController::class,
            'checkout',
        ])->middleware('throttle:5,1')
        ->name('subscriptions.checkout');

        Route::get('/subscriptions/success', [
            PensionGuardSubscriptionController::class,
            'success',
        ])->name('subscriptions.success');

        Route::get('/subscriptions/cancelled', [
            PensionGuardSubscriptionController::class,
            'cancelled',
        ])->name('subscriptions.cancelled');

        Route::post('/subscriptions/billing-portal', [
            PensionGuardSubscriptionController::class,
            'billingPortal',
        ])->middleware('throttle:5,1')
        ->name('subscriptions.billing-portal');

    Route::post(
        '/subscriptions/{subscription}/cancel',
        [PensionGuardSubscriptionController::class, 'cancel']
    )->whereNumber('subscription')
     ->middleware('throttle:5,1')
     ->name('subscriptions.cancel');


        });
    });
