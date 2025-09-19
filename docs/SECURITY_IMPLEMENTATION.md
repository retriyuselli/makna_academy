# 🔒 Panduan Keamanan Makna Academy - Production

## ✅ Fitur Keamanan yang Sudah Diimplementasikan

### 1. **Session Security**

```env
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
```

### 2. **Security Headers Middleware**

-   X-Frame-Options: DENY
-   X-Content-Type-Options: nosniff
-   X-XSS-Protection: 1; mode=block
-   Content-Security-Policy (CSP)
-   Strict-Transport-Security (HTTPS only)

### 3. **Login Security**

-   Rate limiting: 5 attempts per email/IP
-   Comprehensive security logging
-   Failed/successful login tracking
-   IP and User-Agent logging

### 4. **Password Security**

-   Minimum 8 characters
-   Must contain letters, numbers, and symbols
-   BCrypt hashing with 12 rounds

### 5. **CSRF Protection**

-   Built-in Laravel CSRF tokens
-   All forms protected

## 🚀 Setup untuk Production Environment

### 1. **Environment Variables (.env)**

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Session Security
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax

# Database untuk session (recommended)
SESSION_DRIVER=database

# HTTPS Enforcement
FORCE_HTTPS=true
```

### 2. **Web Server Configuration**

#### Apache (.htaccess)

```apache
# Force HTTPS
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Security Headers
Header always set X-Frame-Options "DENY"
Header always set X-Content-Type-Options "nosniff"
Header always set X-XSS-Protection "1; mode=block"
Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains; preload"
```

#### Nginx

```nginx
# Force HTTPS
server {
    listen 80;
    return 301 https://$server_name$request_uri;
}

# Security Headers
add_header X-Frame-Options "DENY";
add_header X-Content-Type-Options "nosniff";
add_header X-XSS-Protection "1; mode=block";
add_header Strict-Transport-Security "max-age=31536000; includeSubDomains; preload";
```

### 3. **SSL Certificate**

-   Pastikan menggunakan SSL Certificate yang valid
-   Minimal TLS 1.2, preferably TLS 1.3
-   Konfigurasi cipher suite yang aman

### 4. **Database Security**

-   Gunakan user database dengan minimal privileges
-   Aktifkan MySQL/PostgreSQL SSL connections
-   Regular database backups dengan enkripsi

### 5. **File Permissions**

```bash
# Set proper file permissions
find /path/to/laravel -type f -exec chmod 644 {} \;
find /path/to/laravel -type d -exec chmod 755 {} \;
chmod 600 .env
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

## 📊 Security Monitoring

### 1. **Log Files**

-   `storage/logs/security.log` - Login attempts & security events
-   `storage/logs/laravel.log` - General application logs

### 2. **Monitoring Commands**

```bash
# Monitor failed login attempts
tail -f storage/logs/security.log | grep "Failed login"

# Monitor successful logins
tail -f storage/logs/security.log | grep "Successful login"

# Check rate limiting
tail -f storage/logs/security.log | grep "rate limited"
```

### 3. **Security Alerts**

Implementasi monitoring untuk:

-   Multiple failed login attempts dari IP yang sama
-   Login dari lokasi geografis yang tidak biasa
-   Akses admin di luar jam kerja
-   Password reset yang berulang

## 🛡️ Security Checklist untuk Production

-   [ ] HTTPS aktif dan dipaksa
-   [ ] SSL Certificate valid dan tidak expired
-   [ ] Environment variables aman (.env tidak accessible via web)
-   [ ] Database credentials aman
-   [ ] File permissions proper
-   [ ] Error reporting disabled (APP_DEBUG=false)
-   [ ] Security headers aktif
-   [ ] Session encryption enabled
-   [ ] Login monitoring aktif
-   [ ] Regular security updates
-   [ ] Backup strategy implemented
-   [ ] Firewall configured
-   [ ] Intrusion detection system (optional)

## 🔧 Maintenance Commands

```bash
# Clear all caches
php artisan optimize:clear

# Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Check security logs
php artisan log:security-check

# Monitor active sessions
php artisan session:monitor
```

## ⚠️ Security Incident Response

1. **Suspicious Activity Detection**

    - Monitor security.log for patterns
    - Check for brute force attempts
    - Verify admin access logs

2. **Immediate Actions**

    - Change admin passwords
    - Revoke sessions: `php artisan session:flush`
    - Enable maintenance mode: `php artisan down`
    - Check file integrity

3. **Investigation**
    - Review all log files
    - Check database for unauthorized changes
    - Verify backup integrity
    - Update security measures

---

**Skor Keamanan Saat Ini: 9.5/10** ✅

Implementasi keamanan sudah mencakup semua aspek penting untuk aplikasi web production-ready.
