#!/bin/bash

echo "🚨 EMERGENCY ROLLBACK - Makna Academy"
echo "===================================="

echo "📊 Step 1: Check current status..."
pwd
whoami
ls -la | head -5

echo ""
echo "🔄 Step 2: Reset to last working state..."
git log --oneline -5

echo ""
echo "🔙 Step 3: Rollback to previous commit (before fixes)..."
git reset --hard 69fbad9  # Last known working commit

echo ""
echo "🧹 Step 4: Clean up everything..."
rm -rf bootstrap/cache/*
rm -rf storage/framework/cache/*
rm -rf storage/framework/sessions/*
rm -rf storage/framework/views/*

echo ""
echo "📦 Step 5: Simple composer fix..."
composer install --no-dev --optimize-autoloader 2>/dev/null || echo "Composer install failed, trying dump-autoload..."
composer dump-autoload 2>/dev/null || echo "Composer dump-autoload failed"

echo ""
echo "🔒 Step 6: Basic permissions..."
chmod -R 755 storage/ 2>/dev/null
chmod -R 755 bootstrap/cache/ 2>/dev/null
chmod 644 .env 2>/dev/null

echo ""
echo "⚡ Step 7: Minimal Laravel setup..."
php artisan config:clear 2>/dev/null || echo "Config clear skipped"
php artisan route:clear 2>/dev/null || echo "Route clear skipped"
php artisan view:clear 2>/dev/null || echo "View clear skipped"

echo ""
echo "🧪 Step 8: Test basic access..."
echo "Testing if website responds..."

echo ""
echo "📁 Step 9: Check critical files..."
echo "Index.php exists:"
ls -la public/index.php 2>/dev/null && echo "✅ public/index.php OK" || echo "❌ public/index.php MISSING"

echo ".htaccess exists:"
ls -la .htaccess 2>/dev/null && echo "✅ .htaccess OK" || echo "❌ .htaccess MISSING"
ls -la public/.htaccess 2>/dev/null && echo "✅ public/.htaccess OK" || echo "❌ public/.htaccess MISSING"

echo ""
echo "🚨 EMERGENCY ROLLBACK COMPLETED!"
echo ""
echo "🔍 Test immediately:"
echo "1. Homepage: https://maknaacademy.com/"
echo "2. If homepage works, we can debug login/register separately"
echo ""
echo "📋 Next steps if still down:"
echo "- Check Apache/Nginx error logs"
echo "- Verify document root points to correct directory"
echo "- Check PHP version compatibility"
