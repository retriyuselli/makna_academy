<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SmartEmailVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        // If user is not authenticated, let the auth middleware handle it
        if (!$user) {
            return $next($request);
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
        
        // If user is not verified and doesn't have Google ID, redirect to verification
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Your email address is not verified.'], 409);
        }
        
        return redirect()->route('verification.notice')
            ->with('message', 'Silakan verifikasi email Anda untuk melanjutkan.');
    }
}
