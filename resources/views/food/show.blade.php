@extends('layouts.app')
@section('title', 'Detail Pesanan Katering - ' . $order->code)

@section('content')
<div style="margin-bottom: 16px;">
    <a href="{{ route('food.index') }}" style="color:var(--muted); text-decoration:none; font-size:0.88rem; display:inline-flex; align-items:center; gap:4px;">
        &larr; Kembali ke Katalog Katering
    </a>
    <div style="display:flex; justify-content:space-between; align-items:center; margin-top:6px;">
        <h1 style="margin:0; font-size:1.25rem;">🍱 Status Katering & Rundown</h1>
        <span class="badge" style="background:#fef3c7; color:#92400e; font-size:0.8rem;">
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

{{-- KARTU STATUS PENGERJAAN DAPUR & RUNDOWN --}}
<div class="card" style="padding: 18px; margin-bottom: 16px; border-top: 4px solid #d97706;">
    <div style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:var(--muted); font-weight:700;">
        Status Pemrosesan Katering ({{ $order->status->label() }})
    </div>
    <div style="font-size:1.15rem; font-weight:800; color:#b45309; margin-top:2px;">
        @if ($order->status === \App\Enums\OrderStatus::Done)
            ✅ Seluruh Sesi Rundown Katering Selesai Diantar
        @elseif ($order->status === \App\Enums\OrderStatus::Shipped)
            🚚 Armada Katering Sedang Mengantar Sesuai Rundown
        @else
            👨‍🍳 Dapur Sedang Mempersiapkan Menu
        @endif
    </div>
    <div style="font-size:0.82rem; color:var(--muted); margin-top:2px;">
        Jadwal Pengantaran Acara: <b>{{ $order->send_at?->format('d M Y, H:i') ?? '-' }} WIB</b>
    </div>

    {{-- Info Catatan Rundown --}}
    @if ($order->note)
        <div style="margin-top:14px; background:#fffbeb; border:1px solid #fde68a; border-radius:10px; padding:12px; font-size:0.84rem; color:#78350f;">
            <div style="font-weight:700; margin-bottom:4px;">📋 Catatan Khusus Rundown & Diet:</div>
            <div>{{ $order->note }}</div>
        </div>
    @endif

    @auth
        @if ($order->status !== \App\Enums\OrderStatus::Done && (auth()->user()->isStaff() || $order->user_id === auth()->id()))
            <div style="margin-top: 14px; padding-top: 12px; border-top: 1px dashed #fde68a; display: flex; justify-content: space-between; align-items: center; gap: 10px; flex-wrap: wrap;">
                <span style="font-size: 0.8rem; color: #92400e; font-weight: 600;">
                    Update Status Pengiriman Rundown Acara:
                </span>
                <form method="POST" action="{{ route('food.status.update', $order->code) }}" style="display: flex; gap: 8px;">
                    @csrf
                    @if (auth()->user()->isStaff())
                        <select name="status" style="padding: 6px 10px; font-size: 0.82rem; width: auto;">
                            <option value="2" @selected($order->status === \App\Enums\OrderStatus::Processing)>👨‍🍳 Diproses Dapur</option>
                            <option value="3" @selected($order->status === \App\Enums\OrderStatus::Shipped)>🚚 Dikirim Bertahap</option>
                            <option value="4" @selected($order->status === \App\Enums\OrderStatus::Done)>✅ Selesai Semua Sesi</option>
                        </select>
                        <button type="submit" class="btn" style="background: #d97706; padding: 6px 14px; font-size: 0.8rem;">Update</button>
                    @else
                        <input type="hidden" name="status" value="4">
                        <button type="submit" class="btn" style="background: #10b981; padding: 6px 14px; font-size: 0.8rem;">
                            ✅ Konfirmasi Katering Diterima
                        </button>
                    @endif
                </form>
            </div>
        @endif
    @endauth
</div>

{{-- RINCIAN MENU --}}
<div class="card" style="padding: 16px; margin-bottom: 20px;">
    <div style="font-weight: 700; font-size: 0.92rem; margin-bottom: 12px; color:#0f172a;">
        Rincian Menu Katering
    </div>

    <div style="display: flex; flex-direction: column; gap: 8px;">
        @foreach ($order->items as $item)
            <div style="display: flex; justify-content: space-between; font-size: 0.88rem; padding-bottom: 6px; border-bottom: 1px solid #f1f5f9;">
                <div>
                    <span style="font-weight: 600;">{{ $item->product?->name }}</span>
                    <span style="color: var(--muted); font-size: 0.8rem;"> &times; {{ $item->qty }} {{ $item->product?->uom }}</span>
                </div>
                <div style="font-weight: 700;">
                    Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}
                </div>
            </div>
        @endforeach
    </div>

    <div style="margin-top: 14px; font-size: 0.85rem; color: #475569;">
        <div><b>PIC Acara:</b> {{ $order->ship_name }} ({{ $order->ship_phone }})</div>
        <div><b>Lokasi:</b> {{ $order->ship_addr }}</div>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px dashed #cbd5e1; margin-top: 12px; padding-top: 10px;">
        <span style="font-weight: 700; font-size: 0.95rem;">Total Tagihan:</span>
        <span style="font-weight: 800; font-size: 1.15rem; color: #0f766e;">
            Rp {{ number_format($order->total, 0, ',', '.') }}
        </span>
    </div>

    @php
        $paidRecord = $order->payments()->where('status', \App\Enums\PaymentStatus::Success)->latest('id')->first();
        $pendingTempo = $order->payments()->where('method', 'tempo_b2b')->where('status', \App\Enums\PaymentStatus::Pending)->latest('id')->first();
    @endphp
    <div style="margin-top: 12px; padding: 12px; border-radius: 12px; background: {{ $paidRecord ? '#ecfdf5' : ($pendingTempo ? '#eff6ff' : '#fffbeb') }}; border: 1px solid {{ $paidRecord ? '#a7f3d0' : ($pendingTempo ? '#bfdbfe' : '#fde68a') }}; display: flex; justify-content: space-between; align-items: center; gap: 10px; flex-wrap: wrap;">
        <div style="font-size: 0.8rem;">
            @if ($paidRecord)
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
        </div>
    </div>
</div>

<div style="display: flex; gap: 10px;">
    <a href="{{ route('food.index') }}" class="btn" style="background:#d97706; text-decoration:none;">
        🍱 Pesan Menu Lain
    </a>
    <a href="{{ route('food.history') }}" class="btn btn-ghost" style="text-decoration:none;">
        📋 Riwayat Katering
    </a>
</div>
@endsection
