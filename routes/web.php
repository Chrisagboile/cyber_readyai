<?php
use App\Http\Controllers\OrganisationTrainingController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LearningPlanController;
use App\Http\Controllers\ManagerAssessmentController;
use App\Http\Controllers\ManagerDepartmentReportsController;
use App\Http\Controllers\ManagerRiskDashboardController;
use App\Http\Controllers\ManagerTeamReadinessController;
use App\Http\Controllers\OrganisationAdminDashboardController;
use App\Http\Controllers\OrganisationAssessmentController;
use App\Http\Controllers\OrganisationReportController;
use App\Http\Controllers\OrganisationUserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
// use  Illuminate\Support\Facades\Auth;
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
                redirect()->route('organisation.dashboard'),

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

        Route::get(
            '/dashboard',
            [OrganisationAdminDashboardController::class, 'index']
        )->name('organisation.dashboard');

        Route::patch(
            '/users/{user}/toggle-status',
            [OrganisationUserController::class, 'toggleStatus']
        )->name('organisation.users.toggle-status');

        Route::get(
            '/users',
            [OrganisationUserController::class, 'index']
        )->name('organisation.users');

        Route::get(
            '/users/create',
            [OrganisationUserController::class, 'create']
        )->name('organisation.users.create');

        Route::post(
            '/users',
            [OrganisationUserController::class, 'store']
        )->name('organisation.users.store');

        Route::get(
            '/users/{user}/edit',
            [OrganisationUserController::class, 'edit']
        )->name('organisation.users.edit');

        Route::put(
            '/users/{user}',
            [OrganisationUserController::class, 'update']
        )->name('organisation.users.update');

        Route::delete(
            '/users/{user}',
            [OrganisationUserController::class, 'destroy']
        )->name('organisation.users.destroy');

        Route::get(
            '/assessments',
            [OrganisationAssessmentController::class, 'index']
        )->name('organisation.assessments');

        Route::get(
            '/assessments/create',
            [OrganisationAssessmentController::class, 'create']
        )->name('organisation.assessments.create');

        Route::post(
            '/assessments',
            [OrganisationAssessmentController::class, 'generate']
        )->name('organisation.assessments.generate');

/*
        Route::get('/assessments', function () {
            return view('assessments.index');
        })->name('organisation.assessments');

        Route::get('/reports', function () {
            return view('reports.index');
        })->name('organisation.reports');
*/
        Route::get(
            '/reports',
            [OrganisationReportController::class, 'index']
        )->name('organisation.reports');

        Route::get(
            '/training',
            [OrganisationTrainingController::class, 'index']
        )->name('organisation.training');

  /*      Route::get('/training', function () {
            return view('training.index');
        })->name('organisation.training');
*/
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
/*
        Route::get('/team-readiness', function () {
            return view('manager.team-readiness');
        })->name('manager.team-readiness');

        Route::get('/department-reports', function () {
            return view('manager.department-reports');
        })->name('manager.department-reports');
*/
        Route::get(
            '/department-reports',
            [ManagerDepartmentReportsController::class, 'index']
        )->name('manager.department-reports');

/*
        Route::get('/risk-dashboard', function () {
            return view('manager.risk-dashboard');
        })->name('manager.risk-dashboard');*/

        Route::get(
            '/risk-dashboard',
            [ManagerRiskDashboardController::class, 'index']
        )->name('manager.risk-dashboard');

        Route::get('/team-readiness', [ManagerTeamReadinessController::class, 'index'])
            ->name('manager.team-readiness');

        Route::get('/learning-plans/{employee}', [LearningPlanController::class, 'managerIndex'])
            ->name('manager.learning-plans');
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
            return redirect()->route('assessment.index');
        })->name('employee.assessments');

        /*
        Route::get('/assessments', function () {
            return view('employee.assessments');
        })->name('employee.assessments');

       Route::get('/score', function () {
            return view('employee.score');
        })->name('employee.score');
*/
        Route::get('/score', [EmployeeController::class, 'score'])
         ->name('employee.score');
