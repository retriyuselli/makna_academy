#!/bin/bash

echo "🔍 Laravel Breeze Installation Check - Makna Academy"
echo "==================================================="

echo "📋 Step 1: Check if Breeze is in composer.json..."
echo "Checking require section:"
grep -A 20 '"require"' composer.json | grep breeze || echo "❌ Breeze not in require section"

echo ""
echo "Checking require-dev section:"
grep -A 20 '"require-dev"' composer.json | grep breeze || echo "❌ Breeze not in require-dev section"

echo ""
echo "📦 Step 2: Check if Breeze is actually installed..."
composer show laravel/breeze 2>/dev/null && echo "✅ Breeze is installed" || echo "❌ Breeze is NOT installed"

echo ""
echo "📁 Step 3: Check vendor directory..."
echo -n "Breeze in vendor: "
[ -d "vendor/laravel/breeze" ] && echo "✅ EXISTS" || echo "❌ MISSING"

echo ""
echo "🔍 Step 4: Check for Breeze service provider..."
grep -r "BreezeServiceProvider" config/ bootstrap/ 2>/dev/null || echo "No BreezeServiceProvider references found"

echo ""
echo "📋 Step 5: List all Laravel packages..."
echo "Laravel packages currently installed:"
composer show | grep laravel/ | head -10

echo ""
echo "🧪 Step 6: Test if Breeze classes exist..."
php -r "
require 'vendor/autoload.php';

\$breezeClasses = [
    'Laravel\Breeze\BreezeServiceProvider',
    'Laravel\Breeze\Console\InstallCommand'
];

foreach (\$breezeClasses as \$class) {
    if (class_exists(\$class)) {
        echo '✅ ' . \$class . ' exists\n';
    } else {
        echo '❌ ' . \$class . ' missing\n';
    }
}
"

echo ""
echo "📊 Step 7: Production vs Development check..."
echo "Current environment:"
grep "APP_ENV=" .env 2>/dev/null || echo "Cannot determine environment"

echo ""
echo "💡 Step 8: Installation recommendation..."
echo ""
if composer show laravel/breeze >/dev/null 2>&1; then
    echo "✅ BREEZE IS INSTALLED"
    echo "The auth issues are likely not related to missing Breeze."
    echo "Check controller errors or view compilation issues instead."
else
    echo "❌ BREEZE IS NOT INSTALLED"
    echo ""
    echo "🔧 To install Breeze, run:"
    echo "composer require laravel/breeze"
    echo ""
    echo "⚠️ Note: This might be the root cause of auth 500 errors"
    echo "if your controllers depend on Breeze components."
fi

echo ""
echo "✅ Breeze check completed!"
