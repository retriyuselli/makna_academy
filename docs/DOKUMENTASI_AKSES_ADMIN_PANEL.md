# Dokumentasi Pembatasan Akses Panel Admin Filament

## Tujuan

Membatasi akses ke panel admin Filament (`/admin`) hanya untuk user dengan role `admin` dan `super_admin`. User dengan role lain (misal: `customer`) akan ditolak aksesnya (403 Forbidden).

## Implementasi

### 1. Penambahan Method di Model User

Sudah terdapat method `canAccessPanel` di `app/Models/User.php`:

```php
public function canAccessPanel(\Filament\Panel $panel): bool
{
    return $this->isAdmin() || $this->isSuperAdmin();
}
```

### 2. Middleware Custom

File: `app/Http/Middleware/EnsureUserIsAdminOrSuperAdmin.php`

```php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdminOrSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user || !in_array($user->role, ['admin', 'super_admin'])) {
            abort(403, 'Unauthorized.');
        }
        return $next($request);
    }
}
```

### 3. Registrasi Middleware

File: `app/Http/Kernel.php`

```php
protected $routeMiddleware = [
    // ...existing code...
    'admin_or_superadmin' => \App\Http\Middleware\EnsureUserIsAdminOrSuperAdmin::class,
];
```

### 4. Aktivasi Middleware di Panel Filament

File: `app/Providers/Filament/AdminPanelProvider.php`

```php
->middleware([
    // ...existing code...
    \App\Http\Middleware\EnsureUserIsAdminOrSuperAdmin::class,
])
```

## Cara Kerja

-   Setiap request ke `/admin` akan dicek apakah user sudah login dan memiliki role `admin` atau `super_admin`.
-   Jika tidak, akan langsung diarahkan ke halaman 403 Forbidden.

## Pengujian

1. Login sebagai user dengan role `customer`, akses `/admin` → akses ditolak (403).
2. Login sebagai user dengan role `admin` atau `super_admin`, akses `/admin` → akses diterima.

## Catatan

-   Pastikan role user di database sudah benar.
-   Jika ada perubahan role, logout dan login ulang agar session terupdate.
