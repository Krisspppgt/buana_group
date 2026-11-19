<?php

namespace App\Services;

use App\Mail\PasswordOtpMail;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OtpNotificationService
{
    /**
     * Kirim kode OTP ke WhatsApp pengguna via Fonnte API, dengan fallback log lokal jika token kosong.
     *
     * @return array{sent: bool, simulated: bool, message: string}
     */
    public function sendWhatsapp(string $phone, string $otp, string $userName, int $expiryMinutes = 10): array
    {
        $token = (string) config('services.fonnte.token');
        $message = "*Buana Group - Reset Kata Sandi*\n\n"
            ."Halo {$userName},\n"
            ."Kode OTP Anda: *{$otp}*\n"
            ."Berlaku selama {$expiryMinutes} menit.\n\n"
            .'Demi keamanan, jangan bagikan kode ini kepada siapa pun.';

        if (empty($token)) {
            Log::info('[SIMULASI WA OTP - Fonnte Token belum diisi]', [
                'phone' => $phone,
                'user' => $userName,
                'otp' => $otp,
                'expires_in_minutes' => $expiryMinutes,
            ]);

            return [
                'sent' => true,
                'simulated' => true,
                'message' => 'OTP dicatat di log lokal (mode simulasi karena FONNTE_TOKEN belum diisi).',
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->timeout(10)->post('https://api.fonnte.com/send', [
                'target' => $phone,
                'message' => $message,
                'countryCode' => '62',
            ]);

            if ($response->successful() && ($response->json('status') === true || $response->json('status') === 'true')) {
                return [
                    'sent' => true,
                    'simulated' => false,
                    'message' => 'Kode OTP berhasil dikirim ke WhatsApp Anda.',
                ];
            }

            Log::warning('Fonnte WA OTP gagal terkirim', [
                'phone' => $phone,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'sent' => false,
                'simulated' => false,
                'message' => 'Gagal mengirim OTP ke WhatsApp. Silakan coba lagi atau gunakan saluran Gmail.',
            ];
        } catch (\Throwable $e) {
            Log::error('Fonnte WA OTP exception: '.$e->getMessage(), ['phone' => $phone]);

            return [
                'sent' => false,
                'simulated' => false,
                'message' => 'Terjadi gangguan koneksi ke layanan WhatsApp. Silakan coba beberapa saat lagi.',
            ];
        }
    }

    /**
     * Kirim kode OTP ke email/Gmail pengguna.
     *
     * @return array{sent: bool, simulated: bool, message: string}
     */
    public function sendEmail(User $user, string $otp, int $expiryMinutes = 10): array
    {
        $isLogMailer = config('mail.default') === 'log';

        try {
            Mail::to($user->email)->send(new PasswordOtpMail($user, $otp, $expiryMinutes));

            if ($isLogMailer) {
                Log::info('[SIMULASI GMAIL OTP - MAIL_MAILER=log]', [
                    'email' => $user->email,
                    'user' => $user->name,
                    'otp' => $otp,
                    'expires_in_minutes' => $expiryMinutes,
                ]);
            }

            return [
                'sent' => true,
                'simulated' => $isLogMailer,
                'message' => $isLogMailer
                    ? 'OTP dicatat di log lokal (mode simulasi karena MAIL_MAILER=log).'
                    : 'Kode OTP berhasil dikirim ke email/Gmail Anda.',
            ];
        } catch (\Throwable $e) {
            Log::error('Gmail OTP exception: '.$e->getMessage(), ['email' => $user->email]);

            return [
                'sent' => false,
                'simulated' => false,
                'message' => 'Gagal mengirim OTP ke email. Pastikan konfigurasi SMTP Gmail sudah benar.',
            ];
        }
    }
}
