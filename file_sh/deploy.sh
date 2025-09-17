#!/bin/bash

echo "🚀 Makna Academy Deployment Script"
echo "=================================="

# Clear all caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Optimize for production
echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Composer optimization
echo "📦 Optimizing composer autoload..."
composer dump-autoload --optimize --no-dev

# Storage link
echo "🔗 Creating storage link..."
php artisan storage:link

# Run deployment check
echo "✅ Running deployment check..."
php artisan deploy:check

echo "🎉 Deployment completed!"
echo ""
echo "📝 Manual checks needed:"
echo "1. Verify .htaccess exists in public folder"
echo "2. Check file permissions (755 for folders, 644 for files)"
echo "3. Verify database connection"
echo "4. Test URL: https://maknaacademy.com/events"
