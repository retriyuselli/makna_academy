# 🏠 HOSTINGER DEPLOYMENT COMMANDS

## 🚨 URGENT FIX - CollisionServiceProvider Error:

### PILIHAN 1: Quick Fix (Recommended) 🔕

```bash
git pull origin main
chmod +x disable-collision.sh
./disable-collision.sh
```

### PILIHAN 2: Manual Fix 🔧

```bash
git pull origin main
chmod +x manual-fix-collision.sh
./manual-fix-collision.sh
```

### PILIHAN 3: Emergency Full Reset 🚨

```bash
git pull origin main
chmod +x emergency-fix-collision.sh
./emergency-fix-collision.sh
```

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

### Error 403 Forbidden:

```bash
./quick-403-fix.sh
```

### Error CollisionServiceProvider not found:

```bash
# Quick fix
rm -rf vendor composer.lock
composer2 clear-cache
composer2 install --no-dev --optimize-autoloader

# Atau gunakan script
chmod +x fix-collision-error.sh
./fix-collision-error.sh
```

### Error composer dependencies:

```bash
# Force reinstall
rm -rf vendor composer.lock
composer2 update --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:clear
```
