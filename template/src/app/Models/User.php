<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasAvatar
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'avatar_url',
        'name',
        'email',
        'phone',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getFilamentAvatarUrl(): ?string
    {
        if ($this->avatar_url) {
            return asset('storage/' . $this->avatar_url);
        }
        $hash = md5(strtolower(trim($this->email)));

        return 'https://www.gravatar.com/avatar/' . $hash . '?d=mp&r=g&s=250';
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->role, ['ADMIN', 'MANAGER']);
    }

    // ===== Role helpers =====

    public function isCustomer(): bool { return $this->role === 'CUSTOMER'; }
    public function isAdmin(): bool    { return $this->role === 'ADMIN'; }
    public function isTechnician(): bool { return $this->role === 'TECHNICIAN'; }
    public function isManager(): bool  { return $this->role === 'MANAGER'; }

    // ===== Relations =====

    public function technician()
    {
        return $this->hasOne(Technician::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'customer_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'customer_id');
    }
}
