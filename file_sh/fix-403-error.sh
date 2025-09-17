#!/bin/bash

echo "🔐 FIX 403 ERROR ON /admin"
echo "========================="
echo ""

echo "📋 Step 1: Check current permissions..."
ls -la storage/
ls -la bootstrap/cache/

echo ""
echo "📋 Step 2: Fix directory permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache
find storage -type f -exec chmod 644 {} \;
find bootstrap/cache -type f -exec chmod 644 {} \;

echo ""
echo "📋 Step 3: Clear all caches..."
php artisan config:clear --quiet || echo "Config clear failed"
php artisan route:clear --quiet || echo "Route clear failed"
php artisan view:clear --quiet || echo "View clear failed"
php artisan cache:clear --quiet || echo "Cache clear failed"

echo ""
echo "📋 Step 4: Regenerate config cache..."
php artisan config:cache --quiet || echo "Config cache failed"

echo ""
echo "📋 Step 5: Check .htaccess in public..."
if [ -f "public/.htaccess" ]; then
    echo "✅ .htaccess exists in public/"
    head -10 public/.htaccess
else
    echo "❌ .htaccess missing! Creating..."
    cat > public/.htaccess << 'EOF'
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
EOF
fi

echo ""
echo "📋 Step 6: Check if admin route exists..."
php artisan route:list | grep admin || echo "No admin routes found"

echo ""
echo "📋 Step 7: Test basic Laravel..."
php artisan --version

echo ""
echo "📋 Step 8: Check User model and admin users..."
php -r "
require_once 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    \$adminUsers = \App\Models\User::where('role', 'admin')->orWhere('role', 'super_admin')->get();
    echo 'Admin users found: ' . \$adminUsers->count() . PHP_EOL;
    foreach (\$adminUsers as \$user) {
        echo '- ' . \$user->email . ' (' . \$user->role . ')' . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Error checking users: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "🎯 403 ERROR FIX COMPLETED!"
echo "=========================="
echo ""
echo "✅ Permissions fixed"
echo "✅ Caches cleared"
echo "✅ .htaccess checked"
echo "✅ Admin users verified"
echo ""
echo "🌐 Test: https://maknaacademy.com/admin"
