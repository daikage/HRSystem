<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ForcedPasswordController extends Controller
{
    /**
     * Show the form for setting a first password.
     */
    public function edit(Request $request): View
    {
        return view('auth.forced-password', ['user' => $request->user()]);
    }

    /**
     * Set a new password and clear the temporary-password flag.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
            'must_change_password' => false,
        ]);

        return redirect()->route('dashboard')->with('status', 'password-updated');
    }
}