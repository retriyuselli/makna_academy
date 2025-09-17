#!/bin/bash

echo "🔧 Makna Academy - Hosting Git Pull Fix Script"
echo "=============================================="

# Backup existing files
echo "📦 Creating backups..."
if [ -f "public/index.php" ]; then
    cp public/index.php public/index.php.backup.$(date +%Y%m%d_%H%M%S)
    echo "✅ Backed up public/index.php"
fi

if [ -f ".htaccess" ]; then
    cp .htaccess .htaccess.backup.$(date +%Y%m%d_%H%M%S)
    echo "✅ Backed up .htaccess"
fi

# Show current git status
echo ""
echo "📊 Current git status:"
git status

# Reset any local changes
echo ""
echo "🔄 Resetting local changes..."
git reset --hard HEAD

# Clean untracked files
echo "🧹 Cleaning untracked files..."
git clean -fd

# Pull latest changes
echo ""
echo "⬇️  Pulling latest changes from GitHub..."
git pull origin main

# Check if pull was successful
if [ $? -eq 0 ]; then
    echo "✅ Git pull successful!"
    
    # Clear Laravel caches
    echo ""
    echo "🧹 Clearing Laravel caches..."
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
    php artisan cache:clear
    
    # Optimize for production
    echo "⚡ Optimizing for production..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    
    # Check file permissions
    echo ""
    echo "🔒 Setting file permissions..."
    chmod 644 .htaccess
    chmod 644 public/.htaccess
    chmod 644 public/index.php
    
    echo ""
    echo "🎉 Deployment completed successfully!"
    echo ""
    echo "📝 Please test these URLs:"
    echo "- https://maknaacademy.com/"
    echo "- https://maknaacademy.com/events"
    echo "- https://maknaacademy.com/about"
    echo "- https://maknaacademy.com/login"
    echo "- https://maknaacademy.com/register"
    
else
    echo "❌ Git pull failed. Please check the error messages above."
fi
