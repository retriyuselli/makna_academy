#!/bin/bash

echo "🔐 Login/Register Specific Fix - Makna Academy"
echo "============================================="

echo "📋 Step 1: Check current auth route status..."
php artisan route:list | grep -E "(login|register)" || echo "❌ No auth routes found"

echo ""
echo "🔍 Step 2: Verify auth files exist..."
echo -n "routes/auth.php: "
[ -f "routes/auth.php" ] && echo "✅ EXISTS" || echo "❌ MISSING"

echo -n "AuthenticatedSessionController: "
[ -f "app/Http/Controllers/Auth/AuthenticatedSessionController.php" ] && echo "✅ EXISTS" || echo "❌ MISSING"

echo -n "RegisteredUserController: "
[ -f "app/Http/Controllers/Auth/RegisteredUserController.php" ] && echo "✅ EXISTS" || echo "❌ MISSING"

echo ""
echo "🔍 Step 3: Check if auth routes are included in web.php..."
grep -n "auth.php" routes/web.php || echo "❌ auth.php not included in web.php"

echo ""
echo "🔧 Step 4: Clear only route cache (safe)..."
php artisan route:clear
echo "Route cache cleared"

echo ""
echo "🔄 Step 5: Rebuild routes..."
php artisan route:cache
echo "Routes cached"

echo ""
echo "🧪 Step 6: Test auth routes registration..."
echo "Checking if login route exists:"
php artisan route:list --path=login || echo "❌ Login route not found"

echo ""
echo "Checking if register route exists:"  
php artisan route:list --path=register || echo "❌ Register route not found"

echo ""
echo "🔍 Step 7: Check auth views..."
echo -n "Login view: "
[ -f "resources/views/auth/login.blade.php" ] && echo "✅ EXISTS" || echo "❌ MISSING"

echo -n "Register view: "
[ -f "resources/views/auth/register.blade.php" ] && echo "✅ EXISTS" || echo "❌ MISSING"

echo ""
echo "🧪 Step 8: Test URLs directly..."
echo "Testing login URL:"
curl -s -o /dev/null -w "Login page HTTP status: %{http_code}\n" https://maknaacademy.com/login 2>/dev/null || echo "Cannot test login URL"

echo "Testing register URL:"
curl -s -o /dev/null -w "Register page HTTP status: %{http_code}\n" https://maknaacademy.com/register 2>/dev/null || echo "Cannot test register URL"

echo ""
echo "✅ Login/Register fix completed!"
echo ""
echo "🔄 Test these URLs now:"
echo "1. Login: https://maknaacademy.com/login"
echo "2. Register: https://maknaacademy.com/register"
echo ""
echo "📝 Expected results:"
echo "- HTTP 200: Page works ✅"
echo "- HTTP 404: Route not found (need further fix) ❌"
echo "- HTTP 500: Server error (check logs) ❌"
