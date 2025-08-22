# Panduan Monitoring Email Verifikasi

## Cara Mengecek Email Sudah Dikirim ke User

### 1. **Melalui Command Line (Real-time Monitoring)**

#### Monitor Email Activity (Live):

```bash
php artisan email:monitor --lines=20
```

Menampilkan 20 baris terakhir aktivitas email dari log Laravel.

#### Cek Status Email Specific User:

```bash
php artisan email:check-user user@example.com
```

Menampilkan:

-   Status verifikasi email user
-   History semua email yang dikirim ke user
-   Summary aktivitas email

### 2. **Melalui Database Query**

#### Cek Email Logs di Database:

```php
// Via Tinker
php artisan tinker

// Cek semua email untuk user tertentu
App\Models\EmailLog::forEmail('user@example.com')->get();

// Cek email verifikasi yang berhasil dikirim
App\Models\EmailLog::verificationEmails()->sent()->get();

// Cek email yang gagal
App\Models\EmailLog::failed()->get();
```

### 3. **Melalui File Log Laravel**

#### Monitor Real-time:

```bash
tail -f storage/logs/laravel.log | grep -i "email\|mail\|verification"
```

#### Cek Log Hari Ini:

```bash
grep -i "email\|mail\|verification" storage/logs/laravel-$(date +%Y-%m-%d).log
```

### 4. **Cek Status User di Database**

```sql
-- Cek status verifikasi user
SELECT id, name, email, email_verified_at, created_at
FROM users
WHERE email = 'user@example.com';

-- Cek user yang belum verifikasi email
SELECT id, name, email, created_at
FROM users
WHERE email_verified_at IS NULL;
```

### 5. **Cek Email dengan Browser (Development)**

Jika menggunakan `MAIL_MAILER=log` (default untuk development):

1. Buka `storage/logs/laravel.log`
2. Cari email verification content
3. Copy link verifikasi dari log
4. Paste di browser untuk test

## Troubleshooting Email Issues

### Email Tidak Dikirim?

1. **Cek Konfigurasi Mail:**

```bash
php artisan config:show mail
```

2. **Test Kirim Email Manual:**

```php
// Via Tinker
Mail::raw('Test email', function($message) {
    $message->to('test@example.com')->subject('Test');
});
```

3. **Cek Queue Status (jika menggunakan queue):**

```bash
php artisan queue:status
php artisan queue:work
```

### Email Masuk Spam?

-   Cek SPF, DKIM, DMARC records domain
-   Gunakan email service provider (SendGrid, Mailgun, etc.)
-   Avoid spam trigger words dalam subject

### User Tidak Menerima Email?

1. Cek email logs: `php artisan email:check-user user@example.com`
2. Resend verification: User bisa klik "Kirim Ulang" di halaman verifikasi
3. Manual verification (emergency):

```php
$user = User::where('email', 'user@example.com')->first();
$user->markEmailAsVerified();
```

## Status Email Logs

-   **sending**: Email sedang dalam proses kirim
-   **sent**: Email berhasil dikirim
-   **failed**: Email gagal dikirim

## Email Types

-   **verification**: Email verifikasi akun
-   **notification**: Email notifikasi sistem
-   **general**: Email umum lainnya

## Commands Summary

| Command                                          | Fungsi                  |
| ------------------------------------------------ | ----------------------- |
| `php artisan email:monitor`                      | Monitor aktivitas email |
| `php artisan email:check-user {email}`           | Cek status email user   |
| `tail -f storage/logs/laravel.log \| grep email` | Live monitoring log     |

Gunakan tools ini untuk memastikan email verifikasi terkirim dengan benar ke user yang melakukan registrasi.
