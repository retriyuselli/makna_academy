#!/bin/bash

echo "🔧 MANUAL MIDDLEWARE DISABLE"
echo "============================"
echo ""

echo "📋 Disabling EnsureUserIsAdminOrSuperAdmin middleware manually..."

# Backup file
cp app/Providers/Filament/AdminPanelProvider.php app/Providers/Filament/AdminPanelProvider.php.backup

# Comment out the middleware line
sed -i.bak 's/\\\App\\\Http\\\Middleware\\\EnsureUserIsAdminOrSuperAdmin::class,/\/\/ \\\App\\\Http\\\Middleware\\\EnsureUserIsAdminOrSuperAdmin::class, \/\/ TEMPORARILY DISABLED/' app/Providers/Filament/AdminPanelProvider.php

echo "✅ Middleware commented out"

echo ""
echo "📋 Clearing caches..."
php artisan config:clear --quiet
php artisan route:clear --quiet

echo ""
echo "📋 Regenerating cache..."
php artisan config:cache --quiet

echo ""
echo "🎯 MIDDLEWARE DISABLED!"
echo "======================"
echo ""
echo "Now try accessing: https://maknaacademy.com/admin"
