<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\OtpNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    private const OTP_TTL_MINUTES = 10;

    private const RESEND_COOLDOWN_SECONDS = 60;

    private const MAX_ATTEMPTS = 5;

    public function __construct(
        private readonly OtpNotificationService $otpService,
    ) {}

    public function showRequestForm(): View
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'account' => ['required', 'string', 'max:100'],
            'channel' => ['required', 'in:whatsapp,email'],
        ], [
            'account.required' => 'Email, No. WhatsApp, atau NIM/NIP wajib diisi.',
            'channel.required' => 'Silakan pilih metode pengiriman OTP.',
            'channel.in' => 'Saluran pengiriman OTP tidak valid.',
        ]);

        $user = $this->findUserByAccount($data['account']);

        if (! $user) {
            return back()
                ->withErrors(['account' => 'Akun tidak ditemukan. Periksa kembali Email, No. WhatsApp, atau NIM/NIP Anda.'])
                ->withInput();
        }

        if ($data['channel'] === 'whatsapp' && empty($user->phone)) {
            return back()
                ->withErrors(['channel' => 'Akun ini belum memiliki nomor WhatsApp terdaftar. Silakan pilih saluran Gmail.'])
                ->withInput();
        }

        if ($data['channel'] === 'email' && empty($user->email)) {
            return back()
                ->withErrors(['channel' => 'Akun ini belum memiliki alamat email terdaftar. Silakan pilih saluran WhatsApp.'])
                ->withInput();
        }

        $existing = Cache::get($this->cacheKey($user->id));
        if (is_array($existing) && isset($existing['resend_at']) && now()->timestamp < $existing['resend_at']) {
            $wait = $existing['resend_at'] - now()->timestamp;

            return back()
                ->withErrors(['account' => "Mohon tunggu {$wait} detik sebelum meminta kode OTP baru."])
                ->withInput();
        }

        $result = $this->generateAndDispatchOtp($user, $data['channel']);

        if (! $result['sent']) {
            return back()
                ->withErrors(['channel' => $result['message']])
                ->withInput();
        }

        $maskedTarget = $data['channel'] === 'whatsapp'
            ? $this->maskPhone((string) $user->phone)
            : $this->maskEmail((string) $user->email);

        session()->put('password_reset_flow', [
            'user_id' => $user->id,
            'channel' => $data['channel'],
            'masked_target' => $maskedTarget,
            'simulated' => $result['simulated'],
        ]);

        $statusMsg = $result['simulated']
            ? "Mode simulasi aktif: Kode OTP untuk {$maskedTarget} telah dicatat di storage/logs/laravel.log."
            : 'Kode OTP telah dikirim melalui '.($data['channel'] === 'whatsapp' ? 'WhatsApp' : 'Gmail')." ke {$maskedTarget}.";

        return redirect()->route('password.verify')->with('status', $statusMsg);
    }

    public function showVerifyForm(): View|RedirectResponse
    {
        $flow = session('password_reset_flow');

        if (! is_array($flow) || empty($flow['user_id'])) {
            return redirect()->route('password.request')
                ->withErrors(['account' => 'Sesi reset kata sandi telah berakhir. Silakan minta kode OTP kembali.']);
        }

        $cached = Cache::get($this->cacheKey((int) $flow['user_id']));
        $cooldownRemaining = 0;

        if (is_array($cached) && isset($cached['resend_at'])) {
            $cooldownRemaining = max(0, (int) $cached['resend_at'] - now()->timestamp);
        }

        return view('auth.reset-password', [
            'channel' => $flow['channel'],
            'maskedTarget' => $flow['masked_target'],
            'simulated' => (bool) ($flow['simulated'] ?? false),
            'cooldownRemaining' => $cooldownRemaining,
        ]);
    }

    public function resendOtp(): RedirectResponse
    {
        $flow = session('password_reset_flow');

        if (! is_array($flow) || empty($flow['user_id'])) {
            return redirect()->route('password.request')
                ->withErrors(['account' => 'Sesi reset kata sandi tidak ditemukan. Silakan mulai ulang.']);
        }

        $user = User::find((int) $flow['user_id']);
        if (! $user) {
            session()->forget('password_reset_flow');

            return redirect()->route('password.request');
        }

        $cached = Cache::get($this->cacheKey($user->id));
        if (is_array($cached) && isset($cached['resend_at']) && now()->timestamp < $cached['resend_at']) {
            $wait = (int) $cached['resend_at'] - now()->timestamp;

            return back()->withErrors(['otp' => "Mohon tunggu {$wait} detik untuk mengirim ulang OTP."]);
        }

        $result = $this->generateAndDispatchOtp($user, (string) $flow['channel']);

        if (! $result['sent']) {
            return back()->withErrors(['otp' => $result['message']]);
        }

        $statusMsg = $result['simulated']
            ? 'Mode simulasi aktif: Kode OTP baru telah dicatat di storage/logs/laravel.log.'
            : 'Kode OTP baru telah berhasil dikirim ulang.';

        return back()->with('status', $statusMsg);
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $flow = session('password_reset_flow');

        if (! is_array($flow) || empty($flow['user_id'])) {
            return redirect()->route('password.request')
                ->withErrors(['account' => 'Sesi reset kata sandi telah berakhir. Silakan minta kode OTP baru.']);
        }

        $validated = $request->validate([
            'otp' => ['required', 'digits:6'],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:72',
                'confirmed',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    $password = (string) $value;

                    if (! preg_match('/^[A-Z]/', $password)) {
                        $fail('Huruf pertama kata sandi harus huruf kapital.');
                    }

                    if (! preg_match('/\d/', $password)) {
                        $fail('Kata sandi harus mengandung minimal satu angka.');
                    }
                },
            ],
        ], [
            'otp.required' => 'Kode OTP 6 digit wajib diisi.',
            'otp.digits' => 'Kode OTP harus berupa 6 digit angka.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.max' => 'Kata sandi maksimal 72 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $userId = (int) $flow['user_id'];
        $cacheKey = $this->cacheKey($userId);
        $record = Cache::get($cacheKey);

        if (! is_array($record) || empty($record['otp_hash']) || now()->timestamp > ($record['expires_at'] ?? 0)) {
            Cache::forget($cacheKey);

            return back()->withErrors(['otp' => 'Kode OTP sudah kedaluwarsa. Silakan klik Kirim Ulang OTP.']);
        }

        if (($record['attempts'] ?? 0) >= self::MAX_ATTEMPTS) {
            Cache::forget($cacheKey);

            return back()->withErrors(['otp' => 'Terlalu banyak percobaan OTP salah. Silakan kirim ulang kode OTP baru.']);
        }

        if (! Hash::check($validated['otp'], $record['otp_hash'])) {
            $record['attempts'] = ($record['attempts'] ?? 0) + 1;
            $remainingTtl = max(1, (int) ($record['expires_at'] - now()->timestamp));
            Cache::put($cacheKey, $record, $remainingTtl);

            $sisa = self::MAX_ATTEMPTS - $record['attempts'];

            return back()->withErrors(['otp' => "Kode OTP tidak sesuai. Sisa percobaan: {$sisa}x."]);
        }

        $user = User::find($userId);
        if (! $user) {
            Cache::forget($cacheKey);
            session()->forget('password_reset_flow');

            return redirect()->route('password.request');
        }

        $user->password = $validated['password'];
        $user->save();

        Cache::forget($cacheKey);
        session()->forget('password_reset_flow');

        return redirect()->route('login')
            ->with('status', 'Kata sandi berhasil diperbarui. Silakan masuk dengan kata sandi baru Anda.');
    }

    /**
     * @return array{sent: bool, simulated: bool, message: string}
     */
    private function generateAndDispatchOtp(User $user, string $channel): array
    {
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $result = $channel === 'whatsapp'
            ? $this->otpService->sendWhatsapp((string) $user->phone, $otp, $user->name, self::OTP_TTL_MINUTES)
            : $this->otpService->sendEmail($user, $otp, self::OTP_TTL_MINUTES);

        if ($result['sent']) {
            Cache::put($this->cacheKey($user->id), [
                'otp_hash' => Hash::make($otp),
                'channel' => $channel,
                'attempts' => 0,
                'expires_at' => now()->addMinutes(self::OTP_TTL_MINUTES)->timestamp,
                'resend_at' => now()->addSeconds(self::RESEND_COOLDOWN_SECONDS)->timestamp,
            ], now()->addMinutes(self::OTP_TTL_MINUTES));
        }

        return $result;
    }

    private function findUserByAccount(string $rawAccount): ?User
    {
        $account = trim($rawAccount);

        if (filter_var($account, FILTER_VALIDATE_EMAIL)) {
            return User::where('email', $account)->first();
        }

        $normalizedPhone = $this->normalizePhone($account);
        if (! empty($normalizedPhone)) {
            $byPhone = User::where('phone', $normalizedPhone)->first();
            if ($byPhone) {
                return $byPhone;
            }
        }

        return User::where('campus_id', $account)
            ->orWhere('phone', $account)
            ->first();
    }

    private function normalizePhone(string $phone): string
    {
        $p = preg_replace('/\D+/', '', $phone) ?? '';

        if (str_starts_with($p, '62')) {
            $p = '0'.substr($p, 2);
        } elseif (str_starts_with($p, '8')) {
            $p = '0'.$p;
        }

        return $p;
    }

    private function maskPhone(string $phone): string
    {
        $len = strlen($phone);
        if ($len <= 6) {
            return $phone;
        }

        return substr($phone, 0, 4).str_repeat('*', max(2, $len - 7)).substr($phone, -3);
    }

    private function maskEmail(string $email): string
    {
        $parts = explode('@', $email);
        if (count($parts) !== 2) {
            return $email;
        }

        [$local, $domain] = $parts;
        $visible = substr($local, 0, min(2, strlen($local)));

        return $visible.'***@'.$domain;
    }

    private function cacheKey(int $userId): string
    {
        return "password_otp:{$userId}";
    }
}
