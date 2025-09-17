#!/bin/bash

echo "🔧 Step-by-Step Auth Test - Makna Academy"
echo "========================================"

echo "📋 Routes confirmed working ✅"
echo "Now testing each component individually..."

echo ""
echo "🧪 Test 1: Basic Laravel bootstrap"
php -r "
try {
    require 'vendor/autoload.php';
    \$app = require 'bootstrap/app.php';
    echo '✅ Laravel bootstrap OK\n';
} catch (Exception \$e) {
    echo '❌ Bootstrap failed: ' . \$e->getMessage() . '\n';
    exit(1);
}
"

echo ""
echo "🧪 Test 2: Auth middleware check"
php -r "
require 'vendor/autoload.php';
\$app = require 'bootstrap/app.php';

try {
    // Check if auth middleware exists
    if (class_exists('App\Http\Middleware\SmartEmailVerification')) {
        echo '✅ SmartEmailVerification middleware exists\n';
    }
    if (class_exists('App\Http\Middleware\SmartAdminVerification')) {
        echo '✅ SmartAdminVerification middleware exists\n';
    }
} catch (Exception \$e) {
    echo '❌ Middleware check failed: ' . \$e->getMessage() . '\n';
}
"

echo ""
echo "🧪 Test 3: Auth controller direct test"
echo "Creating minimal test for AuthenticatedSessionController..."

php -r "
require 'vendor/autoload.php';
\$app = require 'bootstrap/app.php';

// Boot the application
\$app->boot();

try {
    // Create mock request
    \$request = new \Illuminate\Http\Request();
    
    // Test controller instantiation
    \$controller = new \App\Http\Controllers\Auth\AuthenticatedSessionController();
    echo '✅ AuthenticatedSessionController instantiated\n';
    
    // Test if we can call create method (this might fail due to dependencies)
    try {
        \$response = \$controller->create();
        echo '✅ Login page controller works\n';
    } catch (Exception \$e) {
        echo '❌ Login controller error: ' . \$e->getMessage() . '\n';
        echo 'This is likely the cause of 500 error\n';
    }
    
} catch (Exception \$e) {
    echo '❌ Controller test failed: ' . \$e->getMessage() . '\n';
    echo 'File: ' . \$e->getFile() . ':' . \$e->getLine() . '\n';
}
"

echo ""
echo "🧪 Test 4: Register controller test"
php -r "
require 'vendor/autoload.php';
\$app = require 'bootstrap/app.php';
\$app->boot();

try {
    \$controller = new \App\Http\Controllers\Auth\RegisteredUserController();
    echo '✅ RegisteredUserController instantiated\n';
    
    try {
        \$response = \$controller->create();
        echo '✅ Register page controller works\n';
    } catch (Exception \$e) {
        echo '❌ Register controller error: ' . \$e->getMessage() . '\n';
    }
    
} catch (Exception \$e) {
    echo '❌ Register controller test failed: ' . \$e->getMessage() . '\n';
}
"

echo ""
echo "🧪 Test 5: Company model check (used in auth views)"
php -r "
require 'vendor/autoload.php';
\$app = require 'bootstrap/app.php';

try {
    \$company = \App\Models\Company::first();
    if (\$company) {
        echo '✅ Company model works and has data\n';
    } else {
        echo '⚠️ Company model works but no data (might cause view error)\n';
    }
} catch (Exception \$e) {
    echo '❌ Company model error: ' . \$e->getMessage() . '\n';
    echo 'This could cause auth view errors\n';
}
"

echo ""
echo "✅ Step-by-step test completed!"
echo ""
echo "🔍 Check the results above to identify the specific error"
echo "Common issues:"
echo "- Controller error: Missing dependency or wrong import"
echo "- View error: Missing layout or component"
echo "- Model error: Database connection or missing table"
