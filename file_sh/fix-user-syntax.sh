#!/bin/bash

echo "🔧 FIX USER.PHP SYNTAX ERROR"
echo "============================"
echo ""

echo "📋 Step 1: Backup current User.php..."
cp app/Models/User.php app/Models/User.php.error-backup

echo ""
echo "📋 Step 2: Restore clean User.php..."
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
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
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

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
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

    /**
     * Get the registrations for the user
     */
    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        // Simple access check - allow admin and super_admin roles
        return $this->role === 'admin' || $this->role === 'super_admin';
    }
}
EOF

echo "✅ User.php restored to clean version"

echo ""
echo "📋 Step 3: Test PHP syntax..."
php -l app/Models/User.php

echo ""
echo "📋 Step 4: Clear caches..."
php artisan config:clear --quiet
php artisan route:clear --quiet

echo ""
echo "📋 Step 5: Test User model..."
php -r "
require_once 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    \$user = new \App\Models\User();
    echo '✅ User model loads successfully' . PHP_EOL;
} catch (Exception \$e) {
    echo '❌ User model error: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "🎯 USER.PHP FIXED!"
echo "=================="
echo ""
echo "✅ Clean User.php restored"
echo "✅ Syntax validated"
echo "✅ Simple canAccessPanel method"
echo ""
echo "Now try accessing admin again!"
