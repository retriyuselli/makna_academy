<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogLoginAttempts
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Hanya log untuk POST request ke login route
        if ($request->isMethod('POST') && $request->is('login')) {
            $email = $request->input('email');
            $ip = $request->ip();
            $userAgent = $request->userAgent();
            $timestamp = now()->toDateTimeString();
            
            Log::channel('security')->info('Login attempt', [
                'email' => $email,
                'ip' => $ip,
                'user_agent' => $userAgent,
                'timestamp' => $timestamp,
                'url' => $request->fullUrl(),
                'referer' => $request->header('referer')
            ]);
        }

        $response = $next($request);

        // Log hasil login jika POST ke login
        if ($request->isMethod('POST') && $request->is('login')) {
            $statusCode = $response->getStatusCode();
            $isRedirect = $response->isRedirect();
            
            Log::channel('security')->info('Login attempt result', [
                'email' => $request->input('email'),
                'ip' => $request->ip(),
                'status_code' => $statusCode,
                'is_redirect' => $isRedirect,
                'success' => $isRedirect && $statusCode === 302,
                'timestamp' => now()->toDateTimeString()
            ]);
        }

        return $response;
    }
}
