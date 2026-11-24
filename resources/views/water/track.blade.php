@extends('layouts.app')
@section('title', 'Detail Pesanan Air Mineral - ' . $order->code)

@section('content')
<div style="margin-bottom: 16px;">
    <a href="{{ route('water.index') }}" style="color:var(--muted); text-decoration:none; font-size:0.88rem; display:inline-flex; align-items:center; gap:4px;">
        &larr; Kembali ke Katalog Air Mineral
    </a>
    <div style="display:flex; justify-content:space-between; align-items:center; margin-top:6px;">
        <h1 style="margin:0; font-size:1.25rem;">Detail Pesanan Air Mineral</h1>
        <span class="badge" style="background:#dbeafe; color:#1e40af; font-size:0.8rem;">
            {{ $order->code }}
        </span>
    </div>
</div>

@if (session('success'))
    <div style="background:#ecfdf5; border:1px solid #a7f3d0; color:#065f46; padding:12px; border-radius:12px; margin-bottom:16px; font-size:0.88rem; display:flex; align-items:center; gap:8px;">
        <span>✅</span>
        <div>{{ session('success') }}</div>
    </div>
@endif

{{-- 1. KARTU STATUS PESANAN --}}
@php
    $isDone = $order->status === \App\Enums\OrderStatus::Done;
    $isCancelled = $order->status === \App\Enums\OrderStatus::Cancelled;
    $isShipped = in_array($order->status, [\App\Enums\OrderStatus::Shipped, \App\Enums\OrderStatus::Done], true);
    $isProcessing = in_array($order->status, [\App\Enums\OrderStatus::Paid, \App\Enums\OrderStatus::Processing, \App\Enums\OrderStatus::Shipped, \App\Enums\OrderStatus::Done], true);

    [$statusHeading, $statusSubtitle] = match ($order->status) {
        \App\Enums\OrderStatus::WaitingConfirmation => ['Menunggu Konfirmasi Admin', 'Pesanan borongan sedang dicek stok & biaya kirim oleh admin'],
        \App\Enums\OrderStatus::Pending => ['Menunggu Pembayaran', 'Silakan selesaikan pembayaran agar pesanan segera diproses'],
        \App\Enums\OrderStatus::Paid => ['Pembayaran Diterima', 'Pesanan sedang disiapkan oleh tim gudang Buana'],
        \App\Enums\OrderStatus::Processing => ['Diproses Buana', 'Pesanan sedang dipersiapkan dan dijadwalkan untuk pengantaran'],
        \App\Enums\OrderStatus::Shipped => ['Dalam Pengiriman', 'Pesanan sedang diantarkan menuju alamat tujuan Anda'],
        \App\Enums\OrderStatus::Done => ['Pesanan Selesai ✅', 'Serah terima galon & karton telah selesai dikonfirmasi'],
        \App\Enums\OrderStatus::Cancelled => ['Pesanan Dibatalkan', 'Pesanan ini telah dibatalkan'],
    };
