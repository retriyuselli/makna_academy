#!/bin/bash

echo "🔍 DEBUG ADMIN ACCESS ISSUE AFTER CONFIG CHANGE"
echo "==============================================="
echo ""

echo "📋 Step 1: Check current environment settings..."
php -r "
require_once 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo 'APP_ENV: ' . env('APP_ENV') . PHP_EOL;
echo 'APP_DEBUG: ' . (env('APP_DEBUG') ? 'true' : 'false') . PHP_EOL;
echo 'APP_URL: ' . env('APP_URL') . PHP_EOL;
echo 'SESSION_DRIVER: ' . env('SESSION_DRIVER') . PHP_EOL;
echo 'SESSION_DOMAIN: ' . env('SESSION_DOMAIN') . PHP_EOL;
echo 'FILAMENT_ADMIN_ACCESS: ' . (env('FILAMENT_ADMIN_ACCESS') ? 'true' : 'false') . PHP_EOL;
"

echo ""
echo "📋 Step 2: Check admin user status..."
php -r "
require_once 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    \$user = \App\Models\User::where('email', 'admin@maknaacademy.com')->first();
    if (\$user) {
        echo 'Admin user found:' . PHP_EOL;
        echo '- Email: ' . \$user->email . PHP_EOL;
        echo '- Role: ' . \$user->role . PHP_EOL;
        echo '- isAdmin(): ' . (\$user->isAdmin() ? 'true' : 'false') . PHP_EOL;
        echo '- isSuperAdmin(): ' . (\$user->isSuperAdmin() ? 'true' : 'false') . PHP_EOL;
        
        // Test canAccessPanel
        try {
            \$panel = new \Filament\Panel('admin');
            echo '- canAccessPanel(): ' . (\$user->canAccessPanel(\$panel) ? 'true' : 'false') . PHP_EOL;
        } catch (Exception \$e) {
            echo '- canAccessPanel() error: ' . \$e->getMessage() . PHP_EOL;
        }
    } else {
        echo 'Admin user not found!' . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Error checking user: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "📋 Step 3: Check Filament routes..."
php artisan route:list | grep "filament.admin" | head -10

echo ""
echo "📋 Step 4: Test basic website access..."
curl -I https://maknaacademy.com 2>/dev/null | head -1 || echo "Website not responding"

echo ""
echo "📋 Step 5: Check for syntax errors in updated files..."
php -l app/Models/User.php
php -l config/session.php

echo ""
echo "📋 Step 6: Check if .env has conflicting settings..."
grep -E "(APP_ENV|APP_DEBUG|SESSION_|FILAMENT_)" .env | head -10

echo ""
echo "🎯 DIAGNOSIS COMPLETE"
echo "===================="
echo ""
echo "💡 Common issues after config change:"
echo "1. Environment cache not cleared"
echo "2. Conflicting .env settings"
echo "3. Session configuration issues"
echo "4. User model logic problems"
