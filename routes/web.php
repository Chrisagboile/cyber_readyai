<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AssessmentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.store');

Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('register');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register.store');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated Dashboard
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {

        $role = auth()->user()->role?->slug;

        return match ($role) {

            'super-admin' =>
                view('dashboard.super-admin'),

            'organisation-admin' =>
                view('dashboard.organisation-admin'),

            'manager' =>
                view('dashboard.manager'),

            'employee' =>
                view('dashboard.employee'),

            default =>
                abort(403, 'No valid role assigned to this account.'),
        };

    })->name('dashboard');

});


/*
|--------------------------------------------------------------------------
| Super Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'role:super-admin'])
    ->prefix('admin')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('dashboard.super-admin');
        })->name('admin.dashboard');

        Route::get('/tenants', function () {
            return view('tenants.index');
        })->name('tenants.index');

        Route::get('/subscriptions', function () {
            return view('subscriptions.index');
        })->name('subscriptions.index');

        Route::get('/analytics', function () {
            return view('analytics.index');
        })->name('analytics.index');

        Route::get('/billing', function () {
            return view('billing.index');
        })->name('billing.index');

    });


/*
|--------------------------------------------------------------------------
| Organisation Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'role:organisation-admin'])
    ->prefix('organisation')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('dashboard.organisation-admin');
        })->name('organisation.dashboard');

        Route::get('/users', function () {
            return view('users.index');
        })->name('organisation.users');

        Route::get('/assessments', function () {
            return view('assessments.index');
        })->name('organisation.assessments');

        Route::get('/reports', function () {
            return view('reports.index');
        })->name('organisation.reports');

        Route::get('/training', function () {
            return view('training.index');
        })->name('organisation.training');

    });


/*
|--------------------------------------------------------------------------
| Manager Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'role:manager'])
    ->prefix('manager')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('dashboard.manager');
        })->name('manager.dashboard');

        Route::get('/team-readiness', function () {
            return view('manager.team-readiness');
        })->name('manager.team-readiness');

        Route::get('/department-reports', function () {
            return view('manager.department-reports');
        })->name('manager.department-reports');

        Route::get('/risk-dashboard', function () {
            return view('manager.risk-dashboard');
        })->name('manager.risk-dashboard');

    });


/*
|--------------------------------------------------------------------------
| Employee Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'role:employee'])
    ->prefix('employee')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('dashboard.employee');
        })->name('employee.dashboard');

        Route::get('/assessments', function () {
            return view('employee.assessments');
        })->name('employee.assessments');

        Route::get('/score', function () {
            return view('employee.score');
        })->name('employee.score');

        Route::get('/learning-plans', function () {
            return view('employee.learning-plans');
        })->name('employee.learning-plans');

    });
Route::middleware(['auth', 'verified'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Assessment
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/assessment/start',
        [AssessmentController::class, 'start']
    )->name('assessment.start');

    Route::get(
        '/assessment/{attempt}/question/{question}',
        [AssessmentController::class, 'question']
    )->name('assessment.question');

    Route::post(
        '/assessment/{attempt}/question/{question}',
        [AssessmentController::class, 'answer']
    )->name('assessment.answer');

    Route::get(
        '/assessment/{attempt}/review',
        [AssessmentController::class, 'review']
    )->name('assessment.review');

    Route::post(
        '/assessment/{attempt}/submit',
        [AssessmentController::class, 'submit']
    )->name('assessment.submit');

    Route::get(
        '/assessment/{attempt}/result',
        [AssessmentController::class, 'result']
    )->name('assessment.result');

});