@endphp
<div class="card" style="padding: 18px; margin-bottom: 16px; border-top: 4px solid {{ $isCancelled ? '#dc2626' : '#0f766e' }};">
    <div style="display:flex; justify-content:space-between; align-items:flex-start;">
        <div>
            <div style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:var(--muted); font-weight:700;">
                Status Pesanan ({{ $order->status->label() }})
            </div>
            <div style="font-size:1.15rem; font-weight:800; color:{{ $isCancelled ? '#dc2626' : '#0f766e' }}; margin-top:2px;">
                {{ $statusHeading }}
            </div>
            <div style="font-size:0.82rem; color:var(--muted); margin-top:2px;">
                {{ $statusSubtitle }}
            </div>
        </div>
    </div>

    {{-- Timeline Tahapan --}}
    <div style="display: flex; flex-direction: column; gap: 14px; margin-top: 18px; padding-left: 6px;">
        <div style="display: flex; gap: 12px;">
            <div style="width: 22px; height: 22px; border-radius: 50%; background: #10b981; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: bold; flex-shrink: 0;">✓</div>
            <div>
                <div style="font-weight: 700; font-size: 0.88rem; color: #0f172a;">Pesanan Diajukan</div>
                <div style="font-size: 0.75rem; color: var(--muted);">{{ $order->created_at->format('d M Y, H:i') }} WIB</div>
            </div>
        </div>

        <div style="display: flex; gap: 12px; opacity: {{ $isProcessing ? '1' : '0.45' }};">
            <div style="width: 22px; height: 22px; border-radius: 50%; background: {{ $isProcessing ? '#10b981' : '#cbd5e1' }}; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: bold; flex-shrink: 0;">{{ $isProcessing ? '✓' : '2' }}</div>
            <div>
                <div style="font-weight: 700; font-size: 0.88rem; color: #0f172a;">Diproses Buana</div>
                <div style="font-size: 0.75rem; color: var(--muted);">Pengecekan kualitas & penyiapan pesanan di gudang</div>
            </div>
        </div>

        <div style="display: flex; gap: 12px; opacity: {{ $isShipped ? '1' : '0.45' }};">
            <div style="width: 22px; height: 22px; border-radius: 50%; background: {{ $isShipped ? '#10b981' : '#cbd5e1' }}; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: bold; flex-shrink: 0;">{{ $isShipped ? '✓' : '3' }}</div>
            <div>
                <div style="font-weight: 700; font-size: 0.88rem; color: {{ $isShipped ? '#0f766e' : '#0f172a' }};">Dalam Pengiriman</div>
                <div style="font-size: 0.75rem; color: var(--muted);">Pesanan sedang menuju alamat tujuan Anda</div>
            </div>
        </div>

        <div style="display: flex; gap: 12px; opacity: {{ $isDone ? '1' : '0.45' }};">
            <div style="width: 22px; height: 22px; border-radius: 50%; background: {{ $isDone ? '#10b981' : '#cbd5e1' }}; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: bold; flex-shrink: 0;">{{ $isDone ? '✓' : '4' }}</div>
            <div>
                <div style="font-weight: 700; font-size: 0.88rem; color: #0f172a;">Pesanan Diterima di Tujuan</div>
                <div style="font-size: 0.75rem; color: var(--muted);">Serah terima galon & bukti terima pesanan</div>
            </div>
        </div>
    </div>

    {{-- KONFIRMASI PENERIMAAN PELANGGAN --}}
    @auth
        @if (! $isDone && ! $isCancelled && $order->status !== \App\Enums\OrderStatus::WaitingConfirmation && $order->user_id === auth()->id())
            <div style="margin-top: 18px; padding-top: 14px; border-top: 1px dashed #cbd5e1; display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
                <div style="font-size: 0.8rem; color: #475569;">
                    Apakah galon/air mineral sudah tiba dan diterima dengan baik?
                </div>
                <form method="POST" action="{{ route('water.status.update', $order->code) }}">
                    @csrf
                    <input type="hidden" name="status" value="4">
                    <button type="submit" class="btn" style="background: #10b981; padding: 8px 14px; font-size: 0.82rem;">
                        ✅ Konfirmasi Pesanan Diterima
                    </button>
                </form>
            </div>
        @endif
    @endauth
</div>

