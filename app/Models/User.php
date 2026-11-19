<?php

namespace App\Models;

use App\Enums\Role;
use App\Enums\Unit;
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
        'name', 'email', 'phone', 'password', 'role', 'campus_id', 'loyalty_points', 'fcm_token',
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

    public function pointHistories(): HasMany
    {
        return $this->hasMany(PointHistory::class);
    }

    /**
     * Menambahkan Buana Loyalty Points (1% dari nominal transaksi) untuk cross-selling.
     */
    public function earnLoyaltyPoints(int $amountPaid, ?Unit $unit, string $referenceCode, string $description): int
    {
        $earned = max(0, (int) floor($amountPaid * 0.01));
        if ($earned <= 0) {
            return 0;
        }

        $this->increment('loyalty_points', $earned);

        $this->pointHistories()->create([
            'unit' => $unit,
            'type' => 'earn',
            'points' => $earned,
            'reference_code' => $referenceCode,
            'description' => substr($description, 0, 120),
        ]);

        return $earned;
    }

    /**
     * Menukarkan Buana Loyalty Points sebagai potongan harga lintas unit (1 Poin = Rp 1).
     */
    public function redeemLoyaltyPoints(int $requestedPoints, int $maxSubtotal, ?Unit $unit, string $referenceCode, string $description): int
    {
        $available = (int) $this->loyalty_points;
        $redeemed = max(0, min($requestedPoints, $available, $maxSubtotal));

        if ($redeemed <= 0) {
            return 0;
        }

        $this->decrement('loyalty_points', $redeemed);

        $this->pointHistories()->create([
            'unit' => $unit,
            'type' => 'redeem',
            'points' => -$redeemed,
            'reference_code' => $referenceCode,
            'description' => substr($description, 0, 120),
        ]);

        return $redeemed;
    }
}
