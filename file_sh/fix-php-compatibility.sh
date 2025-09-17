#!/bin/bash

echo "🔧 FIX PHP VERSION COMPATIBILITY FOR SHIELD"
echo "==========================================="
echo ""

echo "📋 Current PHP version on server:"
php -v

echo ""
echo "📋 Problem: openspout package requires PHP 8.3+, server has PHP 8.2.29"
echo "📋 Solution: Update composer.lock to be compatible with PHP 8.2"
echo ""

echo "📋 Step 1: Remove composer.lock (will regenerate)..."
rm -f composer.lock

echo ""
echo "📋 Step 2: Update composer dependencies for PHP 8.2..."
composer2 update --no-dev --optimize-autoloader

echo ""
echo "📋 Step 3: If above fails, try specific package downgrade..."
if [ $? -ne 0 ]; then
    echo "Trying to downgrade openspout package..."
    composer2 require "openspout/openspout:^4.20" --no-dev --update-with-dependencies
fi

echo ""
echo "📋 Step 4: Run migrations..."
php artisan migrate --force

echo ""
echo "📋 Step 5: Clear all caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

echo ""
echo "📋 Step 6: Fix permissions..."
chmod -R 755 storage bootstrap/cache

echo ""
echo "📋 Step 7: Test Shield installation..."
php -r "
try {
    require_once 'vendor/autoload.php';
    \$app = require_once 'bootstrap/app.php';
    \$app->boot();
    echo '✅ Laravel bootstrap: OK' . PHP_EOL;
    
    if (class_exists('Spatie\\Permission\\Models\\Role')) {
        echo '✅ Spatie Permission: OK' . PHP_EOL;
    }
    
    if (class_exists('BezhanSalleh\\FilamentShield\\FilamentShieldPlugin')) {
        echo '✅ Filament Shield: OK' . PHP_EOL;
    }
    
} catch (Exception \$e) {
    echo '❌ Error: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "🎯 PHP COMPATIBILITY FIX COMPLETED!"
echo "==================================="
echo ""
echo "✅ Test admin panel:"
echo "URL: https://maknaacademy.com/admin"
echo "Login: admin@maknaacademy.com / password123"
echo ""
echo "📋 Alternative: If server supports PHP 8.3+:"
echo "Contact Hostinger to upgrade PHP version to 8.3 or 8.4"
