#!/bin/bash

echo "🔍 Get Complete Laravel Error - Makna Academy"
echo "============================================="

echo "📋 Step 1: Check Laravel log size and recent entries..."
if [ -f "storage/logs/laravel.log" ]; then
    echo "Laravel log size: $(wc -l storage/logs/laravel.log | awk '{print $1}') lines"
    echo "Last modified: $(stat -c %y storage/logs/laravel.log 2>/dev/null || stat -f %Sm storage/logs/laravel.log)"
else
    echo "❌ Laravel log not found"
fi

echo ""
echo "📊 Step 2: Get recent error with full context..."
echo "Looking for recent errors with full message:"
tail -50 storage/logs/laravel.log 2>/dev/null | grep -A 10 -B 10 "Exception\|Error:" | tail -30

echo ""
echo "🔍 Step 3: Search for specific auth errors..."
echo "Auth-related errors:"
grep -i "auth\|login\|register" storage/logs/laravel.log 2>/dev/null | tail -5

echo ""
echo "🧪 Step 4: Force an auth error to see current issue..."
echo "Testing login controller with error catching:"
php -r "
require 'vendor/autoload.php';
\$app = require 'bootstrap/app.php';

// Boot application
\$app->boot();

try {
    echo 'Testing AuthenticatedSessionController...\n';
    \$controller = new \App\Http\Controllers\Auth\AuthenticatedSessionController();
    
    // Try to call create method
    \$response = \$controller->create();
    echo '✅ Login controller works - this is unexpected!\n';
    echo 'Response type: ' . get_class(\$response) . '\n';
    
} catch (Throwable \$e) {
    echo '❌ LOGIN ERROR FOUND:\n';
    echo 'Error: ' . \$e->getMessage() . '\n';
    echo 'File: ' . \$e->getFile() . ':' . \$e->getLine() . '\n';
    echo 'Type: ' . get_class(\$e) . '\n';
    
    // Get the actual error trace
    echo '\nStack trace (first 5 lines):\n';
    \$trace = \$e->getTrace();
    for (\$i = 0; \$i < min(5, count(\$trace)); \$i++) {
        \$t = \$trace[\$i];
        echo '#' . \$i . ' ' . (\$t['file'] ?? 'unknown') . '(' . (\$t['line'] ?? 'unknown') . '): ';
        echo (\$t['class'] ?? '') . (\$t['type'] ?? '') . (\$t['function'] ?? '') . '()\n';
    }
}
"

echo ""
echo "🧪 Step 5: Test register controller..."
php -r "
require 'vendor/autoload.php';
\$app = require 'bootstrap/app.php';
\$app->boot();

try {
    echo 'Testing RegisteredUserController...\n';
    \$controller = new \App\Http\Controllers\Auth\RegisteredUserController();
    \$response = \$controller->create();
    echo '✅ Register controller works - this is unexpected!\n';
    
} catch (Throwable \$e) {
    echo '❌ REGISTER ERROR FOUND:\n';
    echo 'Error: ' . \$e->getMessage() . '\n';
    echo 'File: ' . \$e->getFile() . ':' . \$e->getLine() . '\n';
}
"

echo ""
echo "🔍 Step 6: Check view compilation..."
echo "Testing auth view compilation:"
php -r "
require 'vendor/autoload.php';
\$app = require 'bootstrap/app.php';

try {
    // Test login view
    \$loginView = view('auth.login');
    echo '✅ Login view compiles OK\n';
} catch (Throwable \$e) {
    echo '❌ Login view error: ' . \$e->getMessage() . '\n';
}

try {
    // Test register view  
    \$registerView = view('auth.register');
    echo '✅ Register view compiles OK\n';
} catch (Throwable \$e) {
    echo '❌ Register view error: ' . \$e->getMessage() . '\n';
}
"

echo ""
echo "📋 Step 7: Test direct HTTP request simulation..."
echo "Simulating HTTP request to login route:"
php -r "
require 'vendor/autoload.php';
\$app = require 'bootstrap/app.php';

try {
    // Create a fake HTTP request
    \$request = \Illuminate\Http\Request::create('/login', 'GET');
    
    // Process through Laravel
    \$response = \$app->handle(\$request);
    echo '✅ Login route handles request - Status: ' . \$response->getStatusCode() . '\n';
    
} catch (Throwable \$e) {
    echo '❌ LOGIN ROUTE ERROR:\n';
    echo 'Error: ' . \$e->getMessage() . '\n';
    echo 'This is likely the exact 500 error cause!\n';
}
"

echo ""
echo "✅ Complete error diagnosis finished!"
echo ""
echo "🔍 Look for specific error messages above"
echo "The controller test should reveal the exact cause of 500 error"
