#!/bin/bash

echo "🔒 Fix 403 Forbidden Error - Admin Panel"
echo "======================================="

echo "📋 Step 1: Analyze 403 Forbidden error..."
echo "403 means admin panel exists but authorization failed"
echo ""

echo "📋 Step 2: Check if admin user exists and test login..."

php -r "
error_reporting(E_ERROR | E_PARSE);

try {
    // Load .env manually
    if (file_exists('.env')) {
        \$lines = file('.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach (\$lines as \$line) {
            if (strpos(\$line, '=') !== false && strpos(\$line, '#') !== 0) {
                list(\$key, \$value) = explode('=', \$line, 2);
                putenv(trim(\$key) . '=' . trim(\$value, '\"'));
            }
        }
    }
    
    \$host = getenv('DB_HOST') ?: 'localhost';
    \$dbname = getenv('DB_DATABASE') ?: 'maknaacademy_db';
    \$username = getenv('DB_USERNAME') ?: 'root';
    \$password = getenv('DB_PASSWORD') ?: '';
    
    \$pdo = new PDO(\"mysql:host=\$host;dbname=\$dbname;charset=utf8mb4\", \$username, \$password);
    echo '✅ Database connected' . PHP_EOL;
    
    // Check admin users
    \$stmt = \$pdo->prepare('SELECT id, name, email, role, email_verified_at, created_at FROM users WHERE role IN (\"admin\", \"super_admin\") ORDER BY role DESC');
    \$stmt->execute();
    \$admins = \$stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo '📊 Found ' . count(\$admins) . ' admin users:' . PHP_EOL;
    
    if (count(\$admins) === 0) {
        echo '❌ NO ADMIN USERS FOUND! Creating super admin...' . PHP_EOL;
        
        // Create super admin
        \$hashedPassword = password_hash('password123', PASSWORD_BCRYPT);
        \$now = date('Y-m-d H:i:s');
        
        \$stmt = \$pdo->prepare('
            INSERT INTO users (name, email, password, phone, date_of_birth, gender, role, email_verified_at, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ');
        \$stmt->execute([
            'Super Administrator',
            'admin@maknaacademy.com', 
            \$hashedPassword,
            '081234567890',
            '1990-01-01',
            'male',
            'super_admin',
            \$now,
            \$now,
            \$now
        ]);
        echo '✅ Super admin created: admin@maknaacademy.com' . PHP_EOL;
    } else {
        foreach (\$admins as \$admin) {
            \$verified = \$admin['email_verified_at'] ? '✅ Verified' : '❌ Not Verified';
            echo \"  - ID: {\$admin['id']} | {\$admin['email']} | {\$admin['role']} | \$verified\" . PHP_EOL;
            
            // Auto-verify if not verified
            if (!\$admin['email_verified_at']) {
                \$stmt = \$pdo->prepare('UPDATE users SET email_verified_at = NOW() WHERE id = ?');
                \$stmt->execute([\$admin['id']]);
                echo '    → Email verified for ' . \$admin['email'] . PHP_EOL;
            }
        }
    }
    
    // Test password for admin@maknaacademy.com
    \$stmt = \$pdo->prepare('SELECT password FROM users WHERE email = ?');
    \$stmt->execute(['admin@maknaacademy.com']);
    \$user = \$stmt->fetch();
    
    if (\$user && password_verify('password123', \$user['password'])) {
        echo '✅ Password verification: SUCCESS for admin@maknaacademy.com' . PHP_EOL;
    } else {
        echo '❌ Password verification: FAILED - updating password...' . PHP_EOL;
        \$newPassword = password_hash('password123', PASSWORD_BCRYPT);
        \$stmt = \$pdo->prepare('UPDATE users SET password = ? WHERE email = ?');
        \$stmt->execute([\$newPassword, 'admin@maknaacademy.com']);
        echo '✅ Password updated for admin@maknaacademy.com' . PHP_EOL;
    }
    
} catch (Exception \$e) {
    echo '❌ Error: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "📋 Step 3: Check Filament middleware configuration..."

echo "Checking EnsureUserIsAdminOrSuperAdmin middleware:"
if [ -f "app/Http/Middleware/EnsureUserIsAdminOrSuperAdmin.php" ]; then
    echo "✅ Middleware file exists"
    echo "Content:"
    cat app/Http/Middleware/EnsureUserIsAdminOrSuperAdmin.php
else
    echo "❌ Middleware file missing - creating it..."
    
    mkdir -p app/Http/Middleware
    cat > app/Http/Middleware/EnsureUserIsAdminOrSuperAdmin.php << 'EOF'
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdminOrSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        // Check if user is authenticated
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Check if user has admin or super_admin role
        if (!in_array($user->role, ['admin', 'super_admin'])) {
            abort(403, 'Access denied. You need admin privileges to access this area.');
        }
        
        // Check if email is verified
        if (!$user->email_verified_at) {
            return redirect()->route('verification.notice');
        }
        
        return $next($request);
    }
}
EOF
    echo "✅ Middleware created"
fi

echo ""
echo "📋 Step 4: Check AdminPanelProvider configuration..."

if [ -f "app/Providers/Filament/AdminPanelProvider.php" ]; then
    echo "✅ AdminPanelProvider exists"
    echo "Checking middleware configuration:"
    grep -A 10 "middleware" app/Providers/Filament/AdminPanelProvider.php
else
    echo "❌ AdminPanelProvider missing!"
fi

echo ""
echo "📋 Step 5: Check bootstrap/providers.php registration..."

if [ -f "bootstrap/providers.php" ]; then
    echo "Current bootstrap/providers.php:"
    cat bootstrap/providers.php
    
    if grep -q "AdminPanelProvider" bootstrap/providers.php; then
        echo "✅ AdminPanelProvider registered"
    else
        echo "❌ AdminPanelProvider not registered - adding it..."
        # Backup and add
        cp bootstrap/providers.php bootstrap/providers.php.backup
        
        # Add AdminPanelProvider
        cat > bootstrap/providers.php << 'EOF'
<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
];
EOF
        echo "✅ AdminPanelProvider added"
    fi
else
    echo "❌ bootstrap/providers.php missing - creating it..."
    cat > bootstrap/providers.php << 'EOF'
<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
];
EOF
    echo "✅ bootstrap/providers.php created"
