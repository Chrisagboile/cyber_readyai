<?php
use App\Modules\PublicTest\Controllers\PublicTestAuthController;
use App\Modules\PublicTest\Controllers\PublicTestController;
use App\Modules\PublicTest\Controllers\PublicTestDashboardController;
use App\Modules\PublicTest\Middleware\PublicTestAuthenticated;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| PUBLIC TEST
|--------------------------------------------------------------------------
*/

Route::prefix('public-test')
    ->name('public-test.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Authentication
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/login',
            [
                PublicTestAuthController::class,
                'showLogin',
            ]
        )->name('login');

        Route::post(
            '/login',
            [
                PublicTestAuthController::class,
                'login',
            ]
        )->name('login.submit');

        Route::get(
            '/register',
            [
                PublicTestAuthController::class,
                'showRegister',
            ]
        )->name('register');

        Route::post(
            '/register',
            [
                PublicTestAuthController::class,
                'register',
            ]
        )->name('register.submit');

        Route::post(
            '/logout',
            [
                PublicTestAuthController::class,
                'logout',
            ]
        )->name('logout');


        /*
        |--------------------------------------------------------------------------
        | Authenticated public-test area
        |--------------------------------------------------------------------------
        */

        Route::middleware(
            PublicTestAuthenticated::class
        )->group(function () {

            /*
            |--------------------------------------------------------------------------
            | Dashboard
            |--------------------------------------------------------------------------
            */
            Route::get(
                '/dashboard',
                [
                    PublicTestDashboardController::class,
                    'index',
                ]
            )->name('dashboard');

            /*
            |--------------------------------------------------------------------------
            | Assessment history
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/assessments',
                [
                    PublicTestDashboardController::class,
                    'assessments',
                ]
            )->name('assessments');


            /*
            |--------------------------------------------------------------------------
            | Learning
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/learning',
                [
                    PublicTestDashboardController::class,
                    'learning',
                ]
            )->name('learning');


            /*
            |--------------------------------------------------------------------------
            | Start test
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/start',
                [
                    PublicTestController::class,
                    'start',
                ]
            )->name('start');


            /*
            |--------------------------------------------------------------------------
            | Question
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/{attempt}/question/{question}',
                [
                    PublicTestController::class,
                    'question',
                ]
            )->whereNumber('attempt')
             ->whereNumber('question')
             ->name('question');


            /*
            |--------------------------------------------------------------------------
            | Save answer
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/{attempt}/question/{question}',
                [
                    PublicTestController::class,
                    'answer',
                ]
            )->whereNumber('attempt')
             ->whereNumber('question')
             ->name('answer');


            /*
            |--------------------------------------------------------------------------
            | Review
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/{attempt}/review',
                [
                    PublicTestController::class,
                    'review',
                ]
            )->whereNumber('attempt')
             ->name('review');


            /*
            |--------------------------------------------------------------------------
            | Submit
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/{attempt}/submit',
                [
                    PublicTestController::class,
                    'submit',
                ]
            )->whereNumber('attempt')
             ->name('submit');


            /*
            |--------------------------------------------------------------------------
            | Result
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/{attempt}/result',
                [
                    PublicTestController::class,
                    'result',
                ]
            )->whereNumber('attempt')
             ->name('result');


            /*
            |--------------------------------------------------------------------------
            | Feedback
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/{attempt}/feedback',
                [
                    PublicTestController::class,
                    'feedback',
                ]
            )->whereNumber('attempt')
             ->name('feedback');


            Route::post(
                '/{attempt}/feedback',
                [
                    PublicTestController::class,
                    'storeFeedback',
                ]
            )->whereNumber('attempt')
             ->name('feedback.store');
        });
    });
