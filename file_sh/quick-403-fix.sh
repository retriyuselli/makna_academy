#!/bin/bash

echo "🔍 Quick 403 Diagnosis & Fix"
echo "============================"

echo "📋 Testing admin user requirements..."

php -r "
error_reporting(E_ERROR | E_PARSE);

// Load environment
if (file_exists('.env')) {
    \$lines = file('.env');
    foreach (\$lines as \$line) {
        if (trim(\$line) && strpos(\$line, '=') && strpos(\$line, '#') !== 0) {
            putenv(trim(\$line));
        }
    }
}

try {
    \$pdo = new PDO('mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
    
    // Check if admin exists
    \$stmt = \$pdo->prepare('SELECT * FROM users WHERE email = ?');
    \$stmt->execute(['admin@maknaacademy.com']);
    \$admin = \$stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!\$admin) {
        echo '❌ Admin user not found - creating...' . PHP_EOL;
        \$hash = password_hash('password123', PASSWORD_BCRYPT);
        \$now = date('Y-m-d H:i:s');
        \$stmt = \$pdo->prepare('INSERT INTO users (name, email, password, role, email_verified_at, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)');
        \$stmt->execute(['Super Admin', 'admin@maknaacademy.com', \$hash, 'super_admin', \$now, \$now, \$now]);
        echo '✅ Admin user created' . PHP_EOL;
    } else {
        echo '✅ Admin user exists' . PHP_EOL;
        echo 'Role: ' . \$admin['role'] . PHP_EOL;
        echo 'Email verified: ' . (\$admin['email_verified_at'] ? 'Yes' : 'No') . PHP_EOL;
        
        // Fix role and verification if needed
        if (\$admin['role'] !== 'super_admin' || !\$admin['email_verified_at']) {
            \$stmt = \$pdo->prepare('UPDATE users SET role = ?, email_verified_at = NOW() WHERE email = ?');
            \$stmt->execute(['super_admin', 'admin@maknaacademy.com']);
            echo '✅ Admin user updated (role: super_admin, verified: yes)' . PHP_EOL;
        }
        
        // Test password
        if (password_verify('password123', \$admin['password'])) {
            echo '✅ Password correct' . PHP_EOL;
        } else {
            echo '❌ Password wrong - fixing...' . PHP_EOL;
            \$newHash = password_hash('password123', PASSWORD_BCRYPT);
            \$stmt = \$pdo->prepare('UPDATE users SET password = ? WHERE email = ?');
            \$stmt->execute([\$newHash, 'admin@maknaacademy.com']);
            echo '✅ Password updated' . PHP_EOL;
        }
    }
    
} catch (Exception \$e) {
    echo 'Database error: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "📋 Checking AdminPanelProvider..."

if grep -q "AdminPanelProvider" bootstrap/providers.php 2>/dev/null; then
    echo "✅ AdminPanelProvider registered"
else
    echo "❌ Adding AdminPanelProvider..."
    cat > bootstrap/providers.php << 'EOF'
<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
];
EOF
    echo "✅ AdminPanelProvider added"
fi

echo ""
echo "📋 Clearing cache..."
php artisan config:clear 2>/dev/null
php artisan route:clear 2>/dev/null  
php artisan cache:clear 2>/dev/null

echo ""
echo "✅ Quick fix completed!"
echo ""
echo "🎯 Test now: https://maknaacademy.com/admin"
echo "📧 admin@maknaacademy.com / password123"
echo ""
echo "💡 If still 403, the issue might be:"
echo "1. Web server configuration (Apache/Nginx)"
echo "2. File permissions on storage/app directories"
echo "3. Filament version compatibility"
echo "4. PHP version requirements"
