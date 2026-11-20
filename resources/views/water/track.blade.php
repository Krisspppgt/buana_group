@extends('layouts.app')
@section('title', 'Pelacakan Logistik - Pesanan ' . $order->code)

@section('content')
<div style="margin-bottom: 16px;">
    <a href="{{ route('water.index') }}" style="color:var(--muted); text-decoration:none; font-size:0.88rem; display:inline-flex; align-items:center; gap:4px;">
        &larr; Kembali ke Katalog Air Mineral
    </a>
    <div style="display:flex; justify-content:space-between; align-items:center; margin-top:6px;">
        <h1 style="margin:0; font-size:1.25rem;">🚚 Pelacakan Logistik Armada</h1>
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

{{-- 1. KARTU STATUS LIVE TRACKING --}}
@php
    $progressPercent = match ($order->status) {
        \App\Enums\OrderStatus::Pending, \App\Enums\OrderStatus::Paid => 35,
        \App\Enums\OrderStatus::Processing => 68,
        \App\Enums\OrderStatus::Shipped => 88,
        \App\Enums\OrderStatus::Done => 100,
        default => 0,
    };
    $isDone = $order->status === \App\Enums\OrderStatus::Done;
    $isShipped = in_array($order->status, [\App\Enums\OrderStatus::Shipped, \App\Enums\OrderStatus::Done], true);
@endphp
<div class="card" style="padding: 18px; margin-bottom: 16px; border-top: 4px solid #0f766e;">
    <div style="display:flex; justify-content:space-between; align-items:flex-start;">
        <div>
            <div style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:var(--muted); font-weight:700;">
                Status Pengiriman ({{ $order->status->label() }})
            </div>
            <div style="font-size:1.15rem; font-weight:800; color:#0f766e; margin-top:2px;">
                @if ($isDone)
                    Pesanan Telah Diterima ✅ (Armada Sedang Mengantar Selesai)
                @else
                    Armada Sedang Mengantar 🚀
                @endif
            </div>
            <div style="font-size:0.82rem; color:var(--muted); margin-top:2px;">
                @if ($isDone)
                    Serah terima galon & karton telah selesai dikonfirmasi
                @elseif ($order->status === \App\Enums\OrderStatus::Shipped)
                    Driver telah tiba di titik lokasi pengantaran
                @else
                    Estimasi tiba: <b>15 - 25 menit lagi</b>
                @endif
            </div>
        </div>
        <div style="width:12px; height:12px; border-radius:50%; background:#10b981; box-shadow:0 0 0 4px rgba(16,185,129,0.2); animation: pulse 1.5s infinite;"></div>
    </div>

    {{-- Visual Radar / Map Simulator --}}
    <div style="margin: 16px 0; background: #0f172a; border-radius: 12px; padding: 16px; color: #fff; position: relative; overflow: hidden;">
        <div style="position: absolute; right: -20px; bottom: -20px; font-size: 5rem; opacity: 0.1;">🗺️</div>
        <div style="display:flex; justify-content:space-between; align-items:center; font-size: 0.75rem; color: #94a3b8; font-weight: 600; margin-bottom: 8px;">
            <span>📡 LIVE GPS TRACKING ARMADA</span>
            <span style="color:#4ade80;">{{ $progressPercent }}% Perjalanan</span>
        </div>
        <div style="display: flex; align-items: center; justify-content: space-between; position: relative; z-index: 1;">
            <div>
                <div style="font-size: 0.72rem; color: #38bdf8;">TITIK ASAL</div>
                <div style="font-weight: 700; font-size: 0.88rem;">Gudang Air Unwahas</div>
                <div style="font-size: 0.7rem; color: #94a3b8;">Jl. Menoreh Tengah X</div>
            </div>
            <div style="font-size: 1.2rem; color: #38bdf8;">➔</div>
            <div style="text-align: right;">
                <div style="font-size: 0.72rem; color: #4ade80;">TUJUAN PENGANTARAN</div>
                <div style="font-weight: 700; font-size: 0.88rem;">{{ Str::limit($order->ship_name, 16) }}</div>
                <div style="font-size: 0.7rem; color: #94a3b8;">{{ Str::limit($order->ship_addr, 20) }}</div>
            </div>
        </div>
        <div style="margin-top: 12px; background: rgba(255,255,255,0.1); height: 6px; border-radius: 3px; overflow: hidden;">
            <div style="background: linear-gradient(90deg, #38bdf8, #4ade80); width: {{ $progressPercent }}%; height: 100%; border-radius: 3px; transition: width 0.4s ease;"></div>
        </div>
    </div>

    {{-- Timeline Tahapan --}}
    <div style="display: flex; flex-direction: column; gap: 14px; margin-top: 18px; padding-left: 6px;">
        <div style="display: flex; gap: 12px;">
            <div style="width: 22px; height: 22px; border-radius: 50%; background: #10b981; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: bold; flex-shrink: 0;">✓</div>
            <div>
                <div style="font-weight: 700; font-size: 0.88rem; color: #0f172a;">Pesanan Terkonfirmasi</div>
                <div style="font-size: 0.75rem; color: var(--muted);">{{ $order->created_at->format('d M Y, H:i') }} WIB</div>
            </div>
        </div>

        <div style="display: flex; gap: 12px;">
            <div style="width: 22px; height: 22px; border-radius: 50%; background: #10b981; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: bold; flex-shrink: 0;">✓</div>
            <div>
                <div style="font-weight: 700; font-size: 0.88rem; color: #0f172a;">Dimuat ke Armada Gudang</div>
                <div style="font-size: 0.75rem; color: var(--muted);">Galon & karton selesai pengecekan kualitas</div>
            </div>
        </div>

        <div style="display: flex; gap: 12px;">
            <div style="width: 22px; height: 22px; border-radius: 50%; background: {{ $isShipped ? '#10b981' : '#0f766e' }}; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: bold; flex-shrink: 0; box-shadow: 0 0 0 3px rgba(15,118,110,0.2);">{{ $isShipped ? '✓' : '3' }}</div>
            <div>
                <div style="font-weight: 700; font-size: 0.88rem; color: #0f766e;">Dalam Perjalanan Menuju Lokasi</div>
                <div style="font-size: 0.75rem; color: var(--muted);">Driver sedang menuju alamat tujuan Anda</div>
            </div>
        </div>

        <div style="display: flex; gap: 12px; opacity: {{ $isDone ? '1' : '0.45' }};">
            <div style="width: 22px; height: 22px; border-radius: 50%; background: {{ $isDone ? '#10b981' : '#cbd5e1' }}; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: bold; flex-shrink: 0;">{{ $isDone ? '✓' : '4' }}</div>
            <div>
                <div style="font-weight: 700; font-size: 0.88rem; color: #0f172a;">Pesanan Diterima di Tujuan</div>
                <div style="font-size: 0.75rem; color: var(--muted);">Serah terima galon & penandatanganan bukti kirim</div>
            </div>
        </div>
    </div>

    {{-- PANEL AKSI KURIR LOGISTIK & KONFIRMASI PELANGGAN --}}
    @auth
        @if (auth()->user()->isStaff())
            <div style="margin-top: 18px; padding-top: 14px; border-top: 1px dashed #cbd5e1;">
                <div style="font-size: 0.8rem; font-weight: 700; color: #0f766e; margin-bottom: 8px;">
                    🚛 Panel Kurir / Petugas Logistik: Update Status Armada
                </div>
                <form method="POST" action="{{ route('water.status.update', $order->code) }}" style="display: flex; gap: 8px; flex-wrap: wrap;">
                    @csrf
                    <select name="status" style="flex: 1; min-width: 180px; padding: 8px 12px; font-size: 0.85rem;">
                        <option value="2" @selected($order->status === \App\Enums\OrderStatus::Processing)>🚀 Sedang Mengantar (Dalam Perjalanan)</option>
                        <option value="3" @selected($order->status === \App\Enums\OrderStatus::Shipped)>📍 Armada Tiba di Lokasi Tujuan</option>
                        <option value="4" @selected($order->status === \App\Enums\OrderStatus::Done)>✅ Selesai Serah Terima Galon</option>
                    </select>
                    <button type="submit" class="btn" style="padding: 8px 16px; font-size: 0.84rem;">
                        Simpan Status
                    </button>
                </form>
            </div>
        @elseif (! $isDone && $order->user_id === auth()->id())
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

