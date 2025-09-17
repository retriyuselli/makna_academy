#!/bin/bash

echo "🔧 Fix Service Bindings - Makna Academy"
echo "====================================="

echo "📋 Step 1: Check current providers..."
echo "Checking bootstrap/providers.php:"
if [ -f "bootstrap/providers.php" ]; then
    cat bootstrap/providers.php
else
    echo "❌ bootstrap/providers.php not found!"
fi

echo ""
echo "📋 Step 2: Check config/app.php providers..."
grep -A 20 -B 5 "providers.*=>" config/app.php

echo ""
echo "🔧 Step 3: Fix missing service providers..."
echo "Creating proper bootstrap/providers.php..."

# Create correct bootstrap/providers.php
cat > bootstrap/providers.php << 'EOF'
<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
];
EOF

echo "✅ bootstrap/providers.php created"

echo ""
echo "🔧 Step 4: Check config/app.php structure..."
# Make sure config/app.php has proper providers array
php artisan config:clear

echo ""
echo "🔧 Step 5: Regenerate autoload and clear caches..."
composer dump-autoload --optimize
php artisan clear-compiled
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

echo ""
echo "🔧 Step 6: Test service container..."
echo "Testing if 'files' service is available:"
php -r "
try {
    require_once 'vendor/autoload.php';
    \$app = require_once 'bootstrap/app.php';
    \$app->boot();
    echo 'Files service: ' . (app()->bound('files') ? '✅ Available' : '❌ Missing') . PHP_EOL;
    echo 'View service: ' . (app()->bound('view') ? '✅ Available' : '❌ Missing') . PHP_EOL;
    echo 'Filesystem service: ' . (app()->bound('filesystem') ? '✅ Available' : '❌ Missing') . PHP_EOL;
} catch (Exception \$e) {
    echo 'Error: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "🧪 Step 7: Test auth routes again..."
echo "Testing login route:"
php -r "
try {
    require_once 'vendor/autoload.php';
    \$app = require_once 'bootstrap/app.php';
    \$app->boot();
    
    // Simulate HTTP request
    \$request = Illuminate\Http\Request::create('/login', 'GET');
    \$response = \$app->handle(\$request);
    echo 'Login route status: ' . \$response->getStatusCode() . PHP_EOL;
    
    if (\$response->getStatusCode() >= 400) {
        echo 'Response content preview: ' . substr(\$response->getContent(), 0, 200) . '...' . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Auth test error: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "✅ Service binding fix completed!"
echo ""
echo "🔄 Test these URLs now:"
echo "- https://maknaacademy.com/login"
echo "- https://maknaacademy.com/register"
