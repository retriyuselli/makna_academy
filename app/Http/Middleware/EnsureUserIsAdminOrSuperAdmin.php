<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdminOrSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Skip middleware for logout requests
        if ($request->is('admin/logout') || $request->routeIs('filament.admin.auth.logout')) {
            return $next($request);
        }
        
        $user = $request->user();
        if (!$user || !in_array($user->role, ['admin', 'super_admin'])) {
            abort(403, 'Unauthorized.');
        }
        return $next($request);
    }
}
