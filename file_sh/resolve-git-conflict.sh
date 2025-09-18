#!/bin/bash

# Script untuk menyelesaikan Git conflict di server production
# File: resolve-git-conflict.sh

echo "🔄 Git Conflict Resolver - Makna Academy"
echo "========================================"

# Set working directory
cd /home/u380354370/domains/maknaacademy.com/public_html

echo "📋 Current Git Status:"
git status

echo ""
echo "🔍 Checking conflicting files..."

# Backup conflicting files
BACKUP_DIR="backup_$(date +%Y%m%d_%H%M%S)"
mkdir -p $BACKUP_DIR

echo "💾 Creating backup of conflicting files..."
if [ -f "app/Models/User.php" ]; then
    cp app/Models/User.php $BACKUP_DIR/User.php.local
    echo "✅ Backed up app/Models/User.php"
fi

if [ -f "composer.lock" ]; then
    cp composer.lock $BACKUP_DIR/composer.lock.local
    echo "✅ Backed up composer.lock"
fi

echo ""
echo "📊 Showing differences in conflicting files:"
echo "--- User.php differences ---"
git diff HEAD app/Models/User.php

echo ""
echo "--- composer.lock differences ---"
git diff HEAD composer.lock

echo ""
echo "🔧 Resolution Options:"
echo "1. Stash local changes and pull (recommended for composer.lock)"
echo "2. Force pull and overwrite local changes"
echo "3. Manual resolution"
echo ""
echo "⚠️  RECOMMENDED APPROACH:"
echo "   - Stash changes first"
echo "   - Pull from remote"
echo "   - Apply important User.php changes manually"
echo ""

# Option 1: Safe stash approach
echo "🚀 Executing safe resolution..."
echo "Step 1: Stashing local changes..."
git stash push -m "Backup before pull $(date)"

echo "Step 2: Pulling from remote..."
git pull origin main

echo "Step 3: Checking if stash contains important changes..."
echo "📋 Stashed changes summary:"
git stash show -p stash@{0} --name-only

echo ""
echo "✅ Git conflict resolved!"
echo "💡 Next steps:"
echo "   1. Check if app works correctly"
echo "   2. If User.php changes are needed, apply them manually"
echo "   3. Run: php artisan config:cache"
echo "   4. Run: php artisan route:cache"
echo "   5. Run: composer install --no-dev --optimize-autoloader"
echo ""
echo "📁 Backup files saved in: $BACKUP_DIR/"
echo "🔄 To see stashed changes: git stash show -p"
echo "🔙 To restore stash if needed: git stash apply"