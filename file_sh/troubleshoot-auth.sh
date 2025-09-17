#!/bin/bash

echo "🔍 Auth Troubleshooting - Makna Academy"
echo "======================================"

echo "🧪 Testing each auth URL individually..."

echo ""
echo "📋 Test 1: Homepage"
echo "URL: https://maknaacademy.com/"
curl -s -I https://maknaacademy.com/ | head -1 || echo "❌ Cannot reach homepage"

echo ""
echo "📋 Test 2: Login page"
echo "URL: https://maknaacademy.com/login"
curl -s -I https://maknaacademy.com/login | head -1 || echo "❌ Cannot reach login"

echo ""
echo "📋 Test 3: Register page"  
echo "URL: https://maknaacademy.com/register"
curl -s -I https://maknaacademy.com/register | head -1 || echo "❌ Cannot reach register"

echo ""
echo "🔍 Check route definitions..."
echo "Auth routes in routes/auth.php:"
grep -n "login\|register" routes/auth.php | head -5

echo ""
echo "🔍 Check web routes..."
echo "Auth require in routes/web.php:"
grep -n "auth.php" routes/web.php

echo ""
echo "🧪 Test artisan route list..."
echo "Registered auth routes:"
php artisan route:list --path=login 2>/dev/null || echo "❌ Route list failed"
php artisan route:list --path=register 2>/dev/null || echo "❌ Route list failed"

echo ""
echo "🔍 Check recent Laravel errors..."
echo "Last 5 lines from Laravel log:"
tail -5 storage/logs/laravel.log 2>/dev/null || echo "No Laravel log found"

echo ""
echo "📊 Summary:"
echo "- If HTTP 200: Page loads correctly ✅"
echo "- If HTTP 404: Route not found - check .htaccess or route cache ❌"
echo "- If HTTP 500: Server error - check Laravel logs ❌"
echo "- If HTTP 302: Redirect - check middleware ⚠️"

echo ""
echo "✅ Troubleshooting completed!"
