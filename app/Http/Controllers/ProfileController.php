<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(
        Request $request
    ): View {
        return view(
            'profile.edit',
            [
                'user' => $request->user(),
            ]
        );
    }

    public function update(
        Request $request
    ): RedirectResponse {
        $user = $request->user();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')
                    ->ignore($user->id),
            ],
        ]);

        $emailChanged =
            $validated['email'] !== $user->email;

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($emailChanged) {
            $user->email_verified_at = null;
        }

        $user->save();

        return back()->with(
            'success',
            'Profile updated successfully.'
        );
    }

    public function updatePassword(
        Request $request
    ): RedirectResponse {
        $request->validate([
            'current_password' => [
                'required',
                'current_password',
            ],

            'password' => [
                'required',
                'confirmed',
                Password::defaults(),
            ],
        ]);

        $request->user()->update([
            'password' => Hash::make(
                $request->password
            ),
        ]);

        return back()->with(
            'success',
            'Password updated successfully.'
        );
    }
}