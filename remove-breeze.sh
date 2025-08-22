#!/bin/bash

echo "🚫 Remove Breeze Dependency - Alternative Fix"
echo "============================================="

echo "📋 Step 1: Check what's using BreezeServiceProvider..."
grep -r "BreezeServiceProvider" config/ app/ bootstrap/ || echo "No direct references found"

echo ""
echo "🔍 Step 2: Check providers in config/app.php..."
grep -n "Breeze" config/app.php || echo "No Breeze in config/app.php"

echo ""
echo "🗑️ Step 3: Remove Breeze from service providers (if exists)..."
# Backup config/app.php first
cp config/app.php config/app.php.backup

# Remove BreezeServiceProvider from config if exists
sed -i '/BreezeServiceProvider/d' config/app.php 2>/dev/null || echo "No BreezeServiceProvider in config"

echo ""
echo "🔄 Step 4: Remove Breeze package (since it's dev-only)..."
composer remove laravel/breeze --no-interaction || echo "Breeze not in production dependencies"

echo ""
echo "🧹 Step 5: Clear all caches..."
rm -rf bootstrap/cache/*.php
rm -rf storage/framework/cache/*
rm -rf storage/framework/sessions/*
rm -rf storage/framework/views/*

echo ""
echo "🔄 Step 6: Regenerate autoload..."
composer dump-autoload --optimize --no-dev

echo ""
echo "⚙️ Step 7: Test Laravel without Breeze..."
php -r "
try {
    require 'vendor/autoload.php';
    echo '✅ Autoload works\n';
    \$app = require 'bootstrap/app.php';
    echo '✅ App bootstrap works\n';
    echo 'Environment: ' . \$app->environment() . '\n';
} catch (Exception \$e) {
    echo '❌ Error: ' . \$e->getMessage() . '\n';
}
"

echo ""
echo "🔒 Step 8: Fix permissions..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/

echo ""
echo "🧪 Step 9: Test artisan..."
php artisan config:clear
php artisan route:clear
php artisan config:cache
php artisan route:cache

echo ""
echo "📋 Step 10: Check auth routes..."
php artisan route:list | grep -E "(login|register)"

echo ""
echo "✅ Breeze removal completed!"
echo ""
echo "📝 Note: This removes Breeze dependency completely"
echo "Your auth should work with basic Laravel auth system"
