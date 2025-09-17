#!/bin/bash

echo "🔧 FIX GIT PULL CONFLICT ON SERVER"
echo "=================================="
echo ""

echo "📋 Step 1: Stash local changes that conflict..."
git stash push -m "Backup local gitignore changes before Shield deployment"

echo ""
echo "📋 Step 2: Pull latest changes from GitHub..."
git pull origin main

echo ""
echo "📋 Step 3: Check what was stashed (optional)..."
echo "Stashed changes (if any):"
git stash list

echo ""
echo "📋 Step 4: Install new Composer dependencies..."
composer2 install --no-dev --optimize-autoloader

echo ""
echo "📋 Step 5: Run database migrations..."
php artisan migrate --force

echo ""
echo "📋 Step 6: Clear all caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

echo ""
echo "📋 Step 7: Fix permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache

echo ""
echo "📋 Step 8: Test Shield installation..."
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
echo "🎯 SHIELD DEPLOYMENT COMPLETED!"
echo "==============================="
echo ""
echo "✅ Test admin panel:"
echo "URL: https://maknaacademy.com/admin"
echo "Login: admin@maknaacademy.com / password123"
echo ""
echo "🛡️ Shield features should now be available!"
echo ""
echo "📋 If admin panel shows 403:"
echo "Run: ./quick-403-fix.sh"
