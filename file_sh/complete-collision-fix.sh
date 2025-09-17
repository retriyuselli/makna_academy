#!/bin/bash

echo "🔧 COMPLETE COLLISION REMOVAL"
echo "============================="
echo ""

echo "📋 Step 1: Check Laravel version and find collision references..."
php artisan --version || echo "Laravel check failed"

echo ""
echo "📋 Step 2: Remove collision from ALL possible locations..."

# Remove from config/app.php
echo "Cleaning config/app.php..."
cp config/app.php config/app.php.backup.$(date +%s)
sed -i '/Collision/d' config/app.php
sed -i '/NunoMaduro/d' config/app.php

# Remove from bootstrap/providers.php (Laravel 11+)
if [ -f "bootstrap/providers.php" ]; then
    echo "Cleaning bootstrap/providers.php..."
    cp bootstrap/providers.php bootstrap/providers.php.backup.$(date +%s)
    sed -i '/Collision/d' bootstrap/providers.php
    sed -i '/NunoMaduro/d' bootstrap/providers.php
fi

# Remove from bootstrap/app.php
if [ -f "bootstrap/app.php" ]; then
    echo "Cleaning bootstrap/app.php..."
    cp bootstrap/app.php bootstrap/app.php.backup.$(date +%s)
    sed -i '/Collision/d' bootstrap/app.php
    sed -i '/NunoMaduro/d' bootstrap/app.php
fi

echo ""
echo "📋 Step 3: Clean composer.json completely..."
cp composer.json composer.json.backup.$(date +%s)
php -r "
\$composer = json_decode(file_get_contents('composer.json'), true);
unset(\$composer['require']['nunomaduro/collision']);
unset(\$composer['require-dev']['nunomaduro/collision']);
if (isset(\$composer['scripts']['post-autoload-dump'])) {
    \$composer['scripts']['post-autoload-dump'] = array_filter(
        \$composer['scripts']['post-autoload-dump'], 
        function(\$script) { 
            return strpos(\$script, 'collision') === false && strpos(\$script, 'Collision') === false; 
        }
    );
}
file_put_contents('composer.json', json_encode(\$composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
echo 'Collision completely removed from composer.json' . PHP_EOL;
"

echo ""
echo "📋 Step 4: Remove vendor completely and reinstall..."
rm -rf vendor/
rm -f composer.lock

echo ""
echo "📋 Step 5: Fresh install without collision..."
composer2 install --no-dev --no-scripts --optimize-autoloader

echo ""
echo "📋 Step 6: Generate fresh autoloader..."
composer2 dump-autoload --no-dev --optimize

echo ""
echo "📋 Step 7: Test basic PHP autoload..."
php -r "
require_once 'vendor/autoload.php';
echo 'Basic autoload works!' . PHP_EOL;
"

echo ""
echo "📋 Step 8: Test Laravel bootstrap..."
php -r "
try {
    \$_ENV['APP_ENV'] = 'production';
    require_once 'vendor/autoload.php';
    \$app = require_once 'bootstrap/app.php';
    echo 'Laravel bootstrap successful!' . PHP_EOL;
} catch (Exception \$e) {
    echo 'Bootstrap error: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "📋 Step 9: Clear Laravel caches manually..."
rm -rf bootstrap/cache/*.php
rm -rf storage/framework/cache/data/*
rm -rf storage/framework/sessions/*
rm -rf storage/framework/views/*

echo ""
echo "📋 Step 10: Run essential artisan commands..."
php artisan config:clear --quiet || echo "Config clear failed (expected)"
php artisan route:clear --quiet || echo "Route clear failed (expected)"
php artisan view:clear --quiet || echo "View clear failed (expected)"

echo ""
echo "📋 Step 11: Test artisan version..."
php artisan --version

echo ""
echo "🎯 COMPLETE COLLISION REMOVAL DONE!"
echo "===================================="
echo ""
echo "✅ All collision references should be removed"
echo "✅ Fresh composer install completed"
echo "✅ Laravel should work without collision errors"
echo ""
echo "🌐 Test: https://maknaacademy.com"
