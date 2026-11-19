@extends('layouts.app')
@section('title', 'Detail Kuotasi - Buana Konveksi')

@section('content')
<div style="margin-bottom: 16px;">
    <a href="{{ route('konveksi.quotes') }}" style="color:var(--muted); text-decoration:none; font-size:0.88rem; display:inline-flex; align-items:center; gap:4px;">
        &larr; Kembali ke Daftar Kuotasi
    </a>
    <div style="display:flex; justify-content:space-between; align-items:center; margin-top:6px;">
        <h1 style="margin:0; font-size:1.25rem;">📄 Rincian Kuotasi Konveksi</h1>
        <span class="badge" style="background:#f1f5f9; color:#475569; font-size:0.8rem;">
            ID: #{{ $quote->id }}
        </span>
    </div>
</div>

@if (session('success'))
    <div style="background:#ecfdf5; border:1px solid #a7f3d0; color:#065f46; padding:12px; border-radius:12px; margin-bottom:16px; font-size:0.88rem;">
        {{ session('success') }}
    </div>
@endif

{{-- KARTU STATUS PENAWARAN --}}
<div class="card" style="padding: 18px; margin-bottom: 16px;">
    <div style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:var(--muted); font-weight:700;">
        Status Pengajuan
    </div>
    <div style="font-size:1.15rem; font-weight:800; color:#0f766e; margin-top:2px;">
        {{ $quote->status->label() }}
    </div>
    <div style="font-size:0.82rem; color:var(--muted); margin-top:2px;">
        Diajukan pada {{ $quote->created_at->format('d M Y, H:i') }} WIB
    </div>

    @if ($quote->order_id && $quote->order)
        <div style="margin-top: 12px; padding: 10px; background: #f0fdfa; border: 1px solid #a7f3d0; border-radius: 8px;">
            <div style="font-size: 0.8rem; font-weight: 700; color: #0f766e;">Pesanan Produksi Telah Dibuat!</div>
            <div style="font-size: 0.75rem; color: #065f46; margin-top: 2px;">Kode Order: <b>{{ $quote->order->code }}</b></div>
            <a href="{{ route('konveksi.tracking', $quote->order->code) }}" style="display:inline-block; margin-top:6px; font-size:0.78rem; color:#0f766e; font-weight:700;">
                Lihat Progres Produksi Transparan &rarr;
            </a>
        </div>
    @else
        <div style="margin-top: 14px; padding: 14px; background: #fdf2f8; border: 1px solid #fbcfe8; border-radius: 12px;">
            <div style="font-size: 0.85rem; font-weight: 700; color: #9d174d; margin-bottom: 4px;">
                🚀 Setujui Estimasi Harga & Lanjutkan ke Produksi
            </div>
            <div style="font-size: 0.78rem; color: #831843; margin-bottom: 10px;">
                Setujui kuotasi ini untuk langsung menerbitkan Order Produksi dan memantau progres jahit secara <i>real-time</i>.
            </div>
            <form method="POST" action="{{ route('konveksi.quotes.approve', $quote->id) }}" style="display: flex; flex-direction: column; gap: 8px;">
                @csrf
                <div>
                    <label style="margin: 0 0 4px; font-size: 0.78rem; color: #831843;">Alamat Pengiriman / Gedung Fakultas Tujuan</label>
                    <input type="text" name="ship_addr" value="Kampus UNWAHAS Jl. Menoreh Tengah X No. 22, Semarang" required style="padding: 8px 12px; font-size: 0.85rem;">
                </div>
                <button type="submit" class="btn" style="background: #db2777; align-self: flex-start; padding: 8px 16px; font-size: 0.84rem;">
                    ✅ Setujui Harga & Mulai Produksi Sekarang
                </button>
            </form>
        </div>
    @endif
</div>

{{-- RINCIAN SPESIFIKASI --}}
<div class="card" style="padding: 16px; margin-bottom: 20px;">
    <div style="font-weight: 700; font-size: 0.95rem; margin-bottom: 12px; color:#0f172a;">
        Spesifikasi Pakaian
    </div>

    <div style="font-size:0.88rem; display:flex; flex-direction:column; gap:8px;">
        <div><b>Item & Bahan:</b> {{ $quote->item }}</div>
        <div><b>Jumlah Pesanan:</b> {{ $quote->qty }} pcs</div>
        <div><b>Rincian Ukuran / Catatan:</b> {{ $quote->sizes }}</div>
    </div>

    @if ($quote->design)
        <div style="margin-top: 14px; border-top: 1px solid #f1f5f9; padding-top: 12px;">
            <div style="font-size: 0.82rem; font-weight: 700; margin-bottom: 6px;">Mockup Desain Diunggah:</div>
            <img src="{{ asset('storage/' . $quote->design) }}" alt="Mockup Desain" style="max-width: 100%; border-radius: 8px; border: 1px solid #e2e8f0; max-height: 240px; object-fit: contain; background: #f8fafc;">
        </div>
    @endif

    <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px dashed #cbd5e1; margin-top: 14px; padding-top: 10px;">
        <div>
            <div style="font-weight: 700; font-size: 0.95rem;">Estimasi Total:</div>
            @if ($quote->final_price)
                <div style="font-size:0.72rem; color:#16a34a; font-weight:600;">Penawaran Final Disetujui</div>
            @endif
        </div>
        <span style="font-weight: 800; font-size: 1.15rem; color: #0f766e;">
            Rp {{ number_format($quote->final_price ?? $quote->est_price, 0, ',', '.') }}
        </span>
    </div>
</div>

<div style="display: flex; gap: 10px;">
    <a href="https://wa.me/6281234567890?text=Halo%20Admin%20Konveksi,%20saya%20ingin%20konsultasi%20kuotasi%20%23{{ $quote->id }}" target="_blank" class="btn" style="background:#22c55e; text-decoration:none; display:flex; align-items:center; justify-content:center; gap:6px;">
        💬 Hubungi Tim Konveksi (WhatsApp)
    </a>
</div>
@endsection
