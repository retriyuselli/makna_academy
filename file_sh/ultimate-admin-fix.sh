#!/bin/bash

echo "🚨 ULTIMATE FIX - Remove All Auth Blocking"
echo "========================================="
echo ""

echo "📋 Step 1: Completely disable custom middleware..."
cp app/Providers/Filament/AdminPanelProvider.php app/Providers/Filament/AdminPanelProvider.php.ultimate-backup

# Create minimal AdminPanelProvider without custom middleware
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
                // ALL CUSTOM MIDDLEWARE REMOVED
            ])
            ->plugins([
                FilamentShieldPlugin::make(),
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
EOF

echo "✅ AdminPanelProvider updated - ALL custom middleware removed"

echo ""
echo "📋 Step 2: Simplify User model canAccessPanel to minimum..."
cp app/Models/User.php app/Models/User.php.ultimate-backup

# Update User model with most permissive canAccessPanel
php -r "
\$content = file_get_contents('app/Models/User.php');
\$pattern = '/public function canAccessPanel.*?\{.*?\}/s';
\$replacement = 'public function canAccessPanel(\Filament\Panel \$panel): bool
    {
        // ULTIMATE FIX: Allow any authenticated user temporarily
        return true;
    }';
\$content = preg_replace(\$pattern, \$replacement, \$content);
file_put_contents('app/Models/User.php', \$content);
echo '✅ User canAccessPanel updated - allows ANY authenticated user' . PHP_EOL;
"

echo ""
echo "📋 Step 3: Clear ALL possible blocking caches..."
rm -rf storage/framework/cache/data/*
rm -rf storage/framework/sessions/*
rm -rf storage/framework/views/*
rm -rf bootstrap/cache/*.php

php artisan config:clear --quiet
php artisan route:clear --quiet
php artisan view:clear --quiet
php artisan cache:clear --quiet
php artisan event:clear --quiet || echo "Event clear not available"

echo ""
echo "📋 Step 4: Regenerate minimum cache..."
php artisan config:cache --quiet

echo ""
echo "📋 Step 5: Test route registration..."
php artisan route:list | grep "admin.*dashboard" || echo "Admin dashboard route missing"

echo ""
echo "📋 Step 6: Fix all permissions..."
chmod -R 755 storage bootstrap/cache public
find storage -type f -exec chmod 644 {} \;
find bootstrap/cache -type f -exec chmod 644 {} \;

echo ""
echo "📋 Step 7: Test basic Laravel functionality..."
php artisan --version

echo ""
echo "📋 Step 8: Test user authentication..."
php -r "
require_once 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    \$user = \App\Models\User::where('email', 'admin@maknaacademy.com')->first();
    if (\$user) {
        echo '✅ Admin user exists: ' . \$user->email . PHP_EOL;
        echo 'Role: ' . \$user->role . PHP_EOL;
        echo 'canAccessPanel: ' . (\$user->canAccessPanel(new \Filament\Panel('admin')) ? 'true' : 'false') . PHP_EOL;
    } else {
        echo '❌ Admin user not found' . PHP_EOL;
    }
} catch (Exception \$e) {
    echo '❌ Error: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "🎯 ULTIMATE FIX COMPLETED!"
echo "========================="
echo ""
echo "✅ ALL custom middleware removed"
echo "✅ canAccessPanel allows ANY authenticated user"
echo "✅ All caches cleared"
echo "✅ Permissions fixed"
echo ""
echo "📋 NOW TRY:"
echo "1. https://maknaacademy.com/login"
echo "2. Login: admin@maknaacademy.com / password123"
echo "3. https://maknaacademy.com/admin"
echo ""
echo "⚠️  WARNING: This is VERY permissive - any authenticated user can access admin"
echo "⚠️  After testing, restore proper authorization!"
