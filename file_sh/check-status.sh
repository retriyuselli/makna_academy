#!/bin/bash

echo "🔍 SERVER STATUS CHECK - Makna Academy"
echo "====================================="

echo "📍 Current location:"
pwd

echo ""
echo "👤 Current user:"
whoami

echo ""
echo "📁 Directory contents:"
ls -la | head -10

echo ""
echo "🌐 Public directory:"
ls -la public/ | head -5

echo ""
echo "📄 Critical files check:"
echo -n "public/index.php: "
[ -f "public/index.php" ] && echo "✅ EXISTS" || echo "❌ MISSING"

echo -n ".htaccess: "
[ -f ".htaccess" ] && echo "✅ EXISTS" || echo "❌ MISSING"

echo -n "public/.htaccess: "
[ -f "public/.htaccess" ] && echo "✅ EXISTS" || echo "❌ MISSING"

echo -n ".env: "
[ -f ".env" ] && echo "✅ EXISTS" || echo "❌ MISSING"

echo -n "vendor/autoload.php: "
[ -f "vendor/autoload.php" ] && echo "✅ EXISTS" || echo "❌ MISSING"

echo ""
echo "🔒 Permissions check:"
echo "storage/: $(ls -ld storage/ | awk '{print $1}')"
echo "bootstrap/cache/: $(ls -ld bootstrap/cache/ | awk '{print $1}')"
echo "public/: $(ls -ld public/ | awk '{print $1}')"

echo ""
echo "🧪 PHP test:"
php --version | head -1

echo ""
echo "📊 Disk space:"
df -h . | tail -1

echo ""
echo "🔍 Recent errors (if any):"
tail -5 storage/logs/laravel.log 2>/dev/null || echo "No Laravel log found"

echo ""
echo "✅ Status check completed!"
echo ""
echo "📝 If website still down, check:"
echo "1. Apache/Nginx server status"
echo "2. Domain DNS settings"
echo "3. Hosting panel error logs"
