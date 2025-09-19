<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

        // Strictly exclude password-related fields from profile update
        $excludedFields = ['password', 'password_confirmation', 'current_password'];
        foreach ($excludedFields as $field) {
            unset($data[$field]);
        }

        // Debug logging for avatar upload
        if (config('app.debug')) {
            Log::info('Profile update attempt:', [
                'user_id' => $user->id,
                'has_avatar_file' => $request->hasFile('avatar'),
                'validated_data' => array_keys($data),
                'current_avatar_url' => $user->avatar_url
            ]);
        }

        if ($request->hasFile('avatar')) {
            $avatarFile = $request->file('avatar');
            
            // Debug avatar file details
            if (config('app.debug')) {
                Log::info('Avatar file details:', [
                    'original_name' => $avatarFile->getClientOriginalName(),
                    'size' => $avatarFile->getSize(),
                    'mime_type' => $avatarFile->getMimeType(),
                    'is_valid' => $avatarFile->isValid()
                ]);
            }

            // Delete old avatar if exists
            if ($user->avatar_url) {
                // Handle different avatar_url formats
                if (str_starts_with($user->avatar_url, 'avatars/')) {
                    Storage::disk('public')->delete($user->avatar_url);
                    if (config('app.debug')) {
                        Log::info('Deleted old avatar:', ['path' => $user->avatar_url]);
                    }
                } else if (!filter_var($user->avatar_url, FILTER_VALIDATE_URL)) {
                    // Local file without avatars/ prefix
                    Storage::disk('public')->delete('avatars/' . $user->avatar_url);
                    if (config('app.debug')) {
                        Log::info('Deleted old avatar:', ['path' => 'avatars/' . $user->avatar_url]);
                    }
                }
            }
            
            // Store new avatar with proper path
            $avatarPath = $avatarFile->store('avatars', 'public');
            $data['avatar_url'] = $avatarPath;
            
            if (config('app.debug')) {
                Log::info('New avatar stored:', [
                    'path' => $avatarPath,
                    'full_path' => storage_path('app/public/' . $avatarPath),
                    'file_exists' => file_exists(storage_path('app/public/' . $avatarPath))
                ]);
            }
            
            // Remove 'avatar' from data since we use 'avatar_url'
            unset($data['avatar']);
        }

        // Use selective update to avoid password field issues
        $updateData = collect($data)->except(['password', 'password_confirmation', 'current_password'])->toArray();
        
        // Handle email verification reset
        if (isset($updateData['email']) && $updateData['email'] !== $user->email) {
            $updateData['email_verified_at'] = null;
        }

        // Update only the fields we want to update
        $user->update($updateData);

        if (config('app.debug')) {
            Log::info('Profile updated successfully:', [
                'user_id' => $user->id,
                'updated_fields' => array_keys($updateData),
                'new_avatar_url' => $user->avatar_url
            ]);
        }

        // Set appropriate success message
        $message = 'profile-updated';
        if ($request->hasFile('avatar')) {
            $message = 'avatar-updated';
        }

        return Redirect::route('profile.edit')->with('status', $message);
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
