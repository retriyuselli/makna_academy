#!/bin/bash

echo "🔥 Provider Repository Fix - Makna Academy"
echo "=========================================="

echo "📋 Current directory:"
pwd

echo ""
echo "🗑️ Step 1: Remove problematic cache files..."
rm -rf bootstrap/cache/services.php
rm -rf bootstrap/cache/packages.php
rm -rf bootstrap/cache/config.php
rm -rf bootstrap/cache/routes.php
rm -rf bootstrap/cache/*

echo ""
echo "🗂️ Step 2: Clear storage framework cache..."
rm -rf storage/framework/cache/data/*
rm -rf storage/framework/views/*
rm -rf storage/framework/sessions/*

echo ""
echo "🔒 Step 3: Fix permissions (CRITICAL)..."
chmod -R 755 bootstrap/
chmod -R 755 bootstrap/cache/
chmod -R 775 storage/
chmod -R 775 storage/logs/
chmod -R 775 storage/framework/
chmod 644 .env

echo ""
echo "👤 Step 4: Change ownership (if needed)..."
# Uncomment next line if you have sudo access
# chown -R www-data:www-data storage/ bootstrap/cache/

echo ""
echo "🔄 Step 5: Regenerate composer autoload (IMPORTANT)..."
composer dump-autoload --optimize --no-dev

echo ""
echo "⚡ Step 6: Try Laravel commands one by one..."
echo "Testing basic artisan..."
php artisan --version 2>&1 | head -3

echo ""
echo "Testing config clear..."
php artisan config:clear 2>/dev/null && echo "✅ Config cleared" || echo "❌ Config clear failed"

echo ""
echo "Testing route clear..."
php artisan route:clear 2>/dev/null && echo "✅ Routes cleared" || echo "❌ Route clear failed"

echo ""
echo "Testing view clear..."
php artisan view:clear 2>/dev/null && echo "✅ Views cleared" || echo "❌ View clear failed"

echo ""
echo "🔧 Step 7: Rebuild caches step by step..."
php artisan config:cache 2>/dev/null && echo "✅ Config cached" || echo "❌ Config cache failed"
php artisan route:cache 2>/dev/null && echo "✅ Routes cached" || echo "❌ Route cache failed"

echo ""
echo "🧪 Step 8: Test specific routes..."
php artisan route:list | grep -E "(login|register|dashboard)" && echo "✅ Auth routes found" || echo "❌ No auth routes"

echo ""
echo "📁 Step 9: Check critical files..."
echo "Checking bootstrap/cache/ directory:"
ls -la bootstrap/cache/

echo ""
echo "Checking storage permissions:"
ls -la storage/

echo ""
echo "✅ Provider Repository fix completed!"
echo ""
echo "🔄 Test these URLs now:"
echo "- https://maknaacademy.com/"
echo "- https://maknaacademy.com/login"
echo "- https://maknaacademy.com/register"
echo ""
echo "🐛 If still error, run: tail -f storage/logs/laravel.log"
