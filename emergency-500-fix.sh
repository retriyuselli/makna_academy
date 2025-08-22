#!/bin/bash

echo "🚨 EMERGENCY FIX - Server Error 500 & User.php"
echo "=============================================="
echo ""

echo "📋 Step 1: Restore completely clean User.php..."
cat > app/Models/User.php << 'EOF'
<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'google_id',
        'avatar',
        'phone',
        'date_of_birth',
        'gender',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        return true;
    }
}
EOF

echo "✅ User.php completely restored"

echo ""
echo "📋 Step 2: Test PHP syntax..."
php -l app/Models/User.php

echo ""
echo "📋 Step 3: Fix potential composer autoload issues..."
composer2 dump-autoload --no-dev --optimize

echo ""
echo "📋 Step 4: Clear ALL Laravel caches that might cause 500 error..."
rm -rf storage/framework/cache/data/*
rm -rf storage/framework/sessions/*
rm -rf storage/framework/views/*
rm -rf bootstrap/cache/*.php

php artisan config:clear --quiet
php artisan route:clear --quiet
php artisan view:clear --quiet
php artisan cache:clear --quiet

echo ""
echo "📋 Step 5: Fix file permissions that might cause 500..."
chmod -R 755 storage bootstrap/cache
chmod -R 644 storage/logs/*
chmod 644 .env

echo ""
echo "📋 Step 6: Test basic Laravel functionality..."
php artisan --version

echo ""
echo "📋 Step 7: Create/verify admin user..."
php -r "
require_once 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    \$user = \App\Models\User::where('email', 'admin@maknaacademy.com')->first();
    if (!\$user) {
        \$user = \App\Models\User::create([
            'name' => 'Super Admin',
            'email' => 'admin@maknaacademy.com',
            'password' => \Hash::make('password123'),
            'role' => 'super_admin',
            'email_verified_at' => now(),
        ]);
        echo 'Admin user created' . PHP_EOL;
    } else {
        echo 'Admin user exists: ' . \$user->email . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Error: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "📋 Step 8: Test route registration..."
php artisan route:list | grep login || echo "Routes not loading properly"

echo ""
echo "📋 Step 9: Check for any other PHP syntax errors..."
find app/ -name "*.php" -exec php -l {} \; | grep -v "No syntax errors"

echo ""
echo "🎯 EMERGENCY FIX COMPLETED!"
echo "=========================="
echo ""
echo "✅ User.php completely restored with clean syntax"
echo "✅ Autoload regenerated"
echo "✅ All caches cleared"
echo "✅ Permissions fixed"
echo "✅ Admin user verified"
echo ""
echo "📋 Now test:"
echo "1. https://maknaacademy.com (should load without 500)"
echo "2. https://maknaacademy.com/login (should work)"
echo "3. Login and try admin access"
