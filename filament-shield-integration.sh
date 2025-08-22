#!/bin/bash

echo "🛡️ FILAMENT SHIELD INTEGRATION GUIDE"
echo "===================================="
echo ""

echo "📋 Step 1: Install Filament Shield & Spatie Permission..."
echo "composer require bezhansalleh/filament-shield"
echo "composer require spatie/laravel-permission"
echo ""

echo "📋 Step 2: Publish and run migrations..."
echo "php artisan vendor:publish --provider=\"Spatie\\Permission\\PermissionServiceProvider\""
echo "php artisan migrate"
echo ""

echo "📋 Step 3: Install Shield..."
echo "php artisan shield:install"
echo ""

echo "📋 Step 4: Generate permissions for existing resources..."
echo "php artisan shield:generate"
echo ""

echo "📋 Step 5: Setup Shield Super Admin..."
echo "php artisan shield:super-admin"
echo ""

echo "📋 Step 6: Update User Model for Permission Integration..."
cat > temp_user_updates.php << 'EOF'
<?php
// Add to User.php

use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles;
    
    // Keep existing methods
    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->hasRole('admin');
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin' || $this->hasRole('super_admin');
    }

    // Enhanced panel access with Shield integration
    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        // Traditional role check
        $hasTraditionalAccess = $this->isAdmin() || $this->isSuperAdmin();
        
        // Shield permission check
        $hasShieldAccess = $this->hasRole(['admin', 'super_admin', 'panel_user']);
        
        return $hasTraditionalAccess || $hasShieldAccess;
    }
}
EOF

echo "✅ User model update template created: temp_user_updates.php"
echo ""

echo "📋 Step 7: Update AdminPanelProvider for Shield..."
cat > temp_admin_panel_updates.php << 'EOF'
<?php
// Update AdminPanelProvider.php

use BezhanSalleh\FilamentShield\FilamentShield;

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
                // Keep existing middleware
                \App\Http\Middleware\EnsureUserIsAdminOrSuperAdmin::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            // Add Shield plugin
            ->plugins([
                FilamentShield::make(),
            ]);
    }
}
EOF

echo "✅ AdminPanelProvider update template created: temp_admin_panel_updates.php"
echo ""

echo "📋 Step 8: Create migration to sync existing roles..."
cat > temp_sync_roles_migration.php << 'EOF'
<?php
// Create migration: php artisan make:migration sync_existing_user_roles

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;
use App\Models\User;

return new class extends Migration
{
    public function up()
    {
        // Create roles if they don't exist
        Role::firstOrCreate(['name' => 'super_admin']);
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'customer']);
        
        // Sync existing user roles
        $users = User::all();
        foreach ($users as $user) {
            if ($user->role && !$user->hasRole($user->role)) {
                $user->assignRole($user->role);
            }
        }
    }
    
    public function down()
    {
        // Remove roles if needed
    }
};
EOF

echo "✅ Role sync migration template created: temp_sync_roles_migration.php"
echo ""

echo "📋 Step 9: Update existing middleware (optional - for hybrid approach)..."
cat > temp_middleware_updates.php << 'EOF'
<?php
// Enhanced middleware with Shield integration

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdminOrSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if (!$user) {
            abort(403, 'Unauthorized.');
        }
        
        // Traditional role check
        $hasTraditionalAccess = in_array($user->role, ['admin', 'super_admin']);
        
        // Shield permission check (if Shield is enabled)
        $hasShieldAccess = false;
        if (method_exists($user, 'hasRole')) {
            $hasShieldAccess = $user->hasRole(['admin', 'super_admin']);
        }
        
        if (!$hasTraditionalAccess && !$hasShieldAccess) {
            abort(403, 'Unauthorized.');
        }
        
        return $next($request);
    }
}
EOF

echo "✅ Enhanced middleware template created: temp_middleware_updates.php"
echo ""

echo "🎯 IMPLEMENTATION STRATEGY:"
echo "=========================="
echo ""
echo "🔄 HYBRID APPROACH (Recommended):"
echo "1. Keep existing role system as primary"
echo "2. Add Shield for granular permissions"
echo "3. Use both systems in parallel"
echo "4. Gradually migrate to Shield-only if needed"
echo ""
echo "✅ BENEFITS:"
echo "- Backward compatibility maintained"
echo "- Enhanced permission management"
echo "- Resource-level security"
echo "- Future-proof architecture"
echo ""
echo "⚠️ CONSIDERATIONS:"
echo "- Additional database tables (roles, permissions, model_has_roles)"
echo "- Learning curve for permission management"
echo "- Performance overhead (minimal)"
echo ""
echo "🚀 NEXT STEPS:"
echo "1. Run installation commands above"
echo "2. Apply code updates from templates"
echo "3. Run migrations"
echo "4. Test with existing admin users"
echo "5. Configure permissions via Filament UI"
echo ""
echo "📱 ACCESS AFTER INSTALLATION:"
echo "- Shield Resources: /admin/shield/roles, /admin/shield/permissions"
echo "- Manage permissions via GUI"
echo "- Create custom permissions per resource"
echo ""
