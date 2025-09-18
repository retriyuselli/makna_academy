# Makna Academy - Installation & Deployment Guide

## Server Path
```bash
cd /home/u380354370/domains/maknaacademy.com/public_html
```

## Migration Commands
```bash
php artisan migrate --path=database/migrations/
```

## Git Conflict Resolution
If you encounter git pull conflicts, use this approach:

### Quick Resolution (Recommended)
```bash
# Run the automated script
chmod +x file_sh/quick-resolve-conflict.sh
./file_sh/quick-resolve-conflict.sh
```

### Manual Resolution
```bash
# 1. Backup conflicting files
mkdir -p backup_$(date +%Y%m%d_%H%M%S)
cp app/Models/User.php backup_$(date +%Y%m%d_%H%M%S)/
cp composer.lock backup_$(date +%Y%m%d_%H%M%S)/

# 2. Stash local changes
git stash push -m "Backup before pull $(date)"

# 3. Pull from remote
git pull origin main

# 4. Post-pull optimization
php artisan config:cache
php artisan route:cache
composer install --no-dev --optimize-autoloader
```

## Common Issues & Solutions

### Git Pull Conflicts
- **Cause**: Local changes in `app/Models/User.php` and `composer.lock`
- **Solution**: Use stash approach to preserve important changes
- **Files to check**: Always verify User.php model after pull

### Avatar Display Issues
- **Check**: Storage link exists (`storage -> storage/app/public`)
- **Check**: Helper function `user_avatar()` handles path correctly
- **Check**: File permissions on avatar files

### Registration Errors
- **Check**: Password field mass assignment in RegisterController
- **Check**: User model `$fillable` vs `$guarded` arrays
