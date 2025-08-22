<?php

use Illuminate\Support\Facades\Auth;

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
        if ($user->avatar && filter_var($user->avatar, FILTER_VALIDATE_URL)) {
            // Jika URL Google, ubah size parameter
            if (strpos($user->avatar, 'googleusercontent.com') !== false) {
                return preg_replace('/=s\d+-c$/', "=s{$size}-c", $user->avatar);
            }
            return $user->avatar;
        }

        // Jika ada avatar lokal (path atau storage)
        if ($user->avatar && !filter_var($user->avatar, FILTER_VALIDATE_URL)) {
            // Cek apakah ada di storage
            if (str_starts_with($user->avatar, 'avatars/')) {
                return asset('storage/' . $user->avatar);
            }
            // Atau langsung return asset
            return asset('storage/avatars/' . $user->avatar);
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
