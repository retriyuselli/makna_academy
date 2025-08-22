# 🏠 HOSTINGER DEPLOYMENT COMMANDS

## Quick Fix untuk Error Composer di Hostinger:

```bash
# 1. Stash & Pull
git stash && git pull origin main

# 2. Remove lock file
rm -f composer.lock

# 3. Install dengan composer2 (PENTING!)
composer2 install --no-dev --optimize-autoloader

# 4. Run migrations
php artisan migrate --force

# 5. Clear cache
php artisan config:clear && php artisan route:clear && php artisan cache:clear

# 6. Fix permissions
chmod -R 755 storage bootstrap/cache
```

## Atau Gunakan Script Otomatis:

```bash
# Pull script terbaru
git pull origin main

# Jalankan script khusus Hostinger
chmod +x deploy-shield-hostinger.sh
./deploy-shield-hostinger.sh
```

## PENTING - Hostinger Commands:

-   ❌ `composer` → ✅ `composer2`
-   ❌ `php8.3 artisan` → ✅ `php artisan`
-   Server PHP: 8.2.29 (compatible dengan Shield)

## Test Success:

-   URL: https://maknaacademy.com/admin
-   Login: admin@maknaacademy.com / password123
-   Shield menu harus muncul di admin panel

## Jika Masih Error:

```bash
./quick-403-fix.sh
```
