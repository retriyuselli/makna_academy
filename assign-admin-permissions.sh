#!/bin/bash

echo "🛡️ ASSIGN ADMIN PERMISSIONS WITH FILAMENT SHIELD"
echo "================================================"
echo ""

echo "📋 Step 1: Check current Shield setup..."
php -r "
require_once 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->boot();

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

echo '📊 Current Roles:' . PHP_EOL;
foreach (Role::all() as \$role) {
    echo '- ' . \$role->name . ' (permissions: ' . \$role->permissions->count() . ')' . PHP_EOL;
}

echo PHP_EOL . '📊 Current Permissions:' . PHP_EOL;
\$permissions = Permission::all();
echo 'Total permissions: ' . \$permissions->count() . PHP_EOL;
"

echo ""
echo "📋 Step 2: Assign all permissions to super_admin role..."
php -r "
require_once 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->boot();

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

\$superAdminRole = Role::findByName('super_admin');
\$allPermissions = Permission::all();

// Give super admin ALL permissions
\$superAdminRole->syncPermissions(\$allPermissions);

echo '✅ Super Admin role now has ' . \$superAdminRole->permissions->count() . ' permissions' . PHP_EOL;
"

echo ""
echo "📋 Step 3: Assign key permissions to admin role..."
php -r "
require_once 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->boot();

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

\$adminRole = Role::findByName('admin');

// Define admin permissions (selective permissions for regular admins)
\$adminPermissions = [
    // User management
    'view_any_user', 'view_user', 'create_user', 'update_user',
    
    // Event management
    'view_any_event', 'view_event', 'create_event', 'update_event', 'delete_event',
    
    // Event category management
    'view_any_event::category', 'view_event::category', 'create_event::category', 'update_event::category',
    
    // Event registration management
    'view_any_event::registration', 'view_event::registration', 'update_event::registration',
    
    // Company management
    'view_any_company', 'view_company', 'create_company', 'update_company',
];

\$permissions = Permission::whereIn('name', \$adminPermissions)->get();
\$adminRole->syncPermissions(\$permissions);

echo '✅ Admin role now has ' . \$adminRole->permissions->count() . ' permissions' . PHP_EOL;
echo 'Permissions assigned:' . PHP_EOL;
foreach (\$adminRole->permissions as \$permission) {
    echo '  - ' . \$permission->name . PHP_EOL;
}
"

echo ""
echo "📋 Step 4: Verify admin users have correct roles..."
php -r "
require_once 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->boot();

use App\Models\User;

\$adminUsers = User::whereIn('role', ['admin', 'super_admin'])->get();

echo '📊 Admin Users Status:' . PHP_EOL;
foreach (\$adminUsers as \$user) {
    echo '- ' . \$user->email . ' (role: ' . \$user->role . ')' . PHP_EOL;
    echo '  Shield roles: ' . \$user->roles->pluck('name')->join(', ') . PHP_EOL;
    echo '  Permissions: ' . \$user->getPermissionsViaRoles()->count() . PHP_EOL;
    echo PHP_EOL;
}
"

echo ""
echo "📋 Step 5: Test admin panel access simulation..."
php -r "
require_once 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->boot();

use App\Models\User;

\$user = User::where('email', 'admin@maknaacademy.com')->first();
if (\$user) {
    echo '✅ Testing canAccessPanel for admin@maknaacademy.com...' . PHP_EOL;
    
    // Test traditional access
    \$traditional = \$user->isAdmin() || \$user->isSuperAdmin();
    echo '  Traditional access: ' . (\$traditional ? '✅ YES' : '❌ NO') . PHP_EOL;
    
    // Test Shield access
    \$shield = \$user->hasRole(['admin', 'super_admin', 'panel_user']);
    echo '  Shield access: ' . (\$shield ? '✅ YES' : '❌ NO') . PHP_EOL;
    
    // Test specific permissions
    echo '  Can view users: ' . (\$user->can('view_any_user') ? '✅ YES' : '❌ NO') . PHP_EOL;
    echo '  Can create events: ' . (\$user->can('create_event') ? '✅ YES' : '❌ NO') . PHP_EOL;
} else {
    echo '❌ Admin user not found' . PHP_EOL;
}
"

echo ""
echo "🎯 SHIELD IMPLEMENTATION COMPLETED!"
echo "=================================="
echo ""
echo "✅ What's been implemented:"
echo "- Filament Shield installed and configured"
echo "- All existing roles synced with Shield"
echo "- Permissions generated for all resources"
echo "- Super admin role with all permissions"
echo "- Admin role with selective permissions"
echo "- Hybrid approach: traditional + Shield access"
echo ""
echo "📱 Admin Panel Access:"
echo "- URL: https://maknaacademy.com/admin"
echo "- Super Admin: admin@maknaacademy.com"
echo "- Regular Admins: ahmad.wijaya@maknaacademy.com, etc."
echo ""
echo "🛡️ Shield Resources Available:"
echo "- /admin/shield/roles - Manage roles"
echo "- /admin/shield/users - Assign roles to users"
echo ""
echo "🚀 Next: Deploy to server and test admin access!"