{{-- 2. RINCIAN PESANAN --}}
<div class="card" style="padding: 16px; margin-bottom: 20px;">
    <div style="font-weight: 700; font-size: 0.92rem; margin-bottom: 12px; color:#0f172a;">
        Rincian Pesanan
    </div>

    <div style="display: flex; flex-direction: column; gap: 8px;">
        @foreach ($order->items as $item)
            <div style="display: flex; justify-content: space-between; font-size: 0.88rem; padding-bottom: 6px; border-bottom: 1px solid #f1f5f9;">
                <div>
                    <span style="font-weight: 600;">{{ $item->product?->name ?? 'Produk' }}</span>
                    <span style="color: var(--muted); font-size: 0.8rem;"> &times; {{ $item->qty }}</span>
                </div>
                <div style="font-weight: 700;">
                    Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}
                </div>
            </div>
        @endforeach
    </div>

    <div style="margin-top: 12px; font-size: 0.85rem; color: #475569;">
        <div><b>Penerima:</b> {{ $order->ship_name }} ({{ $order->ship_phone }})</div>
        <div><b>Alamat:</b> {{ $order->ship_addr }}</div>
        @if ($order->note)
            <div style="margin-top: 4px; background: #f8fafc; padding: 6px 10px; border-radius: 6px; font-size: 0.8rem;">
                <b>Catatan:</b> {{ $order->note }}
            </div>
        @endif
    </div>

    <div style="border-top: 1px dashed #cbd5e1; margin-top: 12px; padding-top: 10px; display: flex; flex-direction: column; gap: 6px; font-size: 0.85rem;">
        <div style="display: flex; justify-content: space-between; color: #475569;">
            <span>Subtotal:</span>
            <span style="font-weight: 600; color: #0f172a;">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; color: #475569;">
            <span>Ongkos Kirim:</span>
            <span style="font-weight: 600; color: #0f766e;">{{ $order->ship_fee > 0 ? 'Rp '.number_format($order->ship_fee, 0, ',', '.') : 'GRATIS' }}</span>
        </div>
        @if ($order->discount_amount > 0)
            <div style="display: flex; justify-content: space-between; color: #047857; font-weight: 600;">
                <span>Potongan Voucher ({{ $order->discount_code }}):</span>
                <span>-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
            </div>
        @endif
        @if ($order->points_redeemed > 0)
            <div style="display: flex; justify-content: space-between; color: #b45309; font-weight: 600;">
                <span>Potongan Buana Points:</span>
                <span>-Rp {{ number_format($order->points_redeemed, 0, ',', '.') }}</span>
            </div>
        @endif
        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #e2e8f0; margin-top: 4px; padding-top: 8px;">
            <span style="font-weight: 700; font-size: 0.95rem;">Total Tagihan:</span>
            <span style="font-weight: 800; font-size: 1.15rem; color: #0f766e;">
                Rp {{ number_format($order->total, 0, ',', '.') }}
            </span>
        </div>
    </div>

    @php
        $paidRecord = $order->payments()->where('status', \App\Enums\PaymentStatus::Success)->latest('id')->first();
        $pendingTempo = $order->payments()->where('method', 'tempo_b2b')->where('status', \App\Enums\PaymentStatus::Pending)->latest('id')->first();
        $isWaitingAdmin = $order->status === \App\Enums\OrderStatus::WaitingConfirmation;
    @endphp
    <div style="margin-top: 12px; padding: 12px; border-radius: 12px; background: {{ $paidRecord ? '#ecfdf5' : ($pendingTempo ? '#eff6ff' : '#fffbeb') }}; border: 1px solid {{ $paidRecord ? '#a7f3d0' : ($pendingTempo ? '#bfdbfe' : '#fde68a') }}; display: flex; justify-content: space-between; align-items: center; gap: 10px; flex-wrap: wrap;">
        <div style="font-size: 0.8rem;">
            @if ($isWaitingAdmin)
                <b style="color: #92400e;">⏳ Menunggu Konfirmasi Admin</b>
                <div style="color: #b45309; font-size: 0.75rem;">Pesanan borongan sedang dicek stok &amp; biaya kirim final sebelum pembayaran dibuka</div>
            @elseif ($paidRecord)
                <b style="color: #065f46;">✅ Lunas via {{ strtoupper($paidRecord->method) }} ({{ $paidRecord->ref }})</b>
                @if ($order->points_earned > 0)
                    <div style="color: #047857; font-size: 0.75rem;">🪙 Reward: +{{ number_format($order->points_earned, 0, ',', '.') }} Buana Loyalty Points</div>
                @endif
            @elseif ($pendingTempo)
                <b style="color: #1e40af;">📄 Invoice Tempo Institusi / B2B ({{ $pendingTempo->ref }})</b>
                <div style="color: #1d4ed8; font-size: 0.75rem;">TOP maksimal 30 hari — menunggu pelunasan bagian keuangan</div>
            @else
                <b style="color: #92400e;">💳 Payment Gateway Terpadu & Tukar Poin</b>
                <div style="color: #b45309; font-size: 0.75rem;">Bayar via QRIS, Virtual Account, E-Wallet, atau Tempo B2B terverifikasi</div>
            @endif
        </div>
        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            @if ($isWaitingAdmin)
                <a href="{{ route('order.waiting-confirmation', $order->code) }}" class="btn" style="width: auto; padding: 8px 14px; font-size: 0.8rem; background: #d97706; text-decoration: none;">
                    ⏳ Cek Status Konfirmasi
                </a>
            @else
                @if ($paidRecord || $pendingTempo || $order->status === \App\Enums\OrderStatus::Done)
                    <a href="{{ route('payment.invoice', $order->code) }}" class="btn" style="width: auto; padding: 8px 14px; font-size: 0.8rem; background: #0D5A47; text-decoration: none;">
                        📄 Lihat Invoice
                    </a>
                @endif
                @if (! $paidRecord && $order->status !== \App\Enums\OrderStatus::Done)
                    <a href="{{ route('payment.checkout', $order->code) }}" class="btn" style="width: auto; padding: 8px 14px; font-size: 0.8rem; background: {{ $pendingTempo ? '#1d4ed8' : '#d97706' }}; text-decoration: none;">
                        {{ $pendingTempo ? '📄 Detail Tagihan' : '💳 Bayar / Tukar Poin' }}
                    </a>
                @endif
            @endif
        </div>
    </div>
</div>

<div style="display: flex; gap: 10px;">
    <a href="{{ route('water.index') }}" class="btn" style="background:#0f766e; text-decoration:none;">
        💧 Pesan Air Tambahan
    </a>
    <a href="{{ route('orders.history') }}" class="btn btn-ghost" style="text-decoration:none;">
        📋 Riwayat Pesanan
    </a>
</div>

<style>
    @keyframes pulse {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }
</style>
@endsection
