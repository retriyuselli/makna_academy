#!/bin/bash

echo "🚨 Manual Fix - NO ARTISAN (For Provider Error)"
echo "==============================================="

echo "📁 Step 1: Manual cache cleanup..."
echo "Removing all bootstrap cache files:"
find bootstrap/cache/ -name "*.php" -type f -delete 2>/dev/null
ls -la bootstrap/cache/

echo ""
echo "Removing storage framework cache:"
rm -rf storage/framework/cache/data/* 2>/dev/null
rm -rf storage/framework/views/* 2>/dev/null
rm -rf storage/framework/sessions/* 2>/dev/null

echo ""
echo "🔒 Step 2: Fix permissions manually..."
chmod 755 bootstrap/
chmod 755 bootstrap/cache/
chmod -R 775 storage/
chmod 644 .env
chmod 644 composer.json
chmod 644 composer.lock

echo ""
echo "📦 Step 3: Composer fixes..."
composer install --no-dev --optimize-autoloader
composer dump-autoload --optimize --no-dev

echo ""
echo "🔧 Step 4: Manual config check..."
if [ -f ".env" ]; then
    echo "✅ .env exists"
    echo "APP_ENV=$(grep '^APP_ENV=' .env | cut -d'=' -f2)"
    echo "APP_DEBUG=$(grep '^APP_DEBUG=' .env | cut -d'=' -f2)"
else
    echo "❌ .env missing - creating from example"
    cp .env.example .env
fi

echo ""
echo "🧪 Step 5: Test PHP directly..."
php -v | head -1

echo ""
echo "Testing basic Laravel bootstrap:"
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
echo "📋 Step 6: Check critical directories..."
echo "Bootstrap cache:"
ls -la bootstrap/cache/ | wc -l
echo "Storage logs:"
ls -la storage/logs/ 2>/dev/null | head -3

echo ""
echo "✅ Manual fix completed!"
echo ""
echo "🔄 Now try:"
echo "1. Access: https://maknaacademy.com/"
echo "2. If works, try: https://maknaacademy.com/login"
echo "3. Check errors: tail -f storage/logs/laravel.log"
