<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
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
        $user = $request->user();
        $data = $request->validated();

        // Ensure password is never updated through profile update
        unset($data['password'], $data['password_confirmation'], $data['current_password']);

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar_url) {
                // Handle different avatar_url formats
                if (str_starts_with($user->avatar_url, 'avatars/')) {
                    Storage::disk('public')->delete($user->avatar_url);
                } else if (!filter_var($user->avatar_url, FILTER_VALIDATE_URL)) {
                    // Local file without avatars/ prefix
                    Storage::disk('public')->delete('avatars/' . $user->avatar_url);
                }
            }
            
            // Store new avatar with proper path
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar_url'] = $avatarPath;
            
            // Remove 'avatar' from data since we use 'avatar_url'
            unset($data['avatar']);
        }

        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
