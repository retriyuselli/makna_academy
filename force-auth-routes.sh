#!/bin/bash

echo "🔧 Force Auth Routes Registration - Makna Academy"
echo "==============================================="

echo "📋 Step 1: Check web.php includes auth.php..."
echo "Content of routes/web.php (last few lines):"
tail -5 routes/web.php

echo ""
echo "🔍 Step 2: Verify auth.php content..."
echo "First 10 lines of routes/auth.php:"
head -10 routes/auth.php

echo ""
echo "🔧 Step 3: Force clear ALL route caches..."
rm -f bootstrap/cache/routes-v7.php 2>/dev/null
rm -f bootstrap/cache/routes.php 2>/dev/null
echo "Route cache files removed"

echo ""
echo "🔄 Step 4: Clear artisan caches..."
php artisan route:clear
php artisan config:clear
echo "Artisan caches cleared"

echo ""
echo "📋 Step 5: Test route registration manually..."
echo "Testing if Laravel can load routes..."
php -r "
require 'vendor/autoload.php';
\$app = require 'bootstrap/app.php';
try {
    \$kernel = \$app->make(Illuminate\Contracts\Http\Kernel::class);
    echo 'Laravel kernel loaded successfully\n';
} catch (Exception \$e) {
    echo 'Error loading kernel: ' . \$e->getMessage() . '\n';
}
"

echo ""
echo "🔧 Step 6: Rebuild route cache..."
php artisan route:cache
echo "Route cache rebuilt"

echo ""
echo "🧪 Step 7: List auth routes..."
echo "Auth routes found:"
php artisan route:list | grep -E "(GET|POST).*/(login|register)" || echo "❌ No auth routes found"

echo ""
echo "🔍 Step 8: Direct file check..."
echo "Check if auth controllers are callable:"
php -r "
require 'vendor/autoload.php';
if (class_exists('App\Http\Controllers\Auth\AuthenticatedSessionController')) {
    echo '✅ AuthenticatedSessionController exists\n';
} else {
    echo '❌ AuthenticatedSessionController missing\n';
}

if (class_exists('App\Http\Controllers\Auth\RegisteredUserController')) {
    echo '✅ RegisteredUserController exists\n';
} else {
    echo '❌ RegisteredUserController missing\n';
}
"

echo ""
echo "✅ Force auth routes registration completed!"
echo ""
echo "🔄 Test URLs after this fix:"
echo "- https://maknaacademy.com/login"
echo "- https://maknaacademy.com/register"
