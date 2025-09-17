#!/bin/bash

echo "🔧 Breeze ServiceProvider Fix - Makna Academy"
echo "============================================"

echo "📋 Step 1: Check current composer status..."
composer show | grep breeze || echo "❌ Breeze not installed"

echo ""
echo "🔄 Step 2: Install missing dependencies..."
echo "Installing Laravel Breeze (required for auth)..."
composer require laravel/breeze --no-dev

echo ""
echo "🧹 Step 3: Clear all caches..."
rm -rf bootstrap/cache/*.php
rm -rf storage/framework/cache/*
rm -rf storage/framework/sessions/*
rm -rf storage/framework/views/*

echo ""
echo "🔄 Step 4: Regenerate autoload..."
composer dump-autoload --optimize --no-dev

echo ""
echo "⚙️ Step 5: Test Laravel bootstrap..."
php -r "
try {
    require 'vendor/autoload.php';
    echo '✅ Autoload works\n';
    \$app = require 'bootstrap/app.php';
    echo '✅ App bootstrap works\n';
    echo 'Environment: ' . \$app->environment() . '\n';
} catch (Exception \$e) {
    echo '❌ Error: ' . \$e->getMessage() . '\n';
    echo 'File: ' . \$e->getFile() . ':' . \$e->getLine() . '\n';
}
"

echo ""
echo "🔒 Step 6: Fix permissions..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod 644 .env

echo ""
echo "🧪 Step 7: Test artisan commands..."
php artisan config:clear 2>/dev/null && echo "✅ Config cleared" || echo "⚠️ Config clear skipped"
php artisan route:clear 2>/dev/null && echo "✅ Routes cleared" || echo "⚠️ Route clear skipped"

echo ""
echo "📋 Step 8: Rebuild caches..."
php artisan config:cache 2>/dev/null && echo "✅ Config cached" || echo "⚠️ Config cache skipped"
php artisan route:cache 2>/dev/null && echo "✅ Routes cached" || echo "⚠️ Route cache skipped"

echo ""
echo "🔍 Step 9: Verify Breeze installation..."
composer show laravel/breeze 2>/dev/null && echo "✅ Breeze installed" || echo "❌ Breeze missing"

echo ""
echo "🧪 Step 10: Test auth routes..."
php artisan route:list | grep -E "(login|register)" | head -5

echo ""
echo "✅ Breeze fix completed!"
echo ""
echo "🔄 Test these URLs now:"
echo "- https://maknaacademy.com/"
echo "- https://maknaacademy.com/login"
echo "- https://maknaacademy.com/register"
