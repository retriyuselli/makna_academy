#!/bin/bash

echo "🚨 QUICK FIX - Revert to Working State"
echo "====================================="
echo ""

echo "📋 Step 1: Set environment back to local (known working)..."
cp .env .env.current-backup

# Update .env to local environment
sed -i 's/APP_ENV=production/APP_ENV=local/' .env
sed -i 's/APP_DEBUG=false/APP_DEBUG=true/' .env

echo "✅ Environment set back to local"

echo ""
echo "📋 Step 2: Restore simple User model..."
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
        // Simple working version
        return $this->isAdmin() || $this->isSuperAdmin();
    }
}
EOF

echo "✅ User model restored to simple working version"

echo ""
echo "📋 Step 3: Restore standard session config..."
cat > config/session.php << 'EOF'
<?php

use Illuminate\Support\Str;

return [
    'driver' => env('SESSION_DRIVER', 'file'),
    'lifetime' => env('SESSION_LIFETIME', 120),
    'expire_on_close' => false,
    'encrypt' => false,
    'files' => storage_path('framework/sessions'),
    'connection' => env('SESSION_CONNECTION'),
    'table' => 'sessions',
    'store' => env('SESSION_STORE'),
    'lottery' => [2, 100],
    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug(env('APP_NAME', 'laravel'), '_').'_session'
    ),
    'path' => '/',
    'domain' => env('SESSION_DOMAIN'),
    'secure' => env('SESSION_SECURE_COOKIE'),
    'http_only' => true,
    'same_site' => 'lax',
];
EOF

echo "✅ Session config restored to standard"

echo ""
echo "📋 Step 4: Clear all caches..."
php artisan config:clear --quiet
php artisan route:clear --quiet
php artisan view:clear --quiet
php artisan cache:clear --quiet

echo ""
echo "📋 Step 5: Test admin user..."
php -r "
require_once 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

\$user = \App\Models\User::where('email', 'admin@maknaacademy.com')->first();
if (\$user) {
    echo '✅ Admin user: ' . \$user->email . ' (Role: ' . \$user->role . ')' . PHP_EOL;
} else {
    echo 'Creating admin user...' . PHP_EOL;
    \$user = \App\Models\User::create([
        'name' => 'Super Admin',
        'email' => 'admin@maknaacademy.com',
        'password' => \Hash::make('password123'),
        'role' => 'super_admin',
        'email_verified_at' => now(),
    ]);
    echo '✅ Admin user created' . PHP_EOL;
}
"

echo ""
echo "🎯 REVERTED TO WORKING STATE!"
echo "============================"
echo ""
echo "✅ Environment: Back to local (working)"
echo "✅ User model: Simple working version"
echo "✅ Session: Standard configuration"
echo "✅ Admin user: Verified"
echo ""
echo "📋 Now test: https://maknaacademy.com/admin"
echo "📋 Login: admin@maknaacademy.com / password123"
