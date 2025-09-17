#!/bin/bash

echo "🛡️ FILAMENT SHIELD - ASSIGN ADMIN PERMISSIONS"
echo "=============================================="
echo ""

echo "📋 Step 1: Assign all permissions to super_admin role..."
php -r "
require_once 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->boot();

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

// Get super_admin role
\$superAdminRole = Role::where('name', 'super_admin')->first();
if (\$superAdminRole) {
    // Get all permissions
    \$permissions = Permission::all();
    \$superAdminRole->syncPermissions(\$permissions);
    echo '✅ Super admin role assigned all permissions (' . \$permissions->count() . ' permissions)' . PHP_EOL;
} else {
    echo '❌ Super admin role not found' . PHP_EOL;
}
"

echo ""
echo "📋 Step 2: Assign basic permissions to admin role..."
php -r "
require_once 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->boot();

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

// Get admin role
\$adminRole = Role::where('name', 'admin')->first();
if (\$adminRole) {
    // Define admin permissions (basic CRUD for most resources)
    \$adminPermissions = [
        // User management
        'view_any_user', 'view_user', 'create_user', 'update_user',
        
        // Event management
        'view_any_event', 'view_event', 'create_event', 'update_event', 'delete_event',
        
        // Event Category management
        'view_any_event::category', 'view_event::category', 'create_event::category', 'update_event::category',
        
        // Event Registration management
        'view_any_event::registration', 'view_event::registration', 'update_event::registration',
        
        // Company management
        'view_any_company', 'view_company', 'create_company', 'update_company',
    ];
    
    \$permissions = Permission::whereIn('name', \$adminPermissions)->get();
    \$adminRole->syncPermissions(\$permissions);
    echo '✅ Admin role assigned basic permissions (' . \$permissions->count() . ' permissions)' . PHP_EOL;
} else {
    echo '❌ Admin role not found' . PHP_EOL;
}
"

echo ""
echo "📋 Step 3: Create panel_user role for basic admin access..."
php -r "
require_once 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->boot();

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

// Create panel_user role
\$panelUserRole = Role::firstOrCreate(['name' => 'panel_user']);

// Define basic panel permissions
\$basicPermissions = [
    'view_any_user', 'view_user',
    'view_any_event', 'view_event',
    'view_any_event::registration', 'view_event::registration',
];

\$permissions = Permission::whereIn('name', \$basicPermissions)->get();
\$panelUserRole->syncPermissions(\$permissions);
echo '✅ Panel user role created with basic permissions (' . \$permissions->count() . ' permissions)' . PHP_EOL;
"

echo ""
echo "📋 Step 4: Display current role assignments..."
php -r "
require_once 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->boot();

use App\Models\User;

echo 'Current admin users and their roles:' . PHP_EOL;
\$adminUsers = User::whereIn('role', ['super_admin', 'admin'])->get();
foreach (\$adminUsers as \$user) {
    \$shieldRoles = \$user->roles->pluck('name')->implode(', ');
    echo '- ' . \$user->email . ' (Traditional: ' . \$user->role . ', Shield: ' . (\$shieldRoles ?: 'none') . ')' . PHP_EOL;
}
"

echo ""
echo "📋 Step 5: Test admin panel access..."
echo "Testing if admin users can access panel:"
php -r "
require_once 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->boot();

use App\Models\User;

\$adminUsers = User::whereIn('role', ['super_admin', 'admin'])->limit(3)->get();
foreach (\$adminUsers as \$user) {
    \$canAccess = \$user->canAccessPanel(null);
    echo '- ' . \$user->email . ': ' . (\$canAccess ? '✅ Can access' : '❌ Cannot access') . PHP_EOL;
}
"

echo ""
echo "🎯 SHIELD INTEGRATION COMPLETE!"
echo "==============================="
echo ""
echo "✅ Features enabled:"
echo "   • Hybrid role system (traditional + Shield)"
echo "   • Granular permissions per resource"
echo "   • Admin panel with Shield resources"
echo "   • Super admin with all permissions"
echo "   • Regular admin with limited permissions"
echo ""
echo "🔗 Access URLs:"
echo "   • Admin Panel: https://maknaacademy.com/admin"
echo "   • Role Management: https://maknaacademy.com/admin/shield/roles"
echo "   • Permission Management: Auto-managed by Shield"
echo ""
echo "📱 Login Credentials:"
echo "   • Super Admin: admin@maknaacademy.com / password123"
echo "   • Regular Admin: ahmad.wijaya@maknaacademy.com / password123"
echo ""
echo "⚡ Next Steps:"
echo "   1. Test admin panel access"
echo "   2. Configure resource permissions via GUI"
echo "   3. Create custom roles if needed"
echo "   4. Deploy to production"
