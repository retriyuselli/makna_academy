#!/bin/bash

echo "🔧 SIMPLE TEST - Manual Steps"
echo "============================="
echo ""

echo "📋 Step 1: Test basic website access..."
curl -I https://maknaacademy.com || echo "Website not responding"

echo ""
echo "📋 Step 2: Test login page access..."
curl -I https://maknaacademy.com/login || echo "Login page not accessible"

echo ""
echo "📋 Step 3: Check current user in database..."
php -r "
require_once 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

\$user = \App\Models\User::where('email', 'admin@maknaacademy.com')->first();
if (\$user) {
    echo 'Admin user found:' . PHP_EOL;
    echo '- Email: ' . \$user->email . PHP_EOL;
    echo '- Role: ' . \$user->role . PHP_EOL;
    echo '- ID: ' . \$user->id . PHP_EOL;
    echo '- Created: ' . \$user->created_at . PHP_EOL;
} else {
    echo 'Creating admin user...' . PHP_EOL;
    \$user = \App\Models\User::create([
        'name' => 'Super Admin',
        'email' => 'admin@maknaacademy.com',
        'password' => \Hash::make('password123'),
        'role' => 'super_admin',
        'email_verified_at' => now(),
    ]);
    echo 'Admin user created with ID: ' . \$user->id . PHP_EOL;
}
"

echo ""
echo "📋 Step 4: Check route registration..."
php artisan route:list --name=filament.admin

echo ""
echo "📋 Step 5: Clear minimal cache..."
php artisan config:clear
php artisan route:clear

echo ""
echo "🎯 SIMPLE TEST COMPLETED"
echo "========================"
echo ""
echo "📋 Manual test steps:"
echo "1. Open browser: https://maknaacademy.com"
echo "2. Go to: https://maknaacademy.com/login"  
echo "3. Login: admin@maknaacademy.com / password123"
echo "4. Try: https://maknaacademy.com/admin"
echo ""
echo "📋 If still 403, try ultimate fix script"
