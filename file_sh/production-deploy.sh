#!/bin/bash

echo "🚀 Makna Academy - Production Deployment Script"
echo "==============================================="
echo ""

echo "1️⃣ Clear all caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
echo "✅ Caches cleared"
echo ""

echo "2️⃣ Auto-verify admin users..."
php artisan admin:verify-all --no-interaction
echo "✅ Admin users verified"
echo ""

echo "3️⃣ Cache for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "✅ Production caches created"
echo ""

echo "4️⃣ Optimize composer..."
composer dump-autoload --optimize --no-dev
echo "✅ Composer optimized"
echo ""

echo "5️⃣ Create storage link..."
php artisan storage:link
echo "✅ Storage linked"
echo ""

echo "6️⃣ Run deployment check..."
php artisan deploy:check
echo ""

echo "🎉 DEPLOYMENT COMPLETED!"
echo ""
echo "📋 Verification Checklist:"
echo "✅ Admin login: https://maknaacademy.com/admin"
echo "   📧 User: admin@maknaacademy.com"
echo "   🔐 Pass: password123"
echo ""
echo "✅ Events page: https://maknaacademy.com/events"
echo "✅ Dashboard: https://maknaacademy.com/dashboard"
echo "✅ Google OAuth: https://maknaacademy.com/auth/google/redirect"
echo ""
echo "🚨 If issues persist:"
echo "   • Check domain points to /public folder"
echo "   • Verify .htaccess exists"
echo "   • Check file permissions (755/644)"
