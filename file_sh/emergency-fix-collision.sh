#!/bin/bash

echo "🚨 EMERGENCY FIX: CollisionServiceProvider Error"
echo "==============================================="
echo ""

echo "📋 This error occurs when dependencies are corrupted or incomplete"
echo "📋 Solution: Complete reinstall of all packages"
echo ""

echo "📋 Step 1: Check current directory..."
pwd
ls -la

echo ""
echo "📋 Step 2: Remove all cached and vendor files..."
rm -rf vendor/
rm -f composer.lock
rm -rf bootstrap/cache/*.php
rm -rf storage/framework/cache/*
rm -rf storage/framework/sessions/*
rm -rf storage/framework/views/*

echo ""
echo "📋 Step 3: Clear composer cache..."
composer2 clear-cache || composer clear-cache

echo ""
echo "📋 Step 4: Check composer version..."
composer2 --version || composer --version

echo ""
echo "📋 Step 5: Install dependencies from scratch..."
composer2 install --no-dev --optimize-autoloader --verbose

echo ""
echo "📋 Step 6: If install fails, try update..."
if [ $? -ne 0 ]; then
    echo "Install failed, trying composer update..."
    composer2 update --no-dev --optimize-autoloader --verbose
fi

echo ""
echo "📋 Step 7: Regenerate autoload files..."
composer2 dump-autoload --no-dev --optimize

echo ""
echo "📋 Step 8: Test autoload..."
php -r "
try {
    require_once 'vendor/autoload.php';
    echo '✅ Autoload: OK' . PHP_EOL;
    
    if (class_exists('NunoMaduro\\Collision\\Adapters\\Laravel\\CollisionServiceProvider')) {
        echo '✅ CollisionServiceProvider: FOUND' . PHP_EOL;
    } else {
        echo '❌ CollisionServiceProvider: NOT FOUND' . PHP_EOL;
    }
} catch (Exception \$e) {
    echo '❌ Autoload Error: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "📋 Step 9: Test Laravel bootstrap..."
php -r "
try {
    require_once 'vendor/autoload.php';
    \$app = require_once 'bootstrap/app.php';
    echo '✅ Laravel App: OK' . PHP_EOL;
} catch (Exception \$e) {
    echo '❌ Laravel Bootstrap Error: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "📋 Step 10: Clear Laravel caches..."
php artisan config:clear 2>/dev/null || echo "Config clear failed"
php artisan route:clear 2>/dev/null || echo "Route clear failed" 
php artisan view:clear 2>/dev/null || echo "View clear failed"
php artisan cache:clear 2>/dev/null || echo "Cache clear failed"

echo ""
echo "📋 Step 11: Fix permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod 644 .env

echo ""
echo "📋 Step 12: Run migrations..."
php artisan migrate --force

echo ""
echo "🎯 COLLISION ERROR FIX COMPLETED!"
echo "================================="
echo ""
echo "✅ Test the website:"
echo "Frontend: https://maknaacademy.com"
echo "Admin: https://maknaacademy.com/admin"
echo ""
echo "📋 If still error:"
echo "1. Check PHP version: php -v"
echo "2. Check if all files exist: ls -la vendor/"
echo "3. Try manual composer install"
echo ""
echo "📋 Manual backup commands:"
echo "composer2 require nunomaduro/collision --no-dev"
echo "composer2 require laravel/framework --no-dev"
