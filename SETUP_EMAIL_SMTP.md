# Setup Email SMTP untuk Verifikasi Email Real

## 🎯 Tujuan

Mengatur sistem agar email verifikasi benar-benar dikirim ke email pengguna, bukan hanya disimpan di log.

## 📧 Setup Gmail SMTP

### Langkah 1: Persiapan Gmail

1. **Buka Google Account**: https://myaccount.google.com/
2. **Aktifkan 2-Step Verification**:
    - Security → 2-Step Verification → Turn On
3. **Generate App Password**:
    - Security → App passwords
    - Select app: Mail
    - Select device: Other (Custom name) → "Makna Academy"
    - Copy 16-character password (format: xxxx xxxx xxxx xxxx)

### Langkah 2: Update Configuration

Update file `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=maknawedding@gmail.com
MAIL_PASSWORD=your_16_character_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=maknawedding@gmail.com
MAIL_FROM_NAME="Makna Academy"
```

### Langkah 3: Clear Cache & Test

```bash
# Clear configuration cache
php artisan config:clear

# Test email sending
php artisan email:test your-test@email.com

# Test verification email
php artisan email:test your-test@email.com --type=verification
```

## 🔧 Commands Available

| Command                                              | Purpose                     |
| ---------------------------------------------------- | --------------------------- |
| `php artisan email:setup --provider=gmail`           | Setup email configuration   |
| `php artisan email:test {email}`                     | Test simple email           |
| `php artisan email:test {email} --type=verification` | Test verification email     |
| `php artisan email:resend-verification {email}`      | Resend verification to user |
| `php artisan email:check-user {email}`               | Check email status          |
| `php artisan email:monitor`                          | Monitor email activity      |

## 🔒 Keamanan App Password

⚠️ **PENTING**:

-   App Password berbeda dengan password Gmail biasa
-   Gunakan App Password khusus untuk aplikasi
-   Jangan share App Password ke siapa pun
-   Simpan backup App Password di tempat aman

## 🚀 Testing

### Test Email Sederhana:

```bash
php artisan email:test maknawedding@gmail.com
```

### Test Email Verifikasi:

```bash
php artisan email:test maknawedding@gmail.com --type=verification
```

### Resend Verification untuk User Existing:

```bash
php artisan email:resend-verification maknawedding@gmail.com
```

## 📊 Monitoring

### Cek Status Email User:

```bash
php artisan email:check-user maknawedding@gmail.com
```

### Monitor Real-time:

```bash
php artisan email:monitor --lines=20
```

## 🐛 Troubleshooting

### Email Tidak Terkirim?

1. **Cek Credentials**:

    ```bash
    php artisan config:show mail
    ```

2. **Cek Error Log**:

    ```bash
    tail -f storage/logs/laravel.log | grep -i error
    ```

3. **Test Connection**:
    ```bash
    php artisan email:test your-email@domain.com
    ```

### Common Issues:

-   **Authentication failed**: App Password salah atau 2FA belum aktif
-   **Connection timeout**: Firewall/network blocking port 587
-   **Invalid credentials**: Email/password tidak sesuai

## 🌐 Alternative Email Providers

### Mailgun:

```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-domain.mailgun.org
MAILGUN_SECRET=your-mailgun-secret
```

### SendGrid:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
```

## ✅ Verification Flow

1. **User registers** → Email verification sent
2. **User clicks link** in email → Email verified
3. **User can access** protected features

Sekarang pengguna akan menerima email verifikasi langsung di inbox mereka! 🎉
