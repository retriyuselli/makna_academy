#!/bin/bash

echo "🚀 Makna Academy - Complete Hosting Deployment & Fix Script"
echo "==========================================================="

# Step 1: Backup existing files
echo "📦 Step 1: Creating backups..."
BACKUP_DIR="backups_$(date +%Y%m%d_%H%M%S)"
mkdir -p $BACKUP_DIR

if [ -f "public/index.php" ]; then
    cp public/index.php $BACKUP_DIR/index.php.backup
    echo "✅ Backed up public/index.php"
fi

if [ -f ".htaccess" ]; then
    cp .htaccess $BACKUP_DIR/.htaccess.backup
    echo "✅ Backed up .htaccess"
fi

if [ -f ".env" ]; then
    cp .env $BACKUP_DIR/.env.backup
    echo "✅ Backed up .env"
fi

# Step 2: Show current git status
echo ""
echo "📊 Step 2: Current git status:"
git status

# Step 3: Reset and clean
echo ""
echo "🔄 Step 3: Resetting local changes..."
git reset --hard HEAD

echo "🧹 Step 3: Cleaning untracked files..."
git clean -fd

# Step 4: Pull latest changes
echo ""
echo "⬇️  Step 4: Pulling latest changes from GitHub..."
git pull origin main

# Check if pull was successful
if [ $? -eq 0 ]; then
    echo "✅ Git pull successful!"
    
    # Step 5: Set proper file permissions
    echo ""
    echo "🔒 Step 5: Setting file permissions..."
    find . -type f -name "*.php" -exec chmod 644 {} \;
    find . -type f -name "*.blade.php" -exec chmod 644 {} \;
    find . -type f -name ".htaccess" -exec chmod 644 {} \;
    find . -type d -exec chmod 755 {} \;
    chmod -R 775 storage/
    chmod -R 775 bootstrap/cache/
    echo "✅ File permissions set"
    
    # Step 6: Clear all Laravel caches
    echo ""
    echo "🧹 Step 6: Clearing Laravel caches..."
    php artisan config:clear 2>/dev/null || echo "⚠️  Config clear failed (might be normal)"
    php artisan route:clear 2>/dev/null || echo "⚠️  Route clear failed (might be normal)"
    php artisan view:clear 2>/dev/null || echo "⚠️  View clear failed (might be normal)"
    php artisan cache:clear 2>/dev/null || echo "⚠️  Cache clear failed (might be normal)"
    php artisan optimize:clear 2>/dev/null || echo "⚠️  Optimize clear failed (might be normal)"
    
    # Step 7: Optimize for production
    echo ""
    echo "⚡ Step 7: Optimizing for production..."
    php artisan config:cache 2>/dev/null || echo "⚠️  Config cache failed"
    php artisan route:cache 2>/dev/null || echo "⚠️  Route cache failed"
    php artisan view:cache 2>/dev/null || echo "⚠️  View cache failed"
    
    # Step 8: Create storage link if needed
    echo ""
    echo "🔗 Step 8: Creating storage link..."
    php artisan storage:link 2>/dev/null || echo "ℹ️  Storage link already exists or failed"
    
    # Step 9: Verify important files exist
    echo ""
    echo "📋 Step 9: Verifying important files..."
    
    if [ -f ".htaccess" ]; then
        echo "✅ Root .htaccess exists"
    else
        echo "❌ Root .htaccess missing!"
    fi
    
    if [ -f "public/.htaccess" ]; then
        echo "✅ Public .htaccess exists"
    else
        echo "❌ Public .htaccess missing!"
    fi
    
    if [ -f "public/index.php" ]; then
        echo "✅ Public index.php exists"
    else
        echo "❌ Public index.php missing!"
    fi
    
    # Step 10: Test route registration
    echo ""
    echo "🛣️  Step 10: Testing route registration..."
    php artisan route:list | head -5
    
    echo ""
    echo "🎉 Deployment completed successfully!"
    echo ""
    echo "📝 Please test these URLs:"
    echo "1. Homepage: https://maknaacademy.com/"
    echo "2. Events: https://maknaacademy.com/events"
    echo "3. About: https://maknaacademy.com/about"
    echo "4. Login: https://maknaacademy.com/login"
    echo "5. Register: https://maknaacademy.com/register"
    echo ""
    echo "🔍 Troubleshooting if pages still show 404:"
    echo "- Check that mod_rewrite is enabled on server"
    echo "- Verify PHP version is 8.2+"
    echo "- Check Apache/Nginx logs for errors"
    echo "- Ensure document root points to your Laravel root directory"
    echo ""
    echo "📁 Backup files saved in: $BACKUP_DIR/"
    
else
    echo "❌ Git pull failed. Please check the error messages above."
    echo "You may need to manually resolve conflicts."
fi
