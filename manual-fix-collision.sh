#!/bin/bash

echo "🔧 MANUAL FIX: Collision Error Alternative"
echo "========================================="
echo ""

echo "📋 Alternative method when composer2 fails completely"
echo ""

echo "📋 Step 1: Try downloading composer manually..."
curl -sS https://getcomposer.org/installer | php
php composer.phar --version

echo ""
echo "📋 Step 2: Remove problematic packages first..."
rm -rf vendor/nunomaduro/
rm -rf vendor/laravel/framework/
rm -f composer.lock

echo ""
echo "📋 Step 3: Install core packages manually..."
php composer.phar install --no-dev --no-scripts --optimize-autoloader

echo ""
echo "📋 Step 4: If composer.phar works, use it instead..."
if [ $? -eq 0 ]; then
    echo "✅ composer.phar working, continuing with it..."
    php composer.phar dump-autoload --no-dev --optimize
else
    echo "❌ composer.phar also failed, trying composer2..."
    composer2 install --no-dev --no-scripts --optimize-autoloader
fi

echo ""
echo "📋 Step 5: Test basic autoload..."
php -r "
require_once 'vendor/autoload.php';
echo 'Autoload test passed' . PHP_EOL;
"

echo ""
echo "📋 Step 6: Bootstrap Laravel without collision..."
php -r "
try {
    \$_ENV['APP_ENV'] = 'production';
    require_once 'vendor/autoload.php';
    \$app = require_once 'bootstrap/app.php';
    echo 'Laravel bootstrap successful' . PHP_EOL;
} catch (Exception \$e) {
    echo 'Error: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "📋 Step 7: Run essential artisan commands..."
php artisan config:clear --quiet
php artisan route:clear --quiet
php artisan migrate --force --quiet

echo ""
echo "🎯 MANUAL FIX COMPLETED!"
echo "======================="
echo ""
echo "Test: https://maknaacademy.com"
