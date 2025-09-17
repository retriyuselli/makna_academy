#!/bin/bash

echo "🔧 Install Laravel Breeze - Makna Academy"
echo "========================================"

echo "📋 Step 1: Check current Breeze status..."
if composer show laravel/breeze >/dev/null 2>&1; then
    echo "✅ Breeze already installed"
    composer show laravel/breeze
else
    echo "❌ Breeze not installed - proceeding with installation..."
    
    echo ""
    echo "📦 Step 2: Install Laravel Breeze..."
    composer require laravel/breeze --no-interaction
    
    if [ $? -eq 0 ]; then
        echo "✅ Breeze installation completed"
    else
        echo "❌ Breeze installation failed"
        exit 1
    fi
fi

echo ""
echo "🧹 Step 3: Clear caches after installation..."
rm -rf bootstrap/cache/*.php
php artisan config:clear 2>/dev/null
php artisan route:clear 2>/dev/null
php artisan view:clear 2>/dev/null

echo ""
echo "🔄 Step 4: Regenerate autoload..."
composer dump-autoload --optimize

echo ""
echo "🧪 Step 5: Test Breeze classes..."
php -r "
require 'vendor/autoload.php';

if (class_exists('Laravel\Breeze\BreezeServiceProvider')) {
    echo '✅ BreezeServiceProvider now available\n';
} else {
    echo '❌ BreezeServiceProvider still missing\n';
}
"

echo ""
echo "⚙️ Step 6: Rebuild caches..."
php artisan config:cache 2>/dev/null && echo "✅ Config cached" || echo "⚠️ Config cache skipped"
php artisan route:cache 2>/dev/null && echo "✅ Routes cached" || echo "⚠️ Route cache skipped"

echo ""
echo "🧪 Step 7: Test auth routes after Breeze install..."
php artisan route:list | grep -E "(login|register)" | head -5

echo ""
echo "🔍 Step 8: Test auth URLs..."
echo "Testing login URL:"
curl -s -o /dev/null -w "Login: HTTP %{http_code}\n" https://maknaacademy.com/login 2>/dev/null || echo "Cannot test login URL"

echo "Testing register URL:"
curl -s -o /dev/null -w "Register: HTTP %{http_code}\n" https://maknaacademy.com/register 2>/dev/null || echo "Cannot test register URL"

echo ""
echo "✅ Breeze installation process completed!"
echo ""
echo "🔄 Test these URLs now:"
echo "- https://maknaacademy.com/login"
echo "- https://maknaacademy.com/register"
echo ""
echo "📝 Expected results after Breeze install:"
echo "- HTTP 200: Auth pages should work ✅"
echo "- HTTP 500: Check Laravel logs for remaining issues ⚠️"
