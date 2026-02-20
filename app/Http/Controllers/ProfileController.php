<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Generate or regenerate the user's API key.
     */
    public function generateApiKey(Request $request): RedirectResponse
    {
        $user = $request->user();
        $key = $user->generateApiKey();
        ActivityLog::log('api_key_generated', 'API key regenerated', $user->id);

        return back()->with('status', 'api-key-generated')
            ->with('new_api_key', $key);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // For Google OAuth users (no password set), skip password validation
        $user = $request->user();

        if ($user->password !== null) {
            $request->validateWithBag('userDeletion', [
                'password' => ['required', 'current_password'],
            ]);
        } else {
            // Google users must confirm by typing their email
            $request->validateWithBag('userDeletion', [
                'confirm_email' => ['required', 'in:' . $user->email],
            ]);
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
