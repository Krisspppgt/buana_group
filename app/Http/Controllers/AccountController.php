<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\Role;
use App\Enums\VerificationStatus;
use App\Models\Address;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AccountController extends Controller
{
    /**
     * Menampilkan halaman Profil Akun & Buku Alamat Pengiriman.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $addresses = $user->addresses()
            ->orderByDesc('is_main')
            ->latest('id')
            ->get();

        return view('account.index', compact('user', 'addresses'));
    }

    /**
     * Menampilkan halaman Fasilitas Tempo B2B, rincian penggunaan limit, dan pelunasan via Midtrans.
     */
    public function tempo(Request $request): View
    {
        $user = $request->user();

        $tempoPayments = Payment::with(['order.items.product', 'order.payments'])
            ->where('method', 'tempo_b2b')
            ->whereHas('order', function ($query) use ($user): void {
                $query->where('user_id', $user->id)
                    ->where('status', '!=', OrderStatus::Cancelled);
            })
            ->latest('id')
            ->get();

        $tempoLimit = (int) $user->tempo_limit;
        $usedTempo = $user->usedTempoAmount();
        $remainingTempo = $user->remainingTempoLimit();
        $usagePercent = $tempoLimit > 0
            ? min(100, (int) round(($usedTempo / $tempoLimit) * 100))
            : 0;

        $pendingPayments = $tempoPayments
            ->where('status', PaymentStatus::Pending)
            ->values();

        $settledPayments = $tempoPayments
            ->where('status', PaymentStatus::Success)
            ->values();

        $settledTotal = (int) $settledPayments->sum('amount');
        $hasOverdue = $user->hasOverdueTempo();

        return view('account.tempo', compact(
            'user',
            'tempoPayments',
            'tempoLimit',
            'usedTempo',
            'remainingTempo',
            'usagePercent',
            'pendingPayments',
            'settledPayments',
            'settledTotal',
            'hasOverdue'
        ));
    }

    /**
     * Memperbarui informasi profil akun pengguna.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:40'],
            'email' => ['required', 'email', 'max:50', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['required', 'regex:/^08\d{8,12}$/', Rule::unique('users', 'phone')->ignore($user->id)],
            'campus_id' => ['nullable', 'string', 'max:20'],
            'faculty' => ['nullable', 'string', 'max:80'],
            'company_name' => ['nullable', 'string', 'max:100'],
            'position' => ['nullable', 'string', 'max:60'],
            'password' => ['nullable', 'string', 'min:8', 'max:72', 'confirmed'],
        ], [
            'phone.regex' => 'Format nomor WhatsApp harus diawali 08 (contoh: 081234567890).',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $newCampusId = filled($data['campus_id'] ?? null) ? trim((string) $data['campus_id']) : null;
        $newFaculty = filled($data['faculty'] ?? null) ? trim((string) $data['faculty']) : null;
        $newCompanyName = filled($data['company_name'] ?? null) ? trim((string) $data['company_name']) : null;
        $newPosition = filled($data['position'] ?? null) ? trim((string) $data['position']) : null;

        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'campus_id' => $newCampusId,
            'faculty' => $newFaculty,
            'company_name' => $newCompanyName,
            'position' => $newPosition,
        ];

        $needsVerificationMessage = false;

        if ($user->role === Role::Customer || $user->role === Role::Civitas) {
            $campusChanged = $newCampusId !== $user->campus_id;

            if ($newCampusId !== null && ($campusChanged || $user->role === Role::Customer)) {
                $updateData['role'] = Role::Customer;
                $updateData['requested_role'] = Role::Civitas;
                $updateData['verification_status'] = VerificationStatus::Pending;
                $updateData['verification_note'] = null;
                $updateData['verified_at'] = null;
                $updateData['tempo_approved'] = false;
                $updateData['tempo_limit'] = 0;
                $needsVerificationMessage = true;
            } elseif ($newCampusId === null && $campusChanged) {
                $updateData['role'] = Role::Customer;
                $updateData['requested_role'] = null;
                $updateData['verification_status'] = VerificationStatus::None;
                $updateData['verification_note'] = null;
                $updateData['verified_at'] = null;
                $updateData['tempo_approved'] = false;
                $updateData['tempo_limit'] = 0;
            }
        }

        if ($user->role === Role::Customer && ! $needsVerificationMessage && $newCompanyName !== null && $newCompanyName !== $user->company_name) {
            $updateData['requested_role'] = Role::Coordinator;
            $updateData['verification_status'] = VerificationStatus::Pending;
            $updateData['verification_note'] = null;
            $updateData['verified_at'] = null;
            $needsVerificationMessage = true;
        }

        if (! empty($data['password'])) {
            $updateData['password'] = $data['password'];
        }

        $user->update($updateData);

        $message = $needsVerificationMessage
            ? 'Profil disimpan. Pengajuan verifikasi akun khusus sedang menunggu persetujuan admin.'
            : 'Profil akun berhasil diperbarui.';

        return back()->with('success', $message);
    }

    /**
     * Menyimpan alamat pengiriman baru ke Buku Alamat.
     */
    public function storeAddress(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'label' => ['required', 'string', 'max:15'],
            'receiver' => ['required', 'string', 'max:40'],
            'phone' => ['required', 'regex:/^08\d{8,12}$/'],
            'detail' => ['required', 'string', 'max:150'],
            'is_main' => ['nullable', 'boolean'],
        ], [
            'phone.regex' => 'Format nomor HP penerima harus diawali 08 (contoh: 081234567890).',
        ]);

        DB::transaction(function () use ($user, $data) {
            $isMain = (bool) ($data['is_main'] ?? false) || $user->addresses()->count() === 0;

            if ($isMain) {
                $user->addresses()->update(['is_main' => false]);
            }

            $user->addresses()->create([
                'label' => $data['label'],
                'receiver' => $data['receiver'],
                'phone' => $data['phone'],
                'detail' => $data['detail'],
                'is_main' => $isMain,
            ]);
        });

        return back()->with('success', 'Alamat pengiriman baru berhasil disimpan ke Buku Alamat.');
    }

    /**
     * Menjadikan salah satu alamat sebagai Alamat Utama (default form pemesanan).
     */
    public function setMainAddress(Request $request, Address $address): RedirectResponse
    {
        $user = $request->user();

        if ($address->user_id !== $user->id) {
            abort(403);
        }

        DB::transaction(function () use ($user, $address) {
            $user->addresses()->update(['is_main' => false]);
            $address->update(['is_main' => true]);
        });

        return back()->with('success', "Alamat \"{$address->label}\" berhasil dijadikan alamat utama.");
    }

    /**
     * Menghapus alamat dari Buku Alamat.
     */
    public function deleteAddress(Request $request, Address $address): RedirectResponse
    {
        $user = $request->user();

        if ($address->user_id !== $user->id) {
            abort(403);
        }

        $wasMain = $address->is_main;
        $address->delete();

        if ($wasMain) {
            $nextAddress = $user->addresses()->latest('id')->first();
            if ($nextAddress) {
                $nextAddress->update(['is_main' => true]);
            }
        }

        return back()->with('success', 'Alamat berhasil dihapus dari Buku Alamat.');
    }
}
