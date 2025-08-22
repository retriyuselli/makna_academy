#!/bin/bash

echo "🚀 DEPLOY FILAMENT SHIELD TO HOSTINGER SERVER"
echo "=============================================="
echo ""
echo "🏠 HOSTINGER-SPECIFIC COMMANDS (using composer2)"
echo ""

echo "📋 Step 1: Stash local changes..."
git stash push -m "Backup local changes before Shield deployment"

echo ""
echo "📋 Step 2: Pull latest changes from GitHub..."
git pull origin main

echo ""
echo "📋 Step 3: Remove composer.lock (force regenerate for PHP 8.2)..."
rm -f composer.lock

echo ""
echo "📋 Step 4: Install dependencies with composer2..."
composer2 install --no-dev --optimize-autoloader

echo ""
echo "📋 Step 5: Run database migrations..."
php artisan migrate --force

echo ""
echo "📋 Step 6: Clear all Laravel caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

echo ""
echo "📋 Step 7: Fix file permissions..."
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
    } else {
        echo '❌ Spatie Permission: NOT FOUND' . PHP_EOL;
    }
    
    if (class_exists('BezhanSalleh\\FilamentShield\\FilamentShieldPlugin')) {
        echo '✅ Filament Shield: OK' . PHP_EOL;
    } else {
        echo '❌ Filament Shield: NOT FOUND' . PHP_EOL;
    }
    
    use App\\Models\\User;
    \$user = User::first();
    if (\$user && method_exists(\$user, 'hasRole')) {
        echo '✅ User HasRoles trait: OK' . PHP_EOL;
    } else {
        echo '❌ User HasRoles trait: NOT WORKING' . PHP_EOL;
    }
    
} catch (Exception \$e) {
    echo '❌ Error: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "🎯 HOSTINGER SHIELD DEPLOYMENT COMPLETED!"
echo "=========================================="
echo ""
echo "✅ Test admin panel now:"
echo "URL: https://maknaacademy.com/admin"
echo "Login: admin@maknaacademy.com / password123"
echo ""
echo "🛡️ Shield Features Available:"
echo "- Role Management: /admin/shield/roles"
echo "- User Role Assignment: /admin/shield/users"
echo "- Resource Permissions: Auto-managed"
echo ""
echo "📋 If admin panel shows 403 error:"
echo "Run: ./quick-403-fix.sh"
echo ""
echo "📋 If composer2 fails, try:"
echo "composer2 update --no-dev --optimize-autoloader"
echo ""
echo "🚀 SUCCESS INDICATORS:"
echo "✅ Admin panel loads without errors"
echo "✅ Shield menu items visible"
echo "✅ Can manage roles & permissions"
echo "✅ User authentication working"
