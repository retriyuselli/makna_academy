<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'google_id',
        'avatar',
        'phone',
        'date_of_birth',
        'gender',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    /**
     * Get the registrations for the user
     */
    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        // Traditional role check (primary)
        $hasTraditionalAccess = $this->isAdmin() || $this->isSuperAdmin();
        
        // Shield permission check (secondary)
        $hasShieldAccess = false;
        if (method_exists($this, 'hasRole')) {
            $hasShieldAccess = $this->hasRole(['admin', 'super_admin', 'panel_user']);
        }
        
        return $hasTraditionalAccess || $hasShieldAccess;
    }
}
