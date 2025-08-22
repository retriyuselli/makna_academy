#!/bin/bash

echo "🔐 Safe Auth Fix - Makna Academy"
echo "==============================="

echo "📊 Step 1: Current status check..."
echo "Website URL: https://maknaacademy.com/"
curl -s -o /dev/null -w "Homepage HTTP Status: %{http_code}\n" https://maknaacademy.com/ || echo "Cannot test HTTP status"

echo ""
echo "🧪 Step 2: Test current auth routes..."
php artisan route:list | grep -E "(login|register)" | head -5

echo ""
echo "🔍 Step 3: Check auth controllers..."
echo -n "AuthenticatedSessionController: "
[ -f "app/Http/Controllers/Auth/AuthenticatedSessionController.php" ] && echo "✅ EXISTS" || echo "❌ MISSING"

echo -n "RegisteredUserController: "
[ -f "app/Http/Controllers/Auth/RegisteredUserController.php" ] && echo "✅ EXISTS" || echo "❌ MISSING"

echo ""
echo "🔍 Step 4: Check auth views..."
echo -n "Login view: "
[ -f "resources/views/auth/login.blade.php" ] && echo "✅ EXISTS" || echo "❌ MISSING"

echo -n "Register view: "
[ -f "resources/views/auth/register.blade.php" ] && echo "✅ EXISTS" || echo "❌ MISSING"

echo ""
echo "🔧 Step 5: Minimal cache clear (safe)..."
php artisan view:clear 2>/dev/null && echo "✅ Views cleared" || echo "⚠️ View clear skipped"
php artisan route:clear 2>/dev/null && echo "✅ Routes cleared" || echo "⚠️ Route clear skipped"

echo ""
echo "📋 Step 6: Rebuild only routes cache..."
php artisan route:cache 2>/dev/null && echo "✅ Routes cached" || echo "⚠️ Route cache failed"

echo ""
echo "🧪 Step 7: Test auth URLs..."
echo "Testing auth routes:"
curl -s -o /dev/null -w "Login page: %{http_code}\n" https://maknaacademy.com/login || echo "Cannot test login URL"
curl -s -o /dev/null -w "Register page: %{http_code}\n" https://maknaacademy.com/register || echo "Cannot test register URL"

echo ""
echo "✅ Safe auth fix completed!"
echo ""
echo "🔄 Test these URLs manually:"
echo "1. Login: https://maknaacademy.com/login"
echo "2. Register: https://maknaacademy.com/register"
echo ""
echo "📝 If 404 error on auth pages:"
echo "- Routes might not be cached properly"
echo "- Check .htaccess configuration"
echo ""
echo "📝 If 500 error on auth pages:"
echo "- Check Laravel logs: tail -f storage/logs/laravel.log"
echo "- Might be missing dependencies"
