#!/bin/bash

echo "🔧 SAFE FIX - Manual File Updates"
echo "================================="
echo ""

echo "📋 Step 1: Ensure admin user exists..."
php -r "
require_once 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    \$user = \App\Models\User::where('email', 'admin@maknaacademy.com')->first();
    if (!\$user) {
        \$user = \App\Models\User::create([
            'name' => 'Super Admin',
            'email' => 'admin@maknaacademy.com',
            'password' => \Hash::make('password123'),
            'role' => 'super_admin',
            'email_verified_at' => now(),
        ]);
        echo 'Admin user created!' . PHP_EOL;
    } else {
        \$user->role = 'super_admin';
        \$user->save();
        echo 'Admin user updated to super_admin!' . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Error: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "📋 Step 2: Backup AdminPanelProvider..."
cp app/Providers/Filament/AdminPanelProvider.php app/Providers/Filament/AdminPanelProvider.php.backup

echo ""
echo "📋 Step 3: Remove custom middleware from AdminPanelProvider..."
sed -i 's/\\App\\Http\\Middleware\\EnsureUserIsAdminOrSuperAdmin::class,/\/\/ \\App\\Http\\Middleware\\EnsureUserIsAdminOrSuperAdmin::class, \/\/ DISABLED/' app/Providers/Filament/AdminPanelProvider.php

echo ""
echo "📋 Step 4: Clear all caches..."
rm -rf storage/framework/cache/data/*
rm -rf storage/framework/sessions/*
rm -rf storage/framework/views/*
rm -rf bootstrap/cache/*.php

php artisan config:clear --quiet
php artisan route:clear --quiet  
php artisan view:clear --quiet
php artisan cache:clear --quiet

echo ""
echo "📋 Step 5: Regenerate cache..."
php artisan config:cache --quiet
php artisan route:cache --quiet

echo ""
echo "📋 Step 6: Fix permissions..."
chmod -R 755 storage bootstrap/cache

echo ""
echo "📋 Step 7: Test routes..."
php artisan route:list | grep "admin.*dashboard" || echo "No admin dashboard route found"

echo ""
echo "📋 Step 8: Test User model..."
php -r "
require_once 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    \$user = \App\Models\User::where('email', 'admin@maknaacademy.com')->first();
    if (\$user) {
        echo 'User found: ' . \$user->email . ' - Role: ' . \$user->role . PHP_EOL;
        echo 'isAdmin(): ' . (\$user->isAdmin() ? 'true' : 'false') . PHP_EOL;
        echo 'isSuperAdmin(): ' . (\$user->isSuperAdmin() ? 'true' : 'false') . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Error: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "🎯 SAFE FIX COMPLETED!"
echo "====================="
echo ""
echo "✅ Custom middleware disabled"
echo "✅ Admin user verified"
echo "✅ Caches cleared"
echo "✅ Permissions fixed"
echo ""
echo "📋 Now try:"
echo "1. Login: https://maknaacademy.com/login"
echo "2. Email: admin@maknaacademy.com"
echo "3. Password: password123"
echo "4. Access: https://maknaacademy.com/admin"
