#!/bin/bash

echo "🔍 Auth Controller Error Diagnosis - Makna Academy"
echo "================================================="

echo "📋 Routes are registered correctly ✅"
echo "Now testing individual controllers..."

echo ""
echo "🧪 Step 1: Test AuthenticatedSessionController (login)..."
php -r "
require 'vendor/autoload.php';
\$app = require 'bootstrap/app.php';

try {
    \$controller = new App\Http\Controllers\Auth\AuthenticatedSessionController();
    echo '✅ AuthenticatedSessionController can be instantiated\n';
    
    // Test if create method exists
    if (method_exists(\$controller, 'create')) {
        echo '✅ create method exists\n';
    } else {
        echo '❌ create method missing\n';
    }
} catch (Exception \$e) {
    echo '❌ AuthenticatedSessionController error: ' . \$e->getMessage() . '\n';
    echo 'File: ' . \$e->getFile() . ':' . \$e->getLine() . '\n';
}
"

echo ""
echo "🧪 Step 2: Test RegisteredUserController (register)..."
php -r "
require 'vendor/autoload.php';
\$app = require 'bootstrap/app.php';

try {
    \$controller = new App\Http\Controllers\Auth\RegisteredUserController();
    echo '✅ RegisteredUserController can be instantiated\n';
    
    // Test if create method exists
    if (method_exists(\$controller, 'create')) {
        echo '✅ create method exists\n';
    } else {
        echo '❌ create method missing\n';
    }
} catch (Exception \$e) {
    echo '❌ RegisteredUserController error: ' . \$e->getMessage() . '\n';
    echo 'File: ' . \$e->getFile() . ':' . \$e->getLine() . '\n';
}
"

echo ""
echo "🔍 Step 3: Check auth views..."
echo -n "Login view (auth/login.blade.php): "
[ -f "resources/views/auth/login.blade.php" ] && echo "✅ EXISTS" || echo "❌ MISSING"

echo -n "Register view (auth/register.blade.php): "
[ -f "resources/views/auth/register.blade.php" ] && echo "✅ EXISTS" || echo "❌ MISSING"

echo ""
echo "🔍 Step 4: Test view compilation..."
echo "Testing login view compilation:"
php artisan view:clear 2>/dev/null
php -r "
require 'vendor/autoload.php';
\$app = require 'bootstrap/app.php';

try {
    \$view = view('auth.login');
    echo '✅ Login view can be compiled\n';
} catch (Exception \$e) {
    echo '❌ Login view error: ' . \$e->getMessage() . '\n';
}
"

echo ""
echo "Testing register view compilation:"
php -r "
require 'vendor/autoload.php';
\$app = require 'bootstrap/app.php';

try {
    \$view = view('auth.register');
    echo '✅ Register view can be compiled\n';
} catch (Exception \$e) {
    echo '❌ Register view error: ' . \$e->getMessage() . '\n';
}
"

echo ""
echo "🔍 Step 5: Check dependencies..."
echo "Testing if required classes exist:"
php -r "
require 'vendor/autoload.php';

\$classes = [
    'App\Models\User',
    'App\Models\Company',
    'App\Http\Requests\Auth\LoginRequest',
    'Illuminate\Http\Request',
    'Illuminate\Support\Facades\Auth'
];

foreach (\$classes as \$class) {
    if (class_exists(\$class)) {
        echo '✅ ' . \$class . '\n';
    } else {
        echo '❌ ' . \$class . ' MISSING\n';
    }
}
"

echo ""
echo "🧪 Step 6: Test direct URL access..."
echo "Testing login URL:"
curl -s -I https://maknaacademy.com/login 2>/dev/null | head -1 || echo "❌ Cannot test login URL"

echo "Testing register URL:"
curl -s -I https://maknaacademy.com/register 2>/dev/null | head -1 || echo "❌ Cannot test register URL"

echo ""
echo "📋 Step 7: Check recent Laravel errors..."
echo "Last 10 lines from Laravel log:"
tail -10 storage/logs/laravel.log 2>/dev/null || echo "No Laravel log found"

echo ""
echo "✅ Controller diagnosis completed!"
echo ""
echo "📝 Next steps based on results:"
echo "- If controllers fail: Missing dependencies or syntax error"
echo "- If views fail: View compilation error or missing layout"
echo "- If HTTP 500: Check Laravel log for specific error"
