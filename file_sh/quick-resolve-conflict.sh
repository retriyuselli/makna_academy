#!/bin/bash

# Quick Git Conflict Resolution
# Untuk dijalankan di server production

echo "🔄 Resolving Git Conflict..."

# Backup files
mkdir -p backup_conflict_$(date +%Y%m%d_%H%M%S)
cp app/Models/User.php backup_conflict_$(date +%Y%m%d_%H%M%S)/ 2>/dev/null
cp composer.lock backup_conflict_$(date +%Y%m%d_%H%M%S)/ 2>/dev/null

# Stash changes
git stash push -m "Pre-pull backup $(date)"

# Pull updates
git pull origin main

# Show result
echo "✅ Pull completed!"
echo "💾 Backup created in backup_conflict_* folder"
echo "🔄 Changes stashed - use 'git stash list' to see"

# Post-pull commands
echo "🚀 Running post-pull commands..."
php artisan config:cache
php artisan route:cache
composer install --no-dev --optimize-autoloader

echo "✅ All done!"