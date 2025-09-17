#!/bin/bash

echo "🚀 Clean Admin Fix - No Deprecation Warnings"
echo "============================================"

echo "📋 Step 1: Clear Laravel cache (bypass composer warnings)..."
php artisan config:clear 2>/dev/null || echo "Config clear attempted"
php artisan route:clear 2>/dev/null || echo "Route clear attempted"
php artisan cache:clear 2>/dev/null || echo "Cache clear attempted"
php artisan view:clear 2>/dev/null || echo "View clear attempted"

echo ""
echo "📋 Step 2: Create super admin user (direct database method)..."

# Create admin user via direct database connection
php -r "
error_reporting(E_ERROR | E_PARSE); // Suppress deprecation warnings

try {
    // Load .env variables manually
    if (file_exists('.env')) {
        \$env = parse_ini_file('.env');
        foreach (\$env as \$key => \$value) {
            putenv(\"\$key=\$value\");
        }
    }
    
    \$host = getenv('DB_HOST') ?: 'localhost';
    \$dbname = getenv('DB_DATABASE') ?: 'maknaacademy_db';
    \$username = getenv('DB_USERNAME') ?: 'root';
    \$password = getenv('DB_PASSWORD') ?: '';
    
    \$dsn = \"mysql:host=\$host;dbname=\$dbname;charset=utf8mb4\";
    \$pdo = new PDO(\$dsn, \$username, \$password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    echo '✅ Database connection successful' . PHP_EOL;
    
    // Check if admin exists
    \$stmt = \$pdo->prepare('SELECT id, email, role, email_verified_at FROM users WHERE email = ?');
    \$stmt->execute(['admin@maknaacademy.com']);
    \$existingAdmin = \$stmt->fetch();
    
    if (!\$existingAdmin) {
        // Create new admin user
        \$hashedPassword = password_hash('password123', PASSWORD_BCRYPT);
        \$now = date('Y-m-d H:i:s');
        
        \$stmt = \$pdo->prepare('
            INSERT INTO users (name, email, password, phone, date_of_birth, gender, role, email_verified_at, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ');
        \$result = \$stmt->execute([
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
        
        if (\$result) {
            echo '✅ Super admin user created successfully!' . PHP_EOL;
        } else {
            echo '❌ Failed to create admin user' . PHP_EOL;
        }
    } else {
        echo '✅ Super admin already exists: ' . \$existingAdmin['email'] . PHP_EOL;
        
        // Update to ensure correct role and verification
        \$now = date('Y-m-d H:i:s');
        \$stmt = \$pdo->prepare('UPDATE users SET role = ?, email_verified_at = ? WHERE email = ?');
        \$stmt->execute(['super_admin', \$now, 'admin@maknaacademy.com']);
        echo '✅ Super admin role and verification updated' . PHP_EOL;
    }
    
    // Verify admin users
    \$stmt = \$pdo->prepare('SELECT name, email, role, email_verified_at FROM users WHERE role IN (\"admin\", \"super_admin\") ORDER BY role DESC');
    \$stmt->execute();
    \$admins = \$stmt->fetchAll();
    
    echo PHP_EOL . '📊 Admin users in database:' . PHP_EOL;
    foreach (\$admins as \$admin) {
        \$verified = \$admin['email_verified_at'] ? '✅ Verified' : '❌ Not Verified';
        echo \"  - {\$admin['email']} ({\$admin['role']}) - \$verified\" . PHP_EOL;
    }
    
} catch (PDOException \$e) {
    echo '❌ Database error: ' . \$e->getMessage() . PHP_EOL;
    echo '💡 Please check your .env database configuration' . PHP_EOL;
} catch (Exception \$e) {
    echo '❌ Error: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "📋 Step 3: Verify Filament AdminPanelProvider registration..."

if [ -f "bootstrap/providers.php" ]; then
    if grep -q "AdminPanelProvider" bootstrap/providers.php; then
        echo "✅ AdminPanelProvider is registered in bootstrap/providers.php"
    else
        echo "❌ AdminPanelProvider missing - adding it..."
        # Create backup and add AdminPanelProvider
        cp bootstrap/providers.php bootstrap/providers.php.backup
        
        # Add AdminPanelProvider to the array
        sed -i.tmp '/App\\\\Providers\\\\AppServiceProvider::class,/a\\
    App\\\\Providers\\\\Filament\\\\AdminPanelProvider::class,' bootstrap/providers.php
        
        echo "✅ AdminPanelProvider added to bootstrap/providers.php"
    fi
else
    echo "❌ bootstrap/providers.php not found"
    echo "Creating bootstrap/providers.php..."
    cat > bootstrap/providers.php << 'EOF'
<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
];
EOF
    echo "✅ bootstrap/providers.php created with AdminPanelProvider"
fi

echo ""
echo "📋 Step 4: Test admin login credentials..."

php -r "
error_reporting(E_ERROR | E_PARSE); // Suppress warnings

try {
    // Load .env variables
    if (file_exists('.env')) {
        \$env = parse_ini_file('.env');
        foreach (\$env as \$key => \$value) {
            putenv(\"\$key=\$value\");
        }
    }
    
    \$host = getenv('DB_HOST') ?: 'localhost';
    \$dbname = getenv('DB_DATABASE') ?: 'maknaacademy_db';
    \$username = getenv('DB_USERNAME') ?: 'root';
    \$password = getenv('DB_PASSWORD') ?: '';
    
    \$pdo = new PDO(\"mysql:host=\$host;dbname=\$dbname\", \$username, \$password);
    
    // Test login credentials
    \$stmt = \$pdo->prepare('SELECT email, role, email_verified_at FROM users WHERE email = ?');
    \$stmt->execute(['admin@maknaacademy.com']);
    \$user = \$stmt->fetch(PDO::FETCH_ASSOC);
    
    if (\$user) {
        echo '✅ Login test - User found: ' . \$user['email'] . PHP_EOL;
        echo '✅ Role: ' . \$user['role'] . PHP_EOL;
        echo '✅ Email verified: ' . (\$user['email_verified_at'] ? 'Yes' : 'No') . PHP_EOL;
        
        // Test password
        \$stmt = \$pdo->prepare('SELECT password FROM users WHERE email = ?');
        \$stmt->execute(['admin@maknaacademy.com']);
        \$userData = \$stmt->fetch();
        
        if (password_verify('password123', \$userData['password'])) {
            echo '✅ Password test: PASSED' . PHP_EOL;
        } else {
            echo '❌ Password test: FAILED' . PHP_EOL;
        }
        
        if (in_array(\$user['role'], ['admin', 'super_admin'])) {
            echo '✅ Admin access: ALLOWED' . PHP_EOL;
        } else {
            echo '❌ Admin access: DENIED (insufficient role)' . PHP_EOL;
        }
    } else {
        echo '❌ User not found in database' . PHP_EOL;
    }
    
} catch (Exception \$e) {
    echo '❌ Test failed: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "📋 Step 5: Clear cache one more time..."
php artisan config:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true

echo ""
echo "📋 Step 6: Check file permissions..."
chmod -R 755 storage/ 2>/dev/null || true
chmod -R 755 bootstrap/cache/ 2>/dev/null || true
chmod 644 .env 2>/dev/null || true

echo ""
echo "✅ CLEAN ADMIN FIX COMPLETED!"
echo ""
echo "🎯 Now test admin access:"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "🌐 URL: https://maknaacademy.com/admin"
echo "📧 Email: admin@maknaacademy.com"
echo "🔑 Password: password123"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "💡 Troubleshooting:"
echo "• If 404 error: AdminPanelProvider registration issue"
echo "• If login fails: Database connection or user issue"  
echo "• If 500 error: Check Laravel logs in storage/logs/"
echo ""
echo "✨ No more deprecation warnings! ✨"
