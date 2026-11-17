<?php

namespace App\Models;

use App\Enums\Role;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'role', 'campus_id', 'fcm_token',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'role' => Role::class,
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role === Role::Admin || $this->role === Role::SuperAdmin;
    }

    public function isAdmin(): bool
    {
        return $this->role === Role::Admin || $this->role === Role::SuperAdmin;
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === Role::SuperAdmin;
    }

    public function isCivitas(): bool
    {
        return $this->role === Role::Civitas || ! empty($this->campus_id);
    }

    public function isCourier(): bool
    {
        return $this->role === Role::Courier;
    }

    public function isStaff(): bool
    {
        return in_array($this->role, [
            Role::Courier,
            Role::Operator,
            Role::Admin,
            Role::SuperAdmin,
        ], true);
    }

    public function getRoleBadgeAttribute(): string
    {
        return $this->role?->label() ?? 'Pelanggan Umum';
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }
}
