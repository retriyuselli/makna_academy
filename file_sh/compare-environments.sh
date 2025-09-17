#!/bin/bash

echo "🔍 COMPARE LOCAL VS SERVER ENVIRONMENT"
echo "======================================"
echo ""

echo "📋 Step 1: Check environment differences..."
echo "Current environment: $(php -r "echo app()->environment();")"
echo "Debug mode: $(php -r "echo config('app.debug') ? 'true' : 'false';")"

echo ""
echo "📋 Step 2: Check authentication middleware differences..."
php -r "
require_once 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    // Get current user if any
    if (class_exists('\Auth')) {
        echo 'Auth system loaded: YES' . PHP_EOL;
        
        // Check session config
        echo 'Session driver: ' . config('session.driver') . PHP_EOL;
        echo 'Session domain: ' . config('session.domain') . PHP_EOL;
        echo 'Session secure: ' . (config('session.secure') ? 'true' : 'false') . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Auth check error: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "📋 Step 3: Check if there's a web server config issue..."
echo "Checking .htaccess configuration..."
if [ -f "public/.htaccess" ]; then
    echo "✅ .htaccess exists"
    echo "First 10 lines:"
    head -10 public/.htaccess
else
    echo "❌ .htaccess missing in public/"
fi

echo ""
echo "📋 Step 4: Check URL configuration differences..."
php -r "
require_once 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo 'APP_URL: ' . config('app.url') . PHP_EOL;
echo 'APP_ENV: ' . config('app.env') . PHP_EOL;
echo 'Force HTTPS: ' . (config('app.force_https', false) ? 'true' : 'false') . PHP_EOL;
"

echo ""
echo "📋 Step 5: Check middleware stack differences..."
php artisan route:list --path=admin | head -5

echo ""
echo "📋 Step 6: Check session/cookie settings..."
php -r "
require_once 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo 'Session lifetime: ' . config('session.lifetime') . ' minutes' . PHP_EOL;
echo 'Session path: ' . config('session.path') . PHP_EOL;
echo 'Session same_site: ' . config('session.same_site') . PHP_EOL;
echo 'Cookie secure: ' . (config('session.secure') ? 'true' : 'false') . PHP_EOL;
"

echo ""
echo "🎯 ANALYSIS COMPLETE"
echo "==================="
echo ""
echo "💡 Common causes for local vs server difference:"
echo "1. Session configuration (domain, secure, same_site)"
echo "2. .htaccess or web server configuration"
echo "3. Environment variables (.env differences)"
echo "4. HTTPS vs HTTP authentication"
echo "5. Custom middleware blocking on production"
