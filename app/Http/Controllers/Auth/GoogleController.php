<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    /**
     * Redirect ke Google untuk autentikasi
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle callback dari Google
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Debug: Log Google user data untuk development
            Log::info('Google User Data', [
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'avatar_url' => $googleUser->avatar,
                'id' => $googleUser->id
            ]);
            
            // Cek apakah user sudah ada berdasarkan google_id
            $user = User::where('google_id', $googleUser->id)->first();
            
            if ($user) {
                // User sudah ada, update avatar jika berubah dan login langsung
                if ($user->avatar_url !== $googleUser->avatar) {
                    $user->update(['avatar_url' => $googleUser->avatar]);
                }
                Auth::login($user);
                return redirect()->intended('/')->with('success', 'Login berhasil dengan Google!');
            }
            
            // Cek apakah ada user dengan email yang sama
            $existingUser = User::where('email', $googleUser->email)->first();
            
            if ($existingUser) {
                // Update user yang sudah ada dengan google_id dan avatar
                $existingUser->update([
                    'google_id' => $googleUser->id,
                    'avatar_url' => $googleUser->avatar,
                    'email_verified_at' => $existingUser->email_verified_at ?? now(), // Auto verify jika belum
                ]);
                
                Auth::login($existingUser);
                return redirect()->intended('/')->with('success', 'Akun Anda berhasil dihubungkan dengan Google!');
            }
            
            // Buat user baru
            $newUser = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'avatar_url' => $googleUser->avatar,
                'password' => Hash::make(Str::random(12)), // Password random
                'role' => 'customer',
                'email_verified_at' => now(), // Auto verify karena Google sudah memverifikasi
            ]);
            
            Auth::login($newUser);
            return redirect()->intended('/')->with('success', 'Pendaftaran berhasil dengan Google!');
            
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Terjadi kesalahan saat login dengan Google. Silakan coba lagi.');
        }
    }
}
