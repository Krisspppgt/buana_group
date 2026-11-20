<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\Unit;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class PaymentController extends Controller
{
    /**
     * Menampilkan halaman Checkout Payment Gateway Terpadu (Midtrans Snap + Tempo B2B/Institusi) & Tukar Poin.
     */
    public function checkout(Request $request, string $code, MidtransService $midtrans): View
    {
        $user = $request->user();

        $order = Order::with(['items.product', 'payments', 'user'])
            ->where('code', $code)
            ->when(! $user->isAdmin() && ! $user->isStaff(), fn ($q) => $q->where('user_id', $user->id))
            ->firstOrFail();

        $syncMessage = null;
        $latestPayment = $this->syncPendingMidtransPayment($order, $midtrans, $syncMessage);

        if ($request->boolean('cek_status') && $syncMessage !== null) {
            session()->flash('info', $syncMessage);
        }

        $maxRedeemablePoints = min((int) $user->loyalty_points, (int) floor($order->subtotal * 0.5));
        $potentialEarnedPoints = max(0, (int) floor($order->total * 0.01));

        $midtransConfigured = $midtrans->isConfigured();
        $midtransClientKey = (string) config('services.midtrans.client_key');
        $midtransSnapJsUrl = $midtrans->snapJsUrl();
        $midtransIsProduction = $midtrans->isProduction();

        return view('payment.checkout', compact(
            'order',
            'user',
            'latestPayment',
            'maxRedeemablePoints',
            'potentialEarnedPoints',
            'midtransConfigured',
            'midtransClientKey',
            'midtransSnapJsUrl',
            'midtransIsProduction'
        ));
    }

    /**
     * Memproses pembayaran via Midtrans Snap (QRIS, VA, E-Wallet) atau Tempo Institusi/B2B terverifikasi.
     */
    public function process(Request $request, string $code, MidtransService $midtrans): RedirectResponse
    {
        $user = $request->user();

        $order = Order::with(['payments', 'user'])
            ->where('code', $code)
            ->when(! $user->isAdmin() && ! $user->isStaff(), fn ($q) => $q->where('user_id', $user->id))
            ->firstOrFail();

        if ($request->input('method') === 'paylater') {
            return back()->withErrors([
                'method' => 'Metode Paylater ditunda ke tahap lanjutan setelah pilot melalui mitra berizin.',
            ]);
        }

        if ($order->payments()->where('status', PaymentStatus::Success)->exists()
            || $order->payments()->where('method', 'tempo_b2b')->where('status', PaymentStatus::Pending)->exists()) {
            return back()->withErrors([
                'method' => 'Pesanan ini sudah lunas atau sudah memiliki invoice Tempo yang aktif.',
            ]);
        }

        $data = $request->validate([
            'method' => ['required', 'string', 'in:qris,va_bsi,va_jateng,va_mandiri,gopay,dana,tempo_b2b'],
            'use_points' => ['nullable', 'boolean'],
        ]);

        $isTempoMethod = $data['method'] === 'tempo_b2b';
        $orderOwner = $order->user ?? $user;

        $projectedDiscount = 0;
        if (! empty($data['use_points']) && $order->points_redeemed === 0 && $orderOwner->loyalty_points > 0) {
            $maxDiscount = (int) floor($order->subtotal * 0.5);
            $projectedDiscount = min((int) $orderOwner->loyalty_points, $maxDiscount);
        }
        $projectedTotal = max(0, ($order->subtotal + $order->ship_fee) - $projectedDiscount);

        // 1. Jika metode Tempo B2B / Institusi (wajib verifikasi admin & limit mencukupi)
        if ($isTempoMethod) {
            if (! $user->canUseTempo($projectedTotal)) {
                $errorMessage = (! $user->isCivitas() && ! $user->isVerifiedMitra() && ! $user->isAdmin())
                    ? 'Metode Tempo Institusi / B2B hanya tersedia untuk akun Civitas atau Mitra yang telah diverifikasi admin.'
                    : (! $user->tempo_approved || (int) $user->tempo_limit <= 0
                        ? 'Status civitas/mitra tidak otomatis memberikan fasilitas kredit. Pengajuan Tempo harus disetujui admin terlebih dahulu.'
                        : 'Limit Tempo Anda tidak mencukupi untuk pesanan ini (sisa limit: Rp '.number_format($user->remainingTempoLimit(), 0, ',', '.').').');

                return back()->withErrors([
                    'method' => $errorMessage,
                ]);
            }

            $payment = DB::transaction(function () use ($order, $orderOwner, $data) {
                $this->redeemOrderPoints($order, $orderOwner, ! empty($data['use_points']));

                $ref = 'INV-'.strtoupper(Str::random(6)).'-'.$order->code;
                $payment = Payment::create([
                    'order_id' => $order->id,
                    'ref' => $ref,
                    'method' => 'tempo_b2b',
                    'amount' => $order->total,
                    'status' => PaymentStatus::Pending,
                    'paid_at' => null,
                ]);

                if ($order->status === OrderStatus::Pending) {
                    $order->status = OrderStatus::Processing;
                }

                $order->save();

                return $payment;
            });

            $redirectRoute = match ($order->unit) {
                Unit::Air => route('water.show', $order->code),
                Unit::Food => route('food.show', $order->code),
                Unit::Konveksi => route('konveksi.tracking', $order->code),
                default => route('payment.checkout', $order->code),
            };

            return redirect($redirectRoute)->with(
                'success',
                "Invoice Tempo Institusi / B2B (Ref: {$payment->ref}, TOP 30 Hari) berhasil diterbitkan. Poin loyalitas akan masuk setelah tagihan dilunasi."
            );
        }

        // 2. Pembayaran via Midtrans Snap API (QRIS, Virtual Account, GoPay, DANA)
        if (! $midtrans->isConfigured()) {
            return back()->withErrors([
                'method' => 'Midtrans belum dikonfigurasi. Isi MIDTRANS_SERVER_KEY dan MIDTRANS_CLIENT_KEY di file .env terlebih dahulu.',
            ]);
        }

        $ref = 'MID-'.strtoupper(Str::random(6)).'-'.$order->code;
        $draftPayment = new Payment([
            'order_id' => $order->id,
            'ref' => $ref,
            'method' => $data['method'],
            'amount' => $projectedTotal,
        ]);

        try {
            $snap = $midtrans->createSnapTransaction($order, $draftPayment, $data['method']);
        } catch (Throwable $e) {
            return back()->withErrors([
                'method' => $e->getMessage(),
            ]);
        }

        DB::transaction(function () use ($order, $orderOwner, $data, $ref) {
            $this->redeemOrderPoints($order, $orderOwner, ! empty($data['use_points']));
            if ($order->isDirty()) {
                $order->save();
            }

            Payment::create([
                'order_id' => $order->id,
                'ref' => $ref,
                'method' => $data['method'],
                'amount' => $order->total,
                'status' => PaymentStatus::Pending,
            ]);
        });

        return redirect()->route('payment.checkout', $order->code)
            ->with('snap_token', $snap['token'])
            ->with('snap_redirect_url', $snap['redirect_url'])
            ->with('info', 'Token transaksi Midtrans dibuat. Silakan selesaikan pembayaran pada popup Midtrans.');
    }

    /**
     * Endpoint Webhook Notification dari Midtrans (Sandbox & Production).
     */
    public function webhook(Request $request, MidtransService $midtrans): JsonResponse
    {
        $orderId = (string) $request->input('order_id', '');

        // Balas 200 OK untuk ping "Test notification URL" dari Dashboard Midtrans (tanpa mengubah DB)
        if ($request->isMethod('GET') || $orderId === '' || str_starts_with($orderId, 'payment_notif_test_')) {
            return response()->json(['message' => 'Midtrans webhook endpoint is ready']);
        }

        $statusCode = (string) $request->input('status_code', '');
        $grossAmount = (string) $request->input('gross_amount', '');
        $signatureKey = (string) $request->input('signature_key', '');
        $transactionStatus = (string) $request->input('transaction_status', '');
        $fraudStatus = (string) $request->input('fraud_status', '');

        if (! $midtrans->verifyWebhookSignature($orderId, $statusCode, $grossAmount, $signatureKey)) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $payment = Payment::with('order.user')->where('ref', $orderId)->first();
        if (! $payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $isPaid = $transactionStatus === 'settlement'
            || ($transactionStatus === 'capture' && ($fraudStatus === '' || $fraudStatus === 'accept'));

        $isFailed = in_array($transactionStatus, ['deny', 'cancel', 'expire'], true);

        if ($isPaid) {
            $this->markPaymentAsSuccess($payment, (string) $request->input('payment_type', $payment->method));
        } elseif ($isFailed && $payment->status === PaymentStatus::Pending) {
            $payment->update(['status' => PaymentStatus::Failed]);
        }

        return response()->json(['message' => 'OK']);
    }

    private function redeemOrderPoints(Order $order, User $orderOwner, bool $usePoints): void
    {
        if (! $usePoints || $order->points_redeemed !== 0 || $orderOwner->loyalty_points <= 0) {
            return;
        }

        $maxDiscount = (int) floor($order->subtotal * 0.5);
        $redeemed = $orderOwner->redeemLoyaltyPoints(
            (int) $orderOwner->loyalty_points,
            $maxDiscount,
            $order->unit,
            $order->code,
            "Potongan Poin di {$order->unit->label()} #{$order->code}"
        );

        if ($redeemed > 0) {
            $order->points_redeemed = $redeemed;
            $order->total = max(0, ($order->subtotal + $order->ship_fee) - $redeemed);
        }
    }

    private function markPaymentAsSuccess(Payment $payment, string $paymentMethod): void
    {
        DB::transaction(function () use ($payment, $paymentMethod) {
            if ($payment->status !== PaymentStatus::Success) {
                $payment->update([
                    'status' => PaymentStatus::Success,
                    'paid_at' => now(),
                    'method' => substr($paymentMethod, 0, 15),
                ]);
            }

            $order = $payment->order;
            if ($order) {
                if ($order->status === OrderStatus::Pending) {
                    $order->status = OrderStatus::Processing;
                    $order->save();
                }

                $order->awardLoyaltyPoints();
            }
        });
    }

    private function syncPendingMidtransPayment(Order $order, MidtransService $midtrans, ?string &$syncMessage = null): ?Payment
    {
        $successPayment = $order->payments()
            ->where('status', PaymentStatus::Success)
            ->latest('id')
            ->first();

        if ($successPayment) {
            return $successPayment;
        }

        $pendingMidtransPayments = $order->payments()
            ->where('status', PaymentStatus::Pending)
            ->where('ref', 'like', 'MID-%')
            ->latest('id')
            ->get();

        $latestPayment = $order->payments()->latest('id')->first();

        if ($pendingMidtransPayments->isEmpty() || ! $midtrans->isConfigured()) {
            return $latestPayment;
        }

        $lastStatusNote = null;

        foreach ($pendingMidtransPayments as $pendingPayment) {
            try {
                $remote = $midtrans->getTransactionStatus($pendingPayment->ref);
            } catch (Throwable $e) {
                $lastStatusNote = 'Gagal menghubungi server Midtrans (koneksi timeout). Jika pembayaran sudah berhasil di Midtrans, Admin dapat menekan "Konfirmasi Lunas" di menu Pesanan.';

                break;
            }

            if (! is_array($remote)) {
                continue;
            }

            $txStatus = (string) ($remote['transaction_status'] ?? '');
            $fraudStatus = (string) ($remote['fraud_status'] ?? '');
            $apiStatusCode = (string) ($remote['status_code'] ?? '');
            $apiMessage = (string) ($remote['status_message'] ?? '');

            $isPaid = $txStatus === 'settlement'
                || ($txStatus === 'capture' && ($fraudStatus === '' || $fraudStatus === 'accept'));

            if ($isPaid) {
                $this->markPaymentAsSuccess($pendingPayment, (string) ($remote['payment_type'] ?? $pendingPayment->method));
                $pendingPayment->refresh();
                $order->refresh();
                $syncMessage = "Pembayaran ({$pendingPayment->ref}) berhasil dikonfirmasi lunas oleh Midtrans! Buana Loyalty Points telah ditambahkan.";

                return $pendingPayment;
            }

            if (in_array($txStatus, ['deny', 'cancel', 'expire'], true)) {
                $pendingPayment->update(['status' => PaymentStatus::Failed]);
                $lastStatusNote = "Transaksi ({$pendingPayment->ref}) berstatus {$txStatus} di Midtrans.";
            } elseif ($txStatus !== '') {
                $lastStatusNote = "Status transaksi {$pendingPayment->ref} di Midtrans masih {$txStatus}.";
            } elseif ($apiStatusCode !== '') {
                $lastStatusNote = "Respon Midtrans untuk {$pendingPayment->ref} ({$apiStatusCode}): {$apiMessage}";
            }
        }

        $syncMessage = $lastStatusNote
            ?? 'Transaksi belum tercatat lunas di Midtrans. Pastikan pembayaran di popup/simulator Midtrans sudah diselesaikan.';

        return $order->payments()->latest('id')->first();
    }

    /**
     * Menerima hasil callback onSuccess dari Midtrans Snap JS di browser.
     */
    public function confirmSnap(Request $request, string $code, MidtransService $midtrans): JsonResponse
    {
        $user = $request->user();

        $order = Order::with(['payments', 'user'])
            ->where('code', $code)
            ->when(! $user->isAdmin() && ! $user->isStaff(), fn ($q) => $q->where('user_id', $user->id))
            ->firstOrFail();

        $data = $request->validate([
            'order_id' => ['required', 'string', 'max:100'],
            'transaction_status' => ['required', 'string', 'max:40'],
            'payment_type' => ['nullable', 'string', 'max:40'],
            'fraud_status' => ['nullable', 'string', 'max:40'],
            'gross_amount' => ['nullable', 'numeric'],
        ]);

        $payment = $order->payments()->where('ref', $data['order_id'])->firstOrFail();

        if ($payment->status === PaymentStatus::Success) {
            return response()->json(['status' => 'already_paid']);
        }

        // Prioritaskan verifikasi langsung ke Midtrans Core API
        try {
            $remote = $midtrans->getTransactionStatus($payment->ref);
            if (is_array($remote) && isset($remote['transaction_status'])) {
                $txStatus = (string) $remote['transaction_status'];
                $fraudStatus = (string) ($remote['fraud_status'] ?? '');
                if ($txStatus === 'settlement' || ($txStatus === 'capture' && ($fraudStatus === '' || $fraudStatus === 'accept'))) {
                    $this->markPaymentAsSuccess($payment, (string) ($remote['payment_type'] ?? $payment->method));

                    return response()->json(['status' => 'paid']);
                }
            }
        } catch (Throwable) {
            // Jika server lokal timeout ke api.sandbox.midtrans.com pada mode Sandbox, izinkan konfirmasi dari Snap callback
            $isSandboxSuccess = ! $midtrans->isProduction()
                && in_array($data['transaction_status'], ['settlement', 'capture'], true)
                && ((int) ($data['gross_amount'] ?? $payment->amount) === (int) $payment->amount);

            if ($isSandboxSuccess) {
                $this->markPaymentAsSuccess($payment, (string) ($data['payment_type'] ?? $payment->method));

                return response()->json(['status' => 'paid_sandbox_fallback']);
            }
        }

        return response()->json(['status' => 'pending']);
    }

    /**
     * Menampilkan halaman Invoice resmi pesanan yang dapat dicetak atau disimpan sebagai PDF.
     */
    public function invoice(Request $request, string $code, MidtransService $midtrans): View
    {
        $user = $request->user();

        $order = Order::with(['items.product', 'payments', 'user'])
            ->where('code', $code)
            ->when(! $user->isAdmin() && ! $user->isStaff(), fn ($q) => $q->where('user_id', $user->id))
            ->firstOrFail();

        $syncMessage = null;
        $latestPayment = $this->syncPendingMidtransPayment($order, $midtrans, $syncMessage);

        if ($request->boolean('cek_status') && $syncMessage !== null) {
            session()->flash('info', $syncMessage);
        }

        return view('payment.invoice', compact('order', 'latestPayment'));
    }

    /**
     * Menampilkan halaman Dompet Buana Loyalty Points, riwayat mutasi poin, & katalog cross-selling 5 unit.
     */
    public function loyaltyIndex(Request $request): View
    {
        $user = $request->user();

        $histories = $user->pointHistories()
            ->latest('id')
            ->paginate(12);

        $totalEarned = (int) $user->pointHistories()->where('type', 'earn')->sum('points');
        $totalRedeemed = abs((int) $user->pointHistories()->where('type', 'redeem')->sum('points'));

        return view('payment.loyalty', compact('user', 'histories', 'totalEarned', 'totalRedeemed'));
    }
}
