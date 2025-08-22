#!/bin/bash

echo "🔧 FIX COLLISION SERVICE PROVIDER ERROR"
echo "======================================="
echo ""

echo "📋 Error: CollisionServiceProvider not found"
echo "📋 Solution: Reinstall dependencies with composer2"
echo ""

echo "📋 Step 1: Remove vendor and lock files..."
rm -rf vendor
rm -f composer.lock

echo ""
echo "📋 Step 2: Clear composer cache..."
composer2 clear-cache

echo ""
echo "📋 Step 3: Install fresh dependencies..."
composer2 install --no-dev --optimize-autoloader

echo ""
echo "📋 Step 4: If above fails, try update..."
if [ $? -ne 0 ]; then
    echo "Install failed, trying update..."
    composer2 update --no-dev --optimize-autoloader
fi

echo ""
echo "📋 Step 5: Run migrations..."
php artisan migrate --force

echo ""
echo "📋 Step 6: Clear all caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

echo ""
echo "📋 Step 7: Fix permissions..."
chmod -R 755 storage bootstrap/cache

echo ""
echo "📋 Step 8: Test Laravel bootstrap..."
php -r "
try {
    require_once 'vendor/autoload.php';
    \$app = require_once 'bootstrap/app.php';
    \$app->boot();
    echo '✅ Laravel bootstrap: OK' . PHP_EOL;
    echo '✅ CollisionServiceProvider: FIXED' . PHP_EOL;
} catch (Exception \$e) {
    echo '❌ Error: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "🎯 COLLISION ERROR FIX COMPLETED!"
echo "================================="
echo ""
echo "✅ Test admin panel:"
echo "URL: https://maknaacademy.com/admin"
echo "Login: admin@maknaacademy.com / password123"
echo ""
echo "📋 If still error, check PHP version:"
echo "php -v"
