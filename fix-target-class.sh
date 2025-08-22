#!/bin/bash

echo "🎯 Target Class Config Fix"
echo "========================="

echo "🔄 Step 1: Remove all cached files..."
rm -rf bootstrap/cache/config.php
rm -rf bootstrap/cache/routes.php  
rm -rf bootstrap/cache/events.php
rm -rf bootstrap/cache/services.php
find bootstrap/cache/ -name "*.php" -delete

echo "🔄 Step 2: Clear storage cache..."
rm -rf storage/framework/cache/data/*
rm -rf storage/framework/views/*

echo "🔄 Step 3: Regenerate composer autoload..."
composer2 dump-autoload --no-dev --optimize

echo "🔄 Step 4: Fix config step by step..."
php artisan config:clear 2>/dev/null || echo "Config clear skipped"

echo "🔄 Step 5: Test basic artisan..."
php artisan list | head -5

echo "🔄 Step 6: Rebuild caches one by one..."
php artisan config:cache 2>/dev/null && echo "✅ Config cached" || echo "❌ Config cache failed"
php artisan route:cache 2>/dev/null && echo "✅ Routes cached" || echo "❌ Route cache failed"

echo "🔄 Step 7: Test auth routes specifically..."
php artisan route:list | grep -E "(login|register)" || echo "❌ No auth routes found"

echo "✅ Target class fix completed!"
