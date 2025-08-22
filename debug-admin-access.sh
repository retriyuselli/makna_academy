#!/bin/bash

echo "🔍 DEBUG ADMIN ACCESS ISSUE"
echo "==========================="
echo ""

echo "📋 Step 1: Check user authentication and roles..."
php -r "
require_once 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo 'Checking super_admin users...' . PHP_EOL;
    \$superAdmins = \App\Models\User::where('role', 'super_admin')->get();
    echo 'Super admin users found: ' . \$superAdmins->count() . PHP_EOL;
    
    foreach (\$superAdmins as \$user) {
        echo '- ID: ' . \$user->id . ', Email: ' . \$user->email . ', Role: ' . \$user->role . PHP_EOL;
        echo '  isAdmin(): ' . (\$user->isAdmin() ? 'true' : 'false') . PHP_EOL;
        echo '  isSuperAdmin(): ' . (\$user->isSuperAdmin() ? 'true' : 'false') . PHP_EOL;
        
        // Test canAccessPanel
        try {
            \$panel = new \Filament\Panel('admin');
            echo '  canAccessPanel(): ' . (\$user->canAccessPanel(\$panel) ? 'true' : 'false') . PHP_EOL;
        } catch (Exception \$e) {
            echo '  canAccessPanel(): Error - ' . \$e->getMessage() . PHP_EOL;
        }
        echo PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Error: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "📋 Step 2: Check if user is logged in (session)..."
php -r "
\$sessionPath = 'storage/framework/sessions';
if (is_dir(\$sessionPath)) {
    \$sessions = glob(\$sessionPath . '/*');
    echo 'Active sessions: ' . count(\$sessions) . PHP_EOL;
} else {
    echo 'Session directory not found' . PHP_EOL;
}
"

echo ""
echo "📋 Step 3: Test direct route access..."
php artisan route:list | grep admin

echo ""
echo "📋 Step 4: Check middleware registration..."
php -r "
\$middleware = file_get_contents('app/Http/Middleware/EnsureUserIsAdminOrSuperAdmin.php');
if (strpos(\$middleware, 'super_admin') !== false) {
    echo '✅ Middleware includes super_admin role' . PHP_EOL;
} else {
    echo '❌ Middleware missing super_admin role' . PHP_EOL;
}
"

echo ""
echo "📋 Step 5: Clear all auth-related caches..."
php artisan auth:clear-resets --quiet || echo "Auth clear failed"
php artisan config:clear --quiet
php artisan route:clear --quiet
php artisan view:clear --quiet
php artisan cache:clear --quiet

echo ""
echo "📋 Step 6: Regenerate optimized files..."
php artisan config:cache --quiet
php artisan route:cache --quiet

echo ""
echo "📋 Step 7: Create test super_admin user if none exists..."
php -r "
require_once 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    \$user = \App\Models\User::where('email', 'admin@maknaacademy.com')->first();
    if (\$user) {
        if (\$user->role !== 'super_admin') {
            \$user->role = 'super_admin';
            \$user->save();
            echo '✅ Updated admin@maknaacademy.com to super_admin role' . PHP_EOL;
        } else {
            echo '✅ admin@maknaacademy.com already has super_admin role' . PHP_EOL;
        }
    } else {
        echo '❌ admin@maknaacademy.com user not found' . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Error: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "🎯 DEBUG COMPLETED!"
echo "=================="
echo ""
echo "📋 Next steps:"
echo "1. Login: https://maknaacademy.com/login"
echo "2. Use: admin@maknaacademy.com / password123"
echo "3. Access: https://maknaacademy.com/admin"
echo ""
echo "If still 403, the issue might be session-related."
