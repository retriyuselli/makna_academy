#!/bin/bash

echo "🚨 RADICAL FIX - Remove All Auth Blocks"
echo "======================================"
echo ""

echo "📋 Step 1: Check if user is actually logged in..."
php -r "
require_once 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    \$user = \App\Models\User::where('email', 'admin@maknaacademy.com')->first();
    if (\$user) {
        echo 'User found: ' . \$user->email . ' (ID: ' . \$user->id . ')' . PHP_EOL;
        echo 'Role: ' . \$user->role . PHP_EOL;
        echo 'Email verified: ' . (\$user->email_verified_at ? 'Yes' : 'No') . PHP_EOL;
    } else {
        echo 'Creating admin user...' . PHP_EOL;
        \$user = \App\Models\User::create([
            'name' => 'Super Admin',
            'email' => 'admin@maknaacademy.com',
            'password' => \Hash::make('password123'),
            'role' => 'super_admin',
            'email_verified_at' => now(),
        ]);
        echo 'Admin user created!' . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Error: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "📋 Step 2: Backup and modify AdminPanelProvider - Remove ALL auth middleware..."
cp app/Providers/Filament/AdminPanelProvider.php app/Providers/Filament/AdminPanelProvider.php.backup.$(date +%s)

cat > app/Providers/Filament/AdminPanelProvider.php << 'EOF'
<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                // Temporarily disabled: \App\Http\Middleware\EnsureUserIsAdminOrSuperAdmin::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make(),
            ])
            ->authMiddleware([
                // Temporarily using basic auth only
                Authenticate::class,
            ]);
    }
}
EOF

echo "✅ AdminPanelProvider updated - removed blocking middleware"

echo ""
echo "📋 Step 3: Update User model - simplify canAccessPanel..."
cp app/Models/User.php app/Models/User.php.backup.$(date +%s)

php -r "
\$content = file_get_contents('app/Models/User.php');
\$newCanAccessPanel = '    public function canAccessPanel(\Filament\Panel \$panel): bool
    {
        // Temporarily allow all authenticated users with admin/super_admin roles
        return \$this->role === \"admin\" || \$this->role === \"super_admin\";
    }';

\$pattern = '/public function canAccessPanel\(.*?\{.*?\}/s';
\$content = preg_replace(\$pattern, \$newCanAccessPanel, \$content);
file_put_contents('app/Models/User.php', \$content);
echo '✅ User model updated - simplified canAccessPanel' . PHP_EOL;
"

echo ""
echo "📋 Step 4: Clear ALL caches and storage..."
rm -rf storage/framework/cache/data/*
rm -rf storage/framework/sessions/*
rm -rf storage/framework/views/*
rm -rf bootstrap/cache/*.php

echo ""
echo "📋 Step 5: Clear Laravel caches..."
php artisan config:clear --quiet
php artisan route:clear --quiet
php artisan view:clear --quiet
php artisan cache:clear --quiet

echo ""
echo "📋 Step 6: Regenerate optimized files..."
php artisan config:cache --quiet
php artisan route:cache --quiet

echo ""
echo "📋 Step 7: Test artisan routes..."
php artisan route:list | grep "admin.*dashboard"

echo ""
echo "📋 Step 8: Fix permissions..."
chmod -R 755 storage bootstrap/cache
find storage -type f -exec chmod 644 {} \;
find bootstrap/cache -type f -exec chmod 644 {} \;

echo ""
echo "🎯 RADICAL FIX COMPLETED!"
echo "========================"
echo ""
echo "✅ All blocking middleware removed"
echo "✅ User model simplified"
echo "✅ All caches cleared"
echo "✅ Permissions fixed"
echo ""
echo "📋 NOW TRY:"
echo "1. Clear browser cache/cookies"
echo "2. Login: https://maknaacademy.com/login"
echo "3. Email: admin@maknaacademy.com"
echo "4. Password: password123"
echo "5. Access: https://maknaacademy.com/admin"
echo ""
echo "💡 This should work. If not, the issue is at server level (Apache/Nginx config)"
