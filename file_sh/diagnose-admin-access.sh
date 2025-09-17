#!/bin/bash

echo "🔍 Diagnosis Admin Access Issue - Makna Academy"
echo "=============================================="

echo "📋 Step 1: Check user database dan role..."
echo "Checking super_admin user:"
php -r "
require_once 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->boot();

use App\Models\User;

\$superAdmin = User::where('email', 'admin@maknaacademy.com')->first();
if (\$superAdmin) {
    echo 'Email: ' . \$superAdmin->email . PHP_EOL;
    echo 'Name: ' . \$superAdmin->name . PHP_EOL;
    echo 'Role: ' . \$superAdmin->role . PHP_EOL;
    echo 'Email Verified: ' . (\$superAdmin->email_verified_at ? 'Yes' : 'No') . PHP_EOL;
    echo 'Password Hash: ' . substr(\$superAdmin->password, 0, 20) . '...' . PHP_EOL;
} else {
    echo 'Super admin user not found!' . PHP_EOL;
}
"

echo ""
echo "📋 Step 2: Check all admin/super_admin users..."
php -r "
require_once 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->boot();

use App\Models\User;

\$admins = User::whereIn('role', ['admin', 'super_admin'])->get();
foreach (\$admins as \$admin) {
    echo '- ' . \$admin->email . ' (' . \$admin->role . ') - Verified: ' . (\$admin->email_verified_at ? 'Yes' : 'No') . PHP_EOL;
}
"

echo ""
echo "📋 Step 3: Check Filament admin panel configuration..."
echo "Checking AdminPanelProvider registration:"
if grep -q "AdminPanelProvider" bootstrap/providers.php; then
    echo "✅ AdminPanelProvider registered in bootstrap/providers.php"
    cat bootstrap/providers.php
else
    echo "❌ AdminPanelProvider not found in bootstrap/providers.php"
fi

echo ""
echo "📋 Step 4: Check middleware registration..."
echo "Checking EnsureUserIsAdminOrSuperAdmin middleware:"
php -r "
require_once 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->boot();

try {
    if (class_exists('App\Http\Middleware\EnsureUserIsAdminOrSuperAdmin')) {
        echo '✅ EnsureUserIsAdminOrSuperAdmin middleware exists' . PHP_EOL;
    } else {
        echo '❌ EnsureUserIsAdminOrSuperAdmin middleware not found' . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Error: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "📋 Step 5: Test admin access simulation..."
echo "Simulating admin login process:"
php -r "
require_once 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->boot();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

\$email = 'admin@maknaacademy.com';
\$password = 'password123';

\$user = User::where('email', \$email)->first();
if (\$user) {
    if (Hash::check(\$password, \$user->password)) {
        echo '✅ Password check passed' . PHP_EOL;
        
        if (in_array(\$user->role, ['admin', 'super_admin'])) {
            echo '✅ Role check passed: ' . \$user->role . PHP_EOL;
        } else {
            echo '❌ Role check failed: ' . \$user->role . PHP_EOL;
        }
        
        if (\$user->email_verified_at) {
            echo '✅ Email verified' . PHP_EOL;
        } else {
            echo '❌ Email not verified' . PHP_EOL;
        }
    } else {
        echo '❌ Password check failed' . PHP_EOL;
    }
} else {
    echo '❌ User not found' . PHP_EOL;
}
"

echo ""
echo "📋 Step 6: Check Filament route registration..."
echo "Testing if /admin route is accessible:"
php -r "
require_once 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->boot();

try {
    \$routes = collect(\$app['router']->getRoutes()->getRoutes())
        ->filter(function (\$route) {
            return str_contains(\$route->uri, 'admin');
        })
        ->map(function (\$route) {
            return \$route->uri . ' (' . implode('|', \$route->methods) . ')';
        });
    
    if (\$routes->count() > 0) {
        echo '✅ Admin routes found:' . PHP_EOL;
        foreach (\$routes as \$route) {
            echo '  - ' . \$route . PHP_EOL;
        }
    } else {
        echo '❌ No admin routes found' . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Error checking routes: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "📋 Step 7: Check database seeder status..."
echo "Checking if UserSeeder has been run:"
php -r "
require_once 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->boot();

use App\Models\User;

\$userCount = User::count();
\$adminCount = User::whereIn('role', ['admin', 'super_admin'])->count();

echo 'Total users: ' . \$userCount . PHP_EOL;
echo 'Admin/Super Admin users: ' . \$adminCount . PHP_EOL;

if (\$adminCount === 0) {
    echo '❌ No admin users found - please run seeder!' . PHP_EOL;
} else {
    echo '✅ Admin users exist' . PHP_EOL;
}
"

echo ""
echo "📋 Step 8: Suggested fixes..."
echo "If admin access fails, try these fixes:"
echo "1. Run user seeder: php artisan db:seed UserSeeder"
echo "2. Verify admin users: php artisan admin:verify-all"
echo "3. Clear cache: php artisan config:clear && php artisan route:clear"
echo "4. Check .env configuration"
echo ""
echo "🔄 Test admin access at: https://maknaacademy.com/admin"
echo "📧 Login with: admin@maknaacademy.com / password123"
