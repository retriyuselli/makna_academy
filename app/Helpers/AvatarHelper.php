<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

if (!function_exists('user_avatar')) {
    /**
     * Get user avatar URL with fallback
     *
     * @param \App\Models\User|null $user
     * @param int $size
     * @return string
     */
    function user_avatar($user = null, $size = 150)
    {
        if (!$user) {
            $user = Auth::user();
        }

        if (!$user) {
            return default_avatar($size);
        }

        // Jika ada avatar dari Google (URL)
        if ($user->avatar_url && filter_var($user->avatar_url, FILTER_VALIDATE_URL)) {
            // Jika URL Google, ubah size parameter
            if (strpos($user->avatar_url, 'googleusercontent.com') !== false) {
                return preg_replace('/=s\d+-c$/', "=s{$size}-c", $user->avatar_url);
            }
            return $user->avatar_url;
        }

        // Jika ada avatar lokal (path atau storage)
        if ($user->avatar_url && !filter_var($user->avatar_url, FILTER_VALIDATE_URL)) {
            // Cek apakah sudah ada prefix avatars/
            if (str_starts_with($user->avatar_url, 'avatars/')) {
                $filePath = storage_path('app/public/' . $user->avatar_url);
                if (file_exists($filePath)) {
                    return asset('storage/' . $user->avatar_url);
                }
                // Log for debugging in production
                if (config('app.debug')) {
                    Log::warning("Avatar file not found: {$filePath}");
                }
            } else {
                // Cek langsung di root storage/app/public/
                $filePathRoot = storage_path('app/public/' . $user->avatar_url);
                if (file_exists($filePathRoot)) {
                    return asset('storage/' . $user->avatar_url);
                }
                
                // Cek dengan path avatars/
                $filePathAvatars = storage_path('app/public/avatars/' . $user->avatar_url);
                if (file_exists($filePathAvatars)) {
                    return asset('storage/avatars/' . $user->avatar_url);
                }
                
                // Log both attempts for debugging
                if (config('app.debug')) {
                    Log::warning("Avatar file not found in either location: {$filePathRoot} or {$filePathAvatars}");
                }
            }
        }

        // Fallback ke default avatar berdasarkan nama
        return default_avatar($size, $user->name);
    }
}

if (!function_exists('default_avatar')) {
    /**
     * Generate default avatar
     *
     * @param int $size
     * @param string|null $name
     * @return string
     */
    function default_avatar($size = 150, $name = null)
    {
        if ($name) {
            // Generate avatar dengan initials menggunakan UI Avatars
            $initials = get_initials($name);
            $background = substr(md5($name), 0, 6); // Random color based on name
            return "https://ui-avatars.com/api/?name=" . urlencode($initials) . "&size={$size}&background={$background}&color=fff&bold=true";
        }

        // Default avatar
        return "https://ui-avatars.com/api/?name=User&size={$size}&background=6366f1&color=fff&bold=true";
    }
}

if (!function_exists('get_initials')) {
    /**
     * Get initials from name
     *
     * @param string $name
     * @return string
     */
    function get_initials($name)
    {
        $words = explode(' ', trim($name));
        $initials = '';
        
        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper(substr($word, 0, 1));
                if (strlen($initials) >= 2) break; // Max 2 initials
            }
        }
        
        return $initials ?: 'U';
    }
}
