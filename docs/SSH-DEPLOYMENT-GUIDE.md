# Makna Academy - SSH Deployment Guide untuk Niagahoster

# =====================================================

# 1. AKSES SSH & NAVIGASI

# Login SSH ke server Niagahoster:

ssh username@maknaacademy.com

# atau menggunakan terminal Niagahoster

# 2. NAVIGASI KE DIREKTORI PROJECT

cd public_html

# atau sesuai struktur hosting Anda

# Cek struktur direktori

ls -la

# Masuk ke direktori Laravel

cd maknaacademy1.0_hostinger # sesuaikan nama folder

# 3. CEK ENVIRONMENT

pwd
php -v
composer --version

# 4. CEK KONFIGURASI DOMAIN

# Pastikan domain pointing ke folder /public

# Di cPanel > File Manager, cek Document Root

# 5. DEPLOYMENT COMMANDS

# Clear cache

php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Auto-verify admin users

php artisan admin:verify-all

# Cache for production

php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. PERMISSIONS

chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod 644 .env

# 7. COMPOSER OPTIMIZATION

composer dump-autoload --optimize

# 8. STORAGE LINK

php artisan storage:link

# 9. TEST DEPLOYMENT

php artisan deploy:check
php artisan test:production-url

# 10. CHECK ERROR LOGS

tail -f storage/logs/laravel.log

# 11. VERIFY .HTACCESS

cat public/.htaccess

# 12. CHECK DATABASE CONNECTION

php artisan tinker

# Di tinker: DB::connection()->getPdo();

# TROUBLESHOOTING COMMANDS

# ======================

# Jika 404 pada /events:

php artisan route:list | grep events

# Jika 403 Forbidden:

ls -la public/

# Check apakah domain point ke public/

# Jika database error:

php artisan migrate:status

# Check environment

php artisan config:show app.env
php artisan config:show app.url

# Test email configuration

php artisan email:test your@email.com

# QUICK DEPLOYMENT SCRIPT

# ======================

bash production-deploy.sh
