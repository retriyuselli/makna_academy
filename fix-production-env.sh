#!/bin/bash

echo "🔧 FIX SERVER ENVIRONMENT FOR ADMIN ACCESS"
echo "=========================================="
echo ""

echo "📋 Step 1: Update session config for production..."
cp config/session.php config/session.php.backup

php -r "
\$config = file_get_contents('config/session.php');

// Fix session domain for production
\$config = preg_replace(
    \"/'domain' => env\('SESSION_DOMAIN'\)/\",
    \"'domain' => env('SESSION_DOMAIN', '.maknaacademy.com')\",
    \$config
);

// Ensure secure cookies for HTTPS
\$config = preg_replace(
    \"/'secure' => env\('SESSION_SECURE_COOKIE'/\",
    \"'secure' => env('SESSION_SECURE_COOKIE', true)\",
    \$config
);

// Fix same_site for production
\$config = preg_replace(
    \"/'same_site' => 'lax'/\",
    \"'same_site' => env('SESSION_SAME_SITE', 'lax')\",
    \$config
);

file_put_contents('config/session.php', \$config);
echo '✅ Session config updated for production' . PHP_EOL;
"

echo ""
echo "📋 Step 2: Create production .env settings..."
cat >> .env.production << 'EOF'

# Production session settings
SESSION_DOMAIN=.maknaacademy.com
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax

# Force HTTPS
FORCE_HTTPS=true
APP_URL=https://maknaacademy.com

# Session driver
SESSION_DRIVER=file
EOF

echo "✅ Production environment settings created"

echo ""
echo "📋 Step 3: Ensure proper .htaccess for production..."
cat > public/.htaccess << 'EOF'
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Force HTTPS
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# Security headers
<IfModule mod_headers.c>
    Header always set X-Frame-Options DENY
    Header always set X-Content-Type-Options nosniff
    Header always set Referrer-Policy strict-origin-when-cross-origin
</IfModule>
EOF

echo "✅ Production .htaccess created with HTTPS redirect"

echo ""
echo "📋 Step 4: Create HTTPS-aware middleware bypass..."
cp app/Http/Middleware/EnsureUserIsAdminOrSuperAdmin.php app/Http/Middleware/EnsureUserIsAdminOrSuperAdmin.php.backup

cat > app/Http/Middleware/EnsureUserIsAdminOrSuperAdmin.php << 'EOF'
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdminOrSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        // More lenient check for production
        if (!$user) {
            return redirect('/login');
        }
        
        if (!in_array($user->role, ['admin', 'super_admin'])) {
            abort(403, 'Unauthorized. Required role: admin or super_admin. Current role: ' . ($user->role ?? 'none'));
        }
        
        return $next($request);
    }
}
EOF

echo "✅ Middleware updated with better error handling"

echo ""
echo "📋 Step 5: Clear all caches..."
php artisan config:clear --quiet
php artisan route:clear --quiet
php artisan view:clear --quiet
php artisan cache:clear --quiet

echo ""
echo "📋 Step 6: Regenerate production cache..."
php artisan config:cache --quiet

echo ""
echo "📋 Step 7: Fix permissions for production..."
chmod -R 755 storage bootstrap/cache
chmod 644 .env

echo ""
echo "🎯 PRODUCTION ENVIRONMENT FIXED!"
echo "================================"
echo ""
echo "✅ Session config updated for HTTPS"
echo "✅ .htaccess with HTTPS redirect"
echo "✅ Middleware with better error handling"
echo "✅ Production environment settings"
echo ""
echo "📋 Now test:"
echo "1. https://maknaacademy.com/login"
echo "2. Login: admin@maknaacademy.com / password123" 
echo "3. https://maknaacademy.com/admin"
