#!/bin/bash

echo "🔕 DISABLE COLLISION TEMPORARILY"
echo "================================"
echo ""

echo "📋 Step 1: Backup config/app.php..."
cp config/app.php config/app.php.backup

echo "📋 Step 2: Remove Collision provider from config..."
php -r "
\$config = file_get_contents('config/app.php');
\$config = preg_replace('/.*CollisionServiceProvider.*\n/', '', \$config);
\$config = preg_replace('/.*Collision.*\n/', '', \$config);
file_put_contents('config/app.php', \$config);
echo 'Collision provider removed from config' . PHP_EOL;
"

echo "📋 Step 3: Remove collision from composer.json..."
php -r "
\$composer = json_decode(file_get_contents('composer.json'), true);
unset(\$composer['require']['nunomaduro/collision']);
unset(\$composer['require-dev']['nunomaduro/collision']);
file_put_contents('composer.json', json_encode(\$composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
echo 'Collision removed from composer.json' . PHP_EOL;
"

echo "📋 Step 4: Update composer without collision..."
composer2 update --no-dev --optimize-autoloader

echo "📋 Step 5: Clear all caches..."
php artisan config:clear --quiet
php artisan route:clear --quiet
php artisan view:clear --quiet

echo "📋 Step 6: Test Laravel without collision..."
php artisan --version

echo ""
echo "🎯 COLLISION DISABLED!"
echo "====================="
echo ""
echo "✅ Laravel should work now without collision error"
echo "📋 To restore collision later: cp config/app.php.backup config/app.php"
echo ""
echo "Test: https://maknaacademy.com"
