#!/bin/bash

echo "🔧 Fix Admin Access - Server Hosting Focus"
echo "========================================"

echo "📋 Step 1: Run service bindings fix first..."
echo "This will fix the 'files' service container error"
if [ -f "fix-service-bindings.sh" ]; then
    echo "Running service bindings fix..."
    ./fix-service-bindings.sh
else
    echo "Creating basic service bindings fix..."
    composer dump-autoload --optimize
    php artisan config:clear
    php artisan route:clear
    php artisan cache:clear
fi

echo ""
echo "📋 Step 2: Check and run database seeder..."
echo "Creating/verifying admin users in database:"

# Check if super admin exists and create if needed
php artisan tinker --execute="
\$user = App\Models\User::where('email', 'admin@maknaacademy.com')->first();
if (!\$user) {
    echo 'Creating super admin user...' . PHP_EOL;
    App\Models\User::create([
        'name' => 'Super Administrator',
        'email' => 'admin@maknaacademy.com',
        'password' => bcrypt('password123'),
        'phone' => '081234567890',
        'date_of_birth' => '1990-01-01',
        'gender' => 'male',
        'role' => 'super_admin',
        'email_verified_at' => now()
    ]);
    echo 'Super admin created successfully!' . PHP_EOL;
} else {
    echo 'Super admin already exists: ' . \$user->email . ' (Role: ' . \$user->role . ')' . PHP_EOL;
    if (!\$user->email_verified_at) {
        \$user->update(['email_verified_at' => now()]);
        echo 'Email verified for admin user' . PHP_EOL;
    }
}
"

echo ""
echo "📋 Step 3: Run UserSeeder to ensure all admin users exist..."
php artisan db:seed --class=UserSeeder --force

echo ""
echo "📋 Step 4: Clear all Laravel caches..."
php artisan config:clear
php artisan route:clear  
php artisan view:clear
php artisan cache:clear

echo ""
echo "📋 Step 5: Rebuild Laravel cache..."
php artisan config:cache
php artisan route:cache

echo ""
echo "📋 Step 6: Verify admin users in database..."
php artisan tinker --execute="
\$admins = App\Models\User::whereIn('role', ['admin', 'super_admin'])->get();
echo 'Admin users found: ' . \$admins->count() . PHP_EOL;
foreach (\$admins as \$admin) {
    echo '- ' . \$admin->email . ' (' . \$admin->role . ') - Verified: ' . (\$admin->email_verified_at ? 'Yes' : 'No') . PHP_EOL;
}
"

echo ""
echo "📋 Step 7: Test admin access simulation..."
echo "Checking admin login credentials:"
php artisan tinker --execute="
\$email = 'admin@maknaacademy.com';
\$password = 'password123';
\$user = App\Models\User::where('email', \$email)->first();
if (\$user && Hash::check(\$password, \$user->password)) {
    echo '✅ Password verification: SUCCESS' . PHP_EOL;
    echo '✅ User role: ' . \$user->role . PHP_EOL;
    echo '✅ Email verified: ' . (\$user->email_verified_at ? 'Yes' : 'No') . PHP_EOL;
    if (in_array(\$user->role, ['admin', 'super_admin'])) {
        echo '✅ Admin access: ALLOWED' . PHP_EOL;
    } else {
        echo '❌ Admin access: DENIED (wrong role)' . PHP_EOL;
    }
} else {
    echo '❌ Login check: FAILED' . PHP_EOL;
}
"

echo ""
echo "📋 Step 8: Check Filament admin panel..."
echo "Verifying AdminPanelProvider is registered:"
if grep -q "AdminPanelProvider" bootstrap/providers.php; then
    echo "✅ AdminPanelProvider registered"
else
    echo "❌ AdminPanelProvider missing - adding it..."
    # Add AdminPanelProvider if missing
    sed -i.bak 's/App\\Providers\\AppServiceProvider::class,/App\\Providers\\AppServiceProvider::class,\n    App\\Providers\\Filament\\AdminPanelProvider::class,/' bootstrap/providers.php
    echo "✅ AdminPanelProvider added"
fi

echo ""
echo "📋 Step 9: Set proper file permissions..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod 644 .env

echo ""
echo "✅ Admin access fix completed!"
echo ""
echo "🔄 Now test admin access:"
echo "1. Go to: https://maknaacademy.com/admin"
echo "2. Login with:"
echo "   📧 Email: admin@maknaacademy.com"
echo "   🔑 Password: password123"
echo ""
echo "🔍 If still having issues:"
echo "- Check server error logs"
echo "- Verify database connection"
echo "- Ensure all files are uploaded properly"
echo "- Check .env file configuration"
