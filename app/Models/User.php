<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail, FilamentUser, HasAvatar
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
        'password',
        'google_id',
        'avatar_url',
        'phone',
        'date_of_birth',
        'gender',
        'role',
    ];

    /**
     * The attributes that are protected from mass assignment for security
     *
     * @var list<string>
     */
    protected $guarded = [
        'remember_token',
        'email_verified_at',
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
            'date_of_birth' => 'date',
        ];
    }

    /**
     * Boot the model and add event listeners for security
     */
    protected static function boot()
    {
        parent::boot();

        // Sanitize input before saving
        static::saving(function ($user) {
            // Prevent password from being null during updates
            if ($user->exists && empty($user->password)) {
                // If it's an update and password is empty, don't update password
                unset($user->password);
            }
            
            // Sanitize name
            if ($user->name) {
                $user->name = strip_tags(trim($user->name));
            }

            // Validate and sanitize email
            if ($user->email) {
                $user->email = strtolower(trim($user->email));
                if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                    throw new \Exception("Invalid email format");
                }
            }

            // Sanitize phone
            if ($user->phone) {
                $user->phone = preg_replace('/[^0-9+\-\(\)\s]/', '', $user->phone);
            }

            // Validate gender
            if ($user->gender && !in_array($user->gender, ['male', 'female'])) {
                throw new \Exception("Invalid gender value");
            }

            // Validate role
            if ($user->role && !in_array($user->role, ['customer', 'admin', 'super_admin'])) {
                throw new \Exception("Invalid role value");
            }
        });
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
     * Securely update user role - only for admins
     * 
     * @param string $role
     * @param User|null $updatedBy
     * @return bool
     */
    public function updateRole(string $role, ?User $updatedBy = null): bool
    {
        // Validate role
        $allowedRoles = ['customer', 'admin', 'super_admin'];
        if (!in_array($role, $allowedRoles)) {
            throw new \InvalidArgumentException("Invalid role: {$role}");
        }

        // Only super_admin can create other super_admins
        if ($role === 'super_admin' && $updatedBy && !$updatedBy->isSuperAdmin()) {
            throw new \Exception("Only super administrators can assign super_admin role");
        }

        // Only admins can change roles
        if ($updatedBy && !($updatedBy->isAdmin() || $updatedBy->isSuperAdmin())) {
            throw new \Exception("Insufficient permissions to change user role");
        }

        $this->role = $role;
        return $this->save();
    }

    /**
     * Securely update password with validation
     * 
     * @param string $newPassword
     * @param string|null $currentPassword
     * @return bool
     */
    public function updatePassword(string $newPassword, ?string $currentPassword = null): bool
    {
        // Validate current password if provided
        if ($currentPassword && !password_verify($currentPassword, $this->password)) {
            throw new \Exception("Current password is incorrect");
        }

        // Validate new password strength
        if (strlen($newPassword) < 8) {
            throw new \Exception("Password must be at least 8 characters long");
        }

        $this->password = bcrypt($newPassword);
        return $this->save();
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
        // Ensure email is verified for security
        if (!$this->hasVerifiedEmail()) {
            return false;
        }

        // Shield permission check (primary) - Modern approach
        if (method_exists($this, 'hasRole')) {
            $hasShieldAccess = $this->hasRole(['admin', 'super_admin', 'panel_user']);
            if ($hasShieldAccess) {
                return true;
            }
        }

        // Traditional role check (fallback) - Legacy support
        $hasTraditionalAccess = $this->isAdmin() || $this->isSuperAdmin();
        
        return $hasTraditionalAccess;
    }

    /**
     * Check if user can perform admin actions on another user
     * 
     * @param User $targetUser
     * @return bool
     */
    public function canManageUser(User $targetUser): bool
    {
        // Super admin can manage anyone
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Admin can manage customers but not other admins/super_admins
        if ($this->isAdmin() && $targetUser->isCustomer()) {
            return true;
        }

        // Users can only manage themselves for basic profile updates
        return $this->id === $targetUser->id;
    }

    /**
     * Check if user has verified email
     * 
     * @return bool
     */
    public function hasVerifiedEmail(): bool
    {
        return !is_null($this->email_verified_at);
    }

    // public function getFilamentAvatarUrl(): ?string
    // {
        // Use the helper function that already handles security and fallbacks
    //     return user_avatar($this);
    // }

    public function getFilamentAvatarUrl(): ?string
    {
        if ($this->avatar_url) {
            return Storage::url($this->avatar_url);
        }
        
        return null;
    }
}
