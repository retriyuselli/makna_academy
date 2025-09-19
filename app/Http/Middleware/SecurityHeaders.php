<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Security headers untuk melindungi dari berbagai serangan
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // Content Security Policy untuk mencegah XSS
        // More permissive for development, dapat disesuaikan untuk production
        $enableCSP = env('ENABLE_CSP', false); // Set to true in production
        
        if ($enableCSP) {
            $csp = "default-src 'self'; " .
                   "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com https://cdn.jsdelivr.net https://unpkg.com https://cdnjs.cloudflare.com https://kit.fontawesome.com; " .
                   "style-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com https://fonts.googleapis.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://kit.fontawesome.com; " .
                   "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com data:; " .
                   "img-src 'self' data: https: http: blob:; " .
                   "connect-src 'self' https:; " .
                   "object-src 'none'; " .
                   "base-uri 'self'; " .
                   "frame-ancestors 'none'";
            
            // Hanya set CSP yang ketat di production
            if (app()->environment('production')) {
                $response->headers->set('Content-Security-Policy', $csp);
            } else {
                // Development mode - CSP lebih permissive
                $response->headers->set('Content-Security-Policy-Report-Only', $csp);
            }
        }
        
        // HTTPS enforcement untuk production
        if (app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        return $response;
    }
}
