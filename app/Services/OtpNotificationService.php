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
            if (app()->isProduction()) {
                Log::error('FONNTE_TOKEN belum diisi, OTP WhatsApp tidak dapat dikirim.', ['phone' => $phone]);

                return [
                    'sent' => false,
                    'simulated' => false,
                    'message' => 'Layanan OTP WhatsApp belum tersedia. Silakan gunakan saluran Gmail.',
                ];
            }

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
            $client = Http::withHeaders([
                'Authorization' => $token,
            ])->asForm()->timeout(15);

            $caBundle = $this->resolveCaBundle();
            if ($caBundle !== null) {
                $client = $client->withOptions(['verify' => $caBundle]);
            } elseif (app()->environment('local', 'testing')) {
                $client = $client->withoutVerifying();
            }

            $response = $client->post('https://api.fonnte.com/send', [
                'target' => $phone,
                'message' => $message,
                'countryCode' => '62',
                'connectOnly' => 'true',
            ]);

            if ($response->successful() && filter_var($response->json('status'), FILTER_VALIDATE_BOOLEAN)) {
                return [
                    'sent' => true,
                    'simulated' => false,
                    'message' => 'Kode OTP berhasil dikirim ke WhatsApp Anda.',
                ];
            }

            $reason = (string) ($response->json('reason') ?? '');
            Log::warning('Fonnte WA OTP gagal terkirim', [
                'phone' => $phone,
                'status' => $response->status(),
                'reason' => $reason,
                'body' => $response->body(),
            ]);

            $friendlyMessage = match ($reason) {
                'device disconnected' => 'Layanan WhatsApp Buana Group saat ini sedang terputus (device offline). Silakan coba saluran Gmail.',
                default => 'Gagal mengirim OTP ke WhatsApp. Silakan coba lagi atau gunakan saluran Gmail.',
            };

            return [
                'sent' => false,
                'simulated' => false,
                'message' => $friendlyMessage,
            ];
        } catch (\Throwable $e) {
            // Fallback khusus bila terjadi cURL error 60 (SSL cert issue di Windows/XAMPP) pada local/testing
            if (app()->environment('local', 'testing') && str_contains($e->getMessage(), 'cURL error 60')) {
                try {
                    $retryResponse = Http::withHeaders(['Authorization' => $token])
                        ->asForm()
                        ->timeout(15)
                        ->withoutVerifying()
                        ->post('https://api.fonnte.com/send', [
                            'target' => $phone,
                            'message' => $message,
                            'countryCode' => '62',
                            'connectOnly' => 'true',
                        ]);

                    if ($retryResponse->successful() && filter_var($retryResponse->json('status'), FILTER_VALIDATE_BOOLEAN)) {
                        return [
                            'sent' => true,
                            'simulated' => false,
                            'message' => 'Kode OTP berhasil dikirim ke WhatsApp Anda.',
                        ];
                    }
                } catch (\Throwable) {
                    // Teruskan ke logging exception utama
                }
            }

            Log::error('Fonnte WA OTP exception: '.$e->getMessage(), ['phone' => $phone]);

            return [
                'sent' => false,
                'simulated' => false,
                'message' => 'Terjadi gangguan koneksi ke layanan WhatsApp. Silakan coba beberapa saat lagi.',
            ];
        }
    }

    /**
     * Cari lokasi CA certificate bundle untuk lingkungan Windows/XAMPP atau vendor.
     */
    private function resolveCaBundle(): ?string
    {
        $configured = ini_get('curl.cainfo') ?: ini_get('openssl.cafile');
        if (! empty($configured) && is_file($configured)) {
            return $configured;
        }

        $candidates = [
            'D:/xampp/apache/bin/curl-ca-bundle.crt',
            'C:/xampp/apache/bin/curl-ca-bundle.crt',
            base_path('vendor/midtrans/midtrans-php/data/cacert.pem'),
            'D:/xampp/php/extras/ssl/cacert.pem',
            'C:/xampp/php/extras/ssl/cacert.pem',
        ];

        foreach ($candidates as $path) {
            if (is_file($path)) {
                return $path;
            }
        }

        return null;
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