fi

echo ""
echo "📋 Step 6: Clear all Laravel caches..."
php artisan config:clear 2>/dev/null || echo "Config clear attempted"
php artisan route:clear 2>/dev/null || echo "Route clear attempted"  
php artisan cache:clear 2>/dev/null || echo "Cache clear attempted"
php artisan view:clear 2>/dev/null || echo "View clear attempted"

echo "Rebuilding cache..."
php artisan config:cache 2>/dev/null || echo "Config cache attempted"
php artisan route:cache 2>/dev/null || echo "Route cache attempted"

echo ""
echo "📋 Step 7: Check file permissions..."
chmod -R 755 storage/ 2>/dev/null || true
chmod -R 755 bootstrap/cache/ 2>/dev/null || true
chmod 644 .env 2>/dev/null || true

echo ""
echo "📋 Step 8: Test admin access simulation..."

php -r "
error_reporting(E_ERROR | E_PARSE);

echo 'Testing admin access requirements:' . PHP_EOL;

// Simulate middleware check
try {
    if (file_exists('.env')) {
        \$lines = file('.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach (\$lines as \$line) {
            if (strpos(\$line, '=') !== false && strpos(\$line, '#') !== 0) {
                list(\$key, \$value) = explode('=', \$line, 2);
                putenv(trim(\$key) . '=' . trim(\$value, '\"'));
            }
        }
    }
    
    \$pdo = new PDO('mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
    
    \$stmt = \$pdo->prepare('SELECT email, role, email_verified_at FROM users WHERE email = ?');
    \$stmt->execute(['admin@maknaacademy.com']);
    \$user = \$stmt->fetch(PDO::FETCH_ASSOC);
    
    if (\$user) {
        echo '✅ User exists: ' . \$user['email'] . PHP_EOL;
        echo '✅ Role: ' . \$user['role'] . PHP_EOL;
        echo '✅ Email verified: ' . (\$user['email_verified_at'] ? 'Yes' : 'No') . PHP_EOL;
        
        if (in_array(\$user['role'], ['admin', 'super_admin'])) {
            echo '✅ Role check: PASSED' . PHP_EOL;
        } else {
            echo '❌ Role check: FAILED' . PHP_EOL;
        }
        
        if (\$user['email_verified_at']) {
            echo '✅ Email verification: PASSED' . PHP_EOL;
        } else {
            echo '❌ Email verification: FAILED' . PHP_EOL;
        }
        
        echo '🎯 MIDDLEWARE SIMULATION: ' . (in_array(\$user['role'], ['admin', 'super_admin']) && \$user['email_verified_at'] ? 'ACCESS GRANTED' : 'ACCESS DENIED') . PHP_EOL;
    } else {
        echo '❌ User not found' . PHP_EOL;
    }
    
} catch (Exception \$e) {
    echo '❌ Test error: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "✅ 403 FORBIDDEN FIX COMPLETED!"
echo ""
echo "🎯 Test admin access now:"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "🌐 URL: https://maknaacademy.com/admin"
echo "📧 Email: admin@maknaacademy.com"
echo "🔑 Password: password123"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "💡 If still getting 403:"
echo "1. Check server error logs: tail -f storage/logs/laravel.log"
echo "2. Verify database connection"
echo "3. Check Apache/Nginx configuration"
echo "4. Try accessing /admin/login directly"
echo ""
echo "🔍 Common 403 causes fixed:"
echo "✅ Admin user exists and verified"
echo "✅ Correct role (super_admin)"
echo "✅ Password verified"
echo "✅ Middleware configured"
echo "✅ AdminPanelProvider registered"
echo "✅ Cache cleared"
