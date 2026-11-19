<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class MidtransService
{
    public function isConfigured(): bool
    {
        return filled(config('services.midtrans.server_key'))
            && filled(config('services.midtrans.client_key'));
    }

    public function isProduction(): bool
    {
        return (bool) config('services.midtrans.is_production', false);
    }

    public function snapApiUrl(): string
    {
        return $this->isProduction()
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';
    }

    public function snapJsUrl(): string
    {
        return $this->isProduction()
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    }

    public function coreApiBaseUrl(): string
    {
        return $this->isProduction()
            ? 'https://api.midtrans.com'
            : 'https://api.sandbox.midtrans.com';
    }

    /**
     * Mengecek status transaksi langsung ke Midtrans Core API v2 (berguna jika webhook terhalang ngrok).
     *
     * @return array<string, mixed>|null
     */
    public function getTransactionStatus(string $orderId): ?array
    {
        $serverKey = (string) config('services.midtrans.server_key');
        if ($serverKey === '') {
            return null;
        }

        $response = Http::withBasicAuth($serverKey, '')
            ->acceptJson()
            ->timeout(30)
            ->connectTimeout(15)
            ->withOptions(['force_ip_resolve' => 'v4'])
            ->get($this->coreApiBaseUrl()."/v2/{$orderId}/status");

        if (! $response->successful()) {
            return null;
        }

        return $response->json();
    }

    /**
     * Membuat transaksi Midtrans Snap API (berlaku untuk Sandbox & Production).
     *
     * @return array{token: string, redirect_url: string}
     */
    public function createSnapTransaction(Order $order, Payment $payment, string $method): array
    {
        $serverKey = (string) config('services.midtrans.server_key');
        if ($serverKey === '') {
            throw new RuntimeException('MIDTRANS_SERVER_KEY belum diisi pada file .env.');
        }

        $enabledPayments = match ($method) {
            'qris' => ['other_qris', 'gopay', 'shopeepay'],
            'va_bsi' => ['bsi_va'],
            'va_jateng', 'va_mandiri' => ['echannel', 'bni_va', 'bri_va', 'bca_va'],
            'gopay' => ['gopay'],
            'dana' => ['shopeepay', 'other_qris'],
            default => ['other_qris', 'gopay', 'shopeepay', 'bca_va', 'bni_va', 'bri_va', 'echannel'],
        };

        $payload = [
            'transaction_details' => [
                'order_id' => $payment->ref,
                'gross_amount' => (int) $payment->amount,
            ],
            'enabled_payments' => $enabledPayments,
            'customer_details' => [
                'first_name' => $order->ship_name ?: ($order->user?->name ?? 'Pelanggan Buana'),
                'email' => $order->user?->email ?? 'pelanggan@buanagroup.id',
                'phone' => $order->ship_phone ?: ($order->user?->phone ?? '081234567890'),
            ],
            'callbacks' => [
                'finish' => route('payment.checkout', $order->code),
            ],
        ];

        $response = Http::withBasicAuth($serverKey, '')
            ->acceptJson()
            ->timeout(30)
            ->connectTimeout(15)
            ->withOptions(['force_ip_resolve' => 'v4'])
            ->post($this->snapApiUrl(), $payload);

        if (! $response->successful()) {
            $errorMessages = $response->json('error_messages');
            $detail = is_array($errorMessages) ? implode(', ', $errorMessages) : $response->body();

            throw new RuntimeException('Gagal membuat transaksi Midtrans: '.$detail);
        }

        return [
            'token' => (string) $response->json('token'),
            'redirect_url' => (string) $response->json('redirect_url'),
        ];
    }

    /**
     * Memverifikasi signature SHA-512 dari Webhook Notification Midtrans.
     */
    public function verifyWebhookSignature(string $orderId, string $statusCode, string $grossAmount, string $signatureKey): bool
    {
        $serverKey = (string) config('services.midtrans.server_key');
        if ($serverKey === '') {
            return false;
        }

        $expected = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);

        return hash_equals($expected, $signatureKey);
    }
}
