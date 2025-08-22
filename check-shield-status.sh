#!/bin/bash

echo "🔍 CHECK FILAMENT SHIELD STATUS"
echo "==============================="
echo ""

echo "📋 Step 1: Check if Shield package is installed..."
composer2 show | grep filament-shield || echo "❌ Shield package not found"

echo ""
echo "📋 Step 2: Check Shield config file..."
if [ -f "config/filament-shield.php" ]; then
    echo "✅ Shield config exists"
    head -5 config/filament-shield.php
else
    echo "❌ Shield config missing"
fi

echo ""
echo "📋 Step 3: Check Shield migrations..."
ls -la database/migrations/ | grep shield || echo "❌ No Shield migrations found"
ls -la database/migrations/ | grep permission || echo "❌ No permission migrations found"

echo ""
echo "📋 Step 4: Check if permission tables exist..."
php -r "
require_once 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    \$tables = ['permissions', 'roles', 'model_has_permissions', 'model_has_roles', 'role_has_permissions'];
    foreach (\$tables as \$table) {
        \$exists = \Schema::hasTable(\$table);
        echo (\$exists ? '✅' : '❌') . ' Table ' . \$table . ': ' . (\$exists ? 'EXISTS' : 'MISSING') . PHP_EOL;
    }
} catch (Exception \$e) {
    echo '❌ Error checking tables: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "📋 Step 5: Check Shield plugin in AdminPanelProvider..."
if grep -q "FilamentShieldPlugin" app/Providers/Filament/AdminPanelProvider.php; then
    echo "✅ Shield plugin registered"
    grep -A2 -B2 "FilamentShieldPlugin" app/Providers/Filament/AdminPanelProvider.php
else
    echo "❌ Shield plugin not registered"
fi

echo ""
echo "📋 Step 6: Check User model HasRoles trait..."
if grep -q "HasRoles" app/Models/User.php; then
    echo "✅ User model has HasRoles trait"
    grep -A2 -B2 "HasRoles" app/Models/User.php
else
    echo "❌ User model missing HasRoles trait"
fi

echo ""
echo "📋 Step 7: Check Shield routes..."
php artisan route:list | grep shield || echo "❌ No Shield routes found"

echo ""
echo "📋 Step 8: Check if Shield resources exist..."
ls -la app/Filament/Resources/ | grep -i shield || echo "❌ No Shield resources found"

echo ""
echo "📋 Step 9: Test Shield functionality..."
php -r "
require_once 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    if (class_exists('Spatie\Permission\Models\Role')) {
        echo '✅ Spatie Permission package loaded' . PHP_EOL;
        \$roleCount = \Spatie\Permission\Models\Role::count();
        echo 'Roles in database: ' . \$roleCount . PHP_EOL;
    } else {
        echo '❌ Spatie Permission package not loaded' . PHP_EOL;
    }
} catch (Exception \$e) {
    echo '❌ Error testing Shield: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "🎯 SHIELD STATUS CHECK COMPLETED!"
echo "================================="
echo ""
echo "📋 If Shield is NOT installed, run:"
echo "chmod +x deploy-shield-hostinger.sh"
echo "./deploy-shield-hostinger.sh"
