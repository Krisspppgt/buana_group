<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\Role;
use App\Enums\Unit;
use App\Enums\VerificationStatus;
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

    protected $attributes = [
        'role' => Role::Customer->value,
        'verification_status' => VerificationStatus::None->value,
        'tempo_approved' => false,
        'tempo_limit' => 0,
    ];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'requested_role',
        'verification_status',
        'campus_id',
        'faculty',
        'company_name',
        'position',
        'verification_note',
        'verified_at',
        'tempo_approved',
        'tempo_limit',
        'loyalty_points',
        'fcm_token',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'role' => Role::class,
            'requested_role' => Role::class,
            'verification_status' => VerificationStatus::class,
            'verified_at' => 'datetime',
            'tempo_approved' => 'boolean',
            'tempo_limit' => 'integer',
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
        return $this->role === Role::Civitas;
    }

    /**
     * Member internal mencakup Civitas Akademika (Mahasiswa/Dosen/Pegawai) serta staf/admin internal,
     * tidak termasuk Pelanggan Umum maupun Mitra/Koordinator B2B.
     */
    public function isInternalMember(): bool
    {
        return in_array($this->role, [
            Role::Civitas,
            Role::Courier,
            Role::Operator,
            Role::Admin,
            Role::SuperAdmin,
        ], true);
    }

    public function isVerifiedMitra(): bool
    {
        return $this->role === Role::Coordinator;
    }

    public function hasPendingVerification(): bool
    {
        return $this->verification_status === VerificationStatus::Pending;
    }

    public function isVerificationRejected(): bool
    {
        return $this->verification_status === VerificationStatus::Rejected;
    }

    /**
     * Menghitung total tagihan Tempo B2B/Institusi yang masih terbuka (belum dilunasi).
     */
    public function usedTempoAmount(): int
    {
        return (int) Payment::query()
            ->where('method', 'tempo_b2b')
            ->where('status', PaymentStatus::Pending)
            ->whereHas('order', function ($query): void {
                $query->where('user_id', $this->id)
                    ->where('status', '!=', OrderStatus::Cancelled);
            })
            ->sum('amount');
    }

    public function remainingTempoLimit(): int
    {
        if (! $this->tempo_approved || (int) $this->tempo_limit <= 0) {
            return 0;
        }

        return max(0, (int) $this->tempo_limit - $this->usedTempoAmount());
    }

    /**
     * Status civitas atau mitra tidak otomatis memberi fasilitas kredit; Tempo hanya untuk akun yang disetujui admin dengan limit memadai.
     */
    public function canUseTempo(int $amount = 0): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if (! $this->isCivitas() && ! $this->isVerifiedMitra()) {
            return false;
        }

        if (! $this->tempo_approved || (int) $this->tempo_limit <= 0) {
            return false;
        }

        return ($this->usedTempoAmount() + max(0, $amount)) <= (int) $this->tempo_limit;
    }

    public function approveVerification(Role $targetRole, bool $approveTempo = false, int $tempoLimit = 0, ?string $note = null): void
    {
        $this->update([
            'role' => $targetRole,
            'requested_role' => null,
            'verification_status' => VerificationStatus::Approved,
            'verification_note' => $note,
            'verified_at' => now(),
            'tempo_approved' => $approveTempo,
            'tempo_limit' => $approveTempo ? max(0, $tempoLimit) : 0,
        ]);
    }

    public function rejectVerification(?string $note = null): void
    {
        $this->update([
            'role' => Role::Customer,
            'verification_status' => VerificationStatus::Rejected,
            'verification_note' => $note,
            'verified_at' => null,
            'tempo_approved' => false,
            'tempo_limit' => 0,
        ]);
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
