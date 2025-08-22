<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SmartAdminVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        // If user is not authenticated, let other middleware handle it
        if (!$user) {
            return $next($request);
        }
        
        // Auto-verify admin users (super_admin and admin roles)
        if (in_array($user->role, ['super_admin', 'admin']) && is_null($user->email_verified_at)) {
            DB::table('users')
                ->where('id', $user->id)
                ->update(['email_verified_at' => now()]);
                
            // Log admin auto-verification
            Log::info("Admin user auto-verified: {$user->email} ({$user->role})");
        }
        
        // If user has Google ID, auto-verify their email if not already verified
        if ($user->google_id && is_null($user->email_verified_at)) {
            DB::table('users')
                ->where('id', $user->id)
                ->update(['email_verified_at' => now()]);
        }
        
        // Refresh user to get updated data
        $user = Auth::user();
        
        // If user is verified (either was already verified or just auto-verified), continue
        if ($user->email_verified_at) {
            return $next($request);
        }
        
        // If user is not verified and is regular customer, redirect to verification
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Your email address is not verified.'], 409);
        }
        
        return redirect()->route('verification.notice')
            ->with('message', 'Silakan verifikasi email Anda untuk melanjutkan.');
    }
}