/*
         Route::get('/learning-plans', function () {
            return view('employee.learning-plans');
        })->name('employee.learning-plans');
*/


	Route::get('/learning-plans', [LearningPlanController::class, 'index'])
	    ->name('employee.learning-plans');
    });

    Route::patch('/employee/learning-plans/{learningPlan}/status', [LearningPlanController::class, 'updateStatus'])
        ->name('employee.learning-plans.status');
//Route::middleware(['auth', 'verified'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Assessment
    |--------------------------------------------------------------------------
    */
    /*
    Route::middleware([
        'auth',
        'verified',
        'role:employee',
    ])->group(function () {
        // assessment routes
    Route::get(
        '/assessments',
        [AssessmentController::class, 'index']
    )->name('assessment.index');
    Route::get(
        '/assessment/{attempt}/question/{question}/previous',
        [AssessmentController::class, 'previous']
    )->name('assessment.previous');

    Route::get(
        '/assessment/{assessment}/start',
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

  //  });
 });*/

 // Employee assessment routes
    Route::middleware([
        'auth',
        'verified',
        'role:employee',
    ])->group(function () {

        Route::get(
            '/assessments',
            [AssessmentController::class, 'index']
        )->name('assessment.index');

        Route::get(
            '/assessment/{assessment}/start',
            [AssessmentController::class, 'start']
        )->name('assessment.start');

        Route::get(
            '/assessment/{attempt}/question/{question}/previous',
            [AssessmentController::class, 'previous']
        )->name('assessment.previous');

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
    });


    // Assessment result routes
    Route::middleware([
        'auth',
        'verified',
    ])->group(function () {

        Route::get(
            '/assessment/{attempt}/result',
            [AssessmentController::class, 'result']
        )->name('assessment.result');
    });
 /*
 /==================================================
 /  Password: reset and management
 /
 /==================================================
 */

Route::middleware('guest')->group(function () {

    Route::get(
        '/forgot-password',
        [PasswordResetLinkController::class, 'create']
    )->name('password.request');

    Route::post(
        '/forgot-password',
        [PasswordResetLinkController::class, 'store']
    )->name('password.email');

    Route::get(
        '/reset-password/{token}',
        [NewPasswordController::class, 'create']
    )->name('password.reset');

    Route::post(
        '/reset-password',
        [NewPasswordController::class, 'store']
    )->name('password.update');
});

/*
/========================================================
/
/  Manager: assessment question generation
/=========================================================
*/
    Route::middleware([
        'auth',
        'verified',
        'role:manager'
    ])->prefix('manager')->name('manager.')->group(function () {
/*
    Route::get(
        '/manager/assessments',
        [ManagerAssessmentController::class, 'index']
    )->name('manager.assessments.index');

    Route::get(
        '/manager/assessments/create',
        [ManagerAssessmentController::class, 'create']
    )->name('manager.assessments.create');

    Route::post(
        '/manager/assessments',
        [ManagerAssessmentController::class, 'generate']
    )->name('manager.assessments.generate');
*/
        Route::get(
            '/assessments',
            [ManagerAssessmentController::class, 'index']
        )->name('assessments.index');

        Route::get(
            '/assessments/create',
            [ManagerAssessmentController::class, 'create']
        )->name('assessments.create');

        Route::post(
            '/assessments',
            [ManagerAssessmentController::class, 'generate']
        )->name('assessments.generate');

    });

/*
/=============================================================
/
/ Profile management
/==============================================================
*/

Route::middleware([
    'auth',
    'verified',
])->group(function () {

    Route::get(
        '/profile',
        [ProfileController::class, 'edit']
    )->name('profile.edit');

    Route::put(
        '/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');

    Route::put(
        '/profile/password',
        [ProfileController::class, 'updatePassword']
    )->name('profile.password.update');

    Route::resource('departments', DepartmentController::class);

});
