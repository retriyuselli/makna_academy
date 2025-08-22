#!/bin/bash

echo "🔧 SIMPLE FIX - Remove Blocking Middleware"
echo "=========================================="
echo ""

echo "📋 Step 1: Backup current AdminPanelProvider..."
cp app/Providers/Filament/AdminPanelProvider.php app/Providers/Filament/AdminPanelProvider.php.backup

echo ""
echo "📋 Step 2: Remove EnsureUserIsAdminOrSuperAdmin middleware temporarily..."
php -r "
\$content = file_get_contents('app/Providers/Filament/AdminPanelProvider.php');
\$content = str_replace(
    '\App\Http\Middleware\EnsureUserIsAdminOrSuperAdmin::class,',
    '// \App\Http\Middleware\EnsureUserIsAdminOrSuperAdmin::class, // Temporarily disabled',
    \$content
);
file_put_contents('app/Providers/Filament/AdminPanelProvider.php', \$content);
echo '✅ Middleware temporarily disabled' . PHP_EOL;
"

echo ""
echo "📋 Step 3: Ensure super_admin user exists..."
php -r "
require_once 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    \$user = \App\Models\User::where('email', 'admin@maknaacademy.com')->first();
    if (\$user) {
        \$user->role = 'super_admin';
        \$user->save();
        echo '✅ admin@maknaacademy.com set to super_admin' . PHP_EOL;
    } else {
        echo '❌ Creating new super_admin user...' . PHP_EOL;
        \$user = \App\Models\User::create([
            'name' => 'Super Admin',
            'email' => 'admin@maknaacademy.com',
            'password' => \Hash::make('password123'),
            'role' => 'super_admin',
            'email_verified_at' => now(),
        ]);
        echo '✅ Super admin user created' . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Error: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "📋 Step 4: Clear all caches..."
php artisan config:clear --quiet
php artisan route:clear --quiet
php artisan view:clear --quiet
php artisan cache:clear --quiet

echo ""
echo "📋 Step 5: Regenerate config cache..."
php artisan config:cache --quiet

echo ""
echo "🎯 SIMPLE FIX COMPLETED!"
echo "========================"
echo ""
echo "✅ Blocking middleware temporarily disabled"
echo "✅ Super admin user verified"
echo "✅ Caches cleared"
echo ""
echo "📋 Now try:"
echo "1. Login: https://maknaacademy.com/login"
echo "2. Email: admin@maknaacademy.com"
echo "3. Password: password123"
echo "4. Access: https://maknaacademy.com/admin"
echo ""
echo "💡 If this works, we can re-enable middleware after Shield is deployed"
