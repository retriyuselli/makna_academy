#!/bin/bash

echo "🚀 Simple Admin Fix for Server - Makna Academy"
echo "============================================="

echo "📋 Step 1: Basic Laravel fixes..."
composer dump-autoload --optimize 2>/dev/null || echo "Composer autoload attempted"
php artisan config:clear 2>/dev/null || echo "Config clear attempted"
php artisan route:clear 2>/dev/null || echo "Route clear attempted"
php artisan cache:clear 2>/dev/null || echo "Cache clear attempted"
php artisan view:clear 2>/dev/null || echo "View clear attempted"

echo ""
echo "📋 Step 2: Create super admin user directly..."

# Create admin user via raw SQL if Laravel commands fail
echo "Creating admin user in database..."

php -r "
error_reporting(E_ERROR | E_PARSE); // Suppress deprecation warnings
try {
    require_once 'vendor/autoload.php';
    \$pdo = new PDO('mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
    
    // Check if admin exists
    \$stmt = \$pdo->prepare('SELECT id FROM users WHERE email = ?');
    \$stmt->execute(['admin@maknaacademy.com']);
    
    if (!\$stmt->fetch()) {
        // Create admin user
        \$hashedPassword = password_hash('password123', PASSWORD_DEFAULT);
        \$stmt = \$pdo->prepare('
            INSERT INTO users (name, email, password, phone, date_of_birth, gender, role, email_verified_at, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), NOW())
        ');
        \$stmt->execute([
            'Super Administrator',
            'admin@maknaacademy.com', 
            \$hashedPassword,
            '081234567890',
            '1990-01-01',
            'male',
            'super_admin'
        ]);
        echo 'Super admin user created successfully!' . PHP_EOL;
    } else {
        echo 'Super admin user already exists.' . PHP_EOL;
        
        // Update to ensure email is verified and role is correct
        \$stmt = \$pdo->prepare('UPDATE users SET role = ?, email_verified_at = NOW() WHERE email = ?');
        \$stmt->execute(['super_admin', 'admin@maknaacademy.com']);
        echo 'Super admin user updated and verified.' . PHP_EOL;
    }
    
} catch (Exception \$e) {
    echo 'Database error: ' . \$e->getMessage() . PHP_EOL;
    echo 'Please check your .env database configuration.' . PHP_EOL;
}
"

echo ""
echo "📋 Step 3: Verify admin user exists..."

# Simple check using direct SQL
php -r "
try {
    require_once 'vendor/autoload.php';
    \$pdo = new PDO('mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
    
    \$stmt = \$pdo->prepare('SELECT name, email, role, email_verified_at FROM users WHERE role IN (\"admin\", \"super_admin\")');
    \$stmt->execute();
    \$admins = \$stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo 'Admin users found: ' . count(\$admins) . PHP_EOL;
    foreach (\$admins as \$admin) {
        echo '- ' . \$admin['email'] . ' (' . \$admin['role'] . ') - Verified: ' . (\$admin['email_verified_at'] ? 'Yes' : 'No') . PHP_EOL;
    }
    
} catch (Exception \$e) {
    echo 'Error checking admin users: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "📋 Step 4: Check .env configuration..."
echo "Database config:"
grep -E "^(DB_CONNECTION|DB_HOST|DB_DATABASE|DB_USERNAME)" .env | head -4

echo ""
echo "📋 Step 5: Clear cache again..."
php artisan config:clear
php artisan route:clear

echo ""
echo "📋 Step 6: Check bootstrap/providers.php..."
if grep -q "AdminPanelProvider" bootstrap/providers.php; then
    echo "✅ AdminPanelProvider is registered"
else
    echo "❌ AdminPanelProvider missing - please add it to bootstrap/providers.php"
fi

echo ""
echo "✅ Simple admin fix completed!"
echo ""
echo "🔄 Test admin access now:"
echo "1. URL: https://maknaacademy.com/admin"
echo "2. Email: admin@maknaacademy.com"
echo "3. Password: password123"
echo ""
echo "💡 If /admin shows 404:"
echo "- AdminPanelProvider might not be registered"
echo "- Check bootstrap/providers.php"
echo "- Run: composer dump-autoload"
echo ""
echo "💡 If login fails:"
echo "- User might not exist in database"  
echo "- Check database connection in .env"
echo "- Verify password is correct"