{{-- 2. INFORMASI DRIVER & ARMADA LOGISTIK --}}
<div class="card" style="padding: 16px; margin-bottom: 16px;">
    <div style="font-weight: 700; font-size: 0.92rem; margin-bottom: 10px; color:#0f172a;">
        Armada & Kurir Logistik Pengantar
    </div>
    <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 46px; height: 46px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                👨‍✈️
            </div>
            <div>
                <div style="font-weight: 700; font-size: 0.92rem;">Pak Bambang Susanto</div>
                <div style="font-size: 0.78rem; color: var(--muted);">Driver Armada Pick-Up Unwahas</div>
                <div style="font-size: 0.74rem; font-weight: 600; color: #0f766e; background: #f0fdfa; padding: 2px 6px; border-radius: 4px; display: inline-block; margin-top: 2px;">
                    Plat No: H 8219 WH
                </div>
            </div>
        </div>
        <a href="https://wa.me/6281234567890?text=Halo%20Pak%20Bambang,%20saya%20pemesan%20Air%20Unwahas%20{{ $order->code }}" target="_blank" style="background:#22c55e; color:#fff; padding:8px 12px; border-radius:8px; text-decoration:none; font-size:0.82rem; font-weight:700; display:flex; align-items:center; gap:4px;">
            💬 Hubungi
        </a>
    </div>
</div>

{{-- 3. RINCIAN PESANAN --}}
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

    <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px dashed #cbd5e1; margin-top: 12px; padding-top: 10px;">
        <span style="font-weight: 700; font-size: 0.95rem;">Total Tagihan:</span>
        <span style="font-weight: 800; font-size: 1.15rem; color: #0f766e;">
            Rp {{ number_format($order->total, 0, ',', '.') }}
        </span>
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
    <a href="{{ route('water.history') }}" class="btn btn-ghost" style="text-decoration:none;">
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
