<?php

namespace App\Modules\PublicTest\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\PublicTest\Models\PublicTestUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicTestAuthController extends Controller
{
    public function showLogin()
    {
        return view('public-test.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => [
                'required',
                'email',
            ],
            'password' => [
                'required',
            ],
        ]);

        if (
            Auth::guard('public_test')->attempt(
                $credentials
            )
        ) {
            $request->session()->regenerate();

            return redirect()->route(
                'public-test.dashboard'
            );
        }

        return back()
            ->withErrors([
                'email' => 'The email or password is incorrect.',
            ])
            ->withInput(
                $request->only('email')
            );
    }

    public function showRegister()
    {
        return view('public-test.auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'username' => [
                'required',
                'string',
                'max:255',
                'unique:public_test_users,username',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:public_test_users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        $user = PublicTestUser::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        Auth::guard('public_test')->login($user);

        $request->session()->regenerate();

        return redirect()->route(
            'public-test.dashboard'
        );
    }

    public function logout(Request $request)
    {
        Auth::guard('public_test')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route(
            'public-test.login'
        );
    }
}
