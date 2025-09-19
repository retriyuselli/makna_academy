<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleFilamentLogoutRedirect
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Check if this is a POST request to Filament logout
        if ($request->isMethod('POST') && $request->is('admin/logout')) {
            // Redirect to home page instead of login page
            return redirect('/')->with('status', 'Anda telah berhasil logout dari admin panel.');
        }
        
        return $response;
    }
}
