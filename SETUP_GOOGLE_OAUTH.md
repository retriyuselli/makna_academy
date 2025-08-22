# Setup Google OAuth untuk Login/Register

## 1. Setup Google Cloud Console

### A. Buat Project Baru (jika belum ada)

1. Buka [Google Cloud Console](https://console.cloud.google.com/)
2. Klik "Select a project" di bagian atas
3. Klik "New Project"
4. Nama project: `makna-academy` atau sesuai keinginan
5. Klik "Create"

### B. Aktifkan Google+ API

1. Di sidebar, pilih "APIs & Services" > "Library"
2. Cari "Google+ API" atau "People API"
3. Klik dan tekan "Enable"

### C. Buat OAuth 2.0 Credentials

1. Di sidebar, pilih "APIs & Services" > "Credentials"
2. Klik "Create Credentials" > "OAuth client ID"
3. Jika belum setup OAuth consent screen, akan diminta setup dulu:
    - Application type: External
    - App name: Makna Academy
    - User support email: maknawedding@gmail.com
    - Developer contact: maknawedding@gmail.com
    - Save and continue
4. Pilih Application type: "Web application"
5. Name: "Makna Academy Web App"
6. Authorized redirect URIs:
    - Development: `http://localhost:8000/auth/google/callback`
    - Production: `https://yourdomain.com/auth/google/callback`
7. Klik "Create"
8. Copy Client ID dan Client Secret

## 2. Update Environment Variables

Buka file `.env` dan tambahkan di bagian bawah:

```env
# Google OAuth Settings
GOOGLE_CLIENT_ID=your_google_client_id_here
GOOGLE_CLIENT_SECRET=your_google_client_secret_here
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

**PENTING**:

-   Untuk development: gunakan `http://localhost:8000/auth/google/callback`
-   Untuk production: ganti dengan `https://yourdomain.com/auth/google/callback`
-   Jangan commit credentials ke repository!

## 3. Test Configuration

```bash
php artisan oauth:test-google
```

Pastikan semua ✅ hijau sebelum lanjut ke step berikutnya.

## 3. Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

## 4. Test Google OAuth

### A. Akses URL Login Google

Buka browser dan kunjungi: `http://localhost:8000/auth/google/redirect`

### B. Flow yang Terjadi:

1. User diklik ke Google OAuth
2. User login/pilih akun Google
3. Google redirect ke `/auth/google/callback`
4. System cek apakah user sudah ada:
    - Jika ada dengan google_id sama → login langsung
    - Jika ada dengan email sama → link akun dengan google_id
    - Jika belum ada → buat akun baru
5. User login dan redirect ke home

## 5. Integrate dengan View

### A. Tambahkan tombol di Login Page

```html
<a
    href="{{ route('google.redirect') }}"
    class="flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50"
>
    <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
        <path
            fill="#4285F4"
            d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
        />
        <path
            fill="#34A853"
            d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
        />
        <path
            fill="#FBBC05"
            d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
        />
        <path
            fill="#EA4335"
            d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
        />
    </svg>
    Login dengan Google
</a>
```

### B. Tambahkan di Register Page juga

```html
<div class="mt-6">
    <div class="relative">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="px-2 bg-white text-gray-500">Atau</span>
        </div>
    </div>

    <div class="mt-6">
        <a
            href="{{ route('google.redirect') }}"
            class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50"
        >
            <!-- Icon Google di sini -->
            Daftar dengan Google
        </a>
    </div>
</div>
```

## 6. Security Notes

1. **Never commit Google credentials** ke repository
2. **Use different credentials** untuk development dan production
3. **Validate redirect URIs** di Google Console harus sesuai dengan environment
4. **Set proper OAuth consent screen** untuk production

## 7. Features

### ✅ Yang Sudah Tersedia:

-   Auto-create user baru dari Google
-   Link akun existing berdasarkan email
-   Auto-verify email (karena Google sudah verify)
-   Simpan avatar dari Google
-   Role default 'customer'
-   Password random untuk user Google (mereka tidak perlu tau)

### 🔄 Hybrid System:

-   User bisa login dengan email/password biasa
-   User bisa login dengan Google
-   User yang daftar manual bisa link dengan Google
-   Email system tetap berfungsi untuk notifikasi

## 8. Troubleshooting

### Error: "redirect_uri_mismatch"

-   Pastikan redirect URI di Google Console sama persis dengan yang di .env
-   Cek trailing slash, http vs https, port number

### Error: "invalid_client"

-   Cek GOOGLE_CLIENT_ID dan GOOGLE_CLIENT_SECRET
-   Pastikan tidak ada spasi extra

### Error: "access_denied"

-   User cancel login di Google
-   Normal behavior, user bisa coba lagi
