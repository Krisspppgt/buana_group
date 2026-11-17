@extends('layouts.app')
@section('title', 'Beranda Super App - Buana Group')

@section('content')
@php
    $user = auth()->user();
    $role = $user->role;
    $isCivitas = $user->isCivitas();
    $isAdmin = $user->isAdmin();
@endphp

{{-- Kartu Info Profil & Status Ekosistem --}}
<div class="user-box">
    <div class="user-header">
        <div>
            <div style="font-size:0.8rem; color:var(--muted); font-weight:600;">Selamat datang,</div>
            <div style="font-size:1.15rem; font-weight:700; color:#0f172a;">{{ $user->name }}</div>
        </div>
        <div>
            @if ($isAdmin)
                <span class="badge badge-admin">👑 {{ $user->role_badge }}</span>
            @elseif ($isCivitas)
                <span class="badge badge-civitas">🎓 Civitas Unwahas</span>
            @elseif ($user->isCourier())
                <span class="badge badge-courier">🚚 Kurir Armada</span>
            @else
                <span class="badge badge-customer">👤 Pelanggan Umum</span>
            @endif
        </div>
    </div>

    <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-top: 10px; align-items: center;">
        <div class="points-pill">
            ⭐ <b>0</b> Poin Buana Loyalty
        </div>
        @if ($user->campus_id)
            <div style="font-size: 0.8rem; color: #475569; background: #f1f5f9; padding: 4px 10px; border-radius: 12px; font-weight: 500;">
                ID: {{ $user->campus_id }}
            </div>
        @endif
    </div>

    @if ($isAdmin)
        <a href="{{ url('/admin') }}" class="btn btn-admin">
            ⚙️ Buka Dashboard Manajemen Admin
        </a>
    @endif
</div>

{{-- Banner Notifikasi Harga Khusus Civitas --}}
@if ($isCivitas)
    <div class="eco-banner">
        <span style="font-size: 1.25rem;">✨</span>
        <div>
            <b>Akses Harga Khusus Civitas Aktif!</b>
            <div style="font-size: 0.8rem; opacity: 0.9;">Kamu berhak atas potongan harga khusus mahasiswa, dosen & pegawai Yayasan Wahid Hasyim.</div>
        </div>
    </div>
@endif

{{-- Judul Menu Layanan --}}
<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 12px;">
    <div>
        <h1 style="font-size: 1.15rem;">Modul Unit Usaha Utama</h1>
        <p class="sub" style="margin-bottom: 0;">Pilih layanan bisnis yang ingin kamu akses:</p>
    </div>
</div>

{{-- Grid 5 Unit Usaha Utama --}}
<div class="grid">
    {{-- 1. Air Mineral Unwahas --}}
    <a class="unit-card" href="{{ route('water.index') }}">
        <div class="unit-header">
            <div class="unit-icon" style="background:#e0f2fe;">💧</div>
            <div>
                <h3 class="unit-title">Air Mineral Unwahas</h3>
                <div style="font-size: 0.78rem; color: #0284c7; font-weight: 600;">Logistik Air Minum Kampus & Rumah Tangga</div>
            </div>
        </div>
        <p class="unit-desc">Pemesanan air minum kemasan botol, gelas, dan galon untuk kebutuhan insidental maupun langganan rutin.</p>
        <div class="tag-list">
            <span class="tag tag-highlight">Botol, Gelas & Galon</span>
            <span class="tag">Langganan Rutin</span>
            <span class="tag">Live Tracking</span>
            <span class="tag">B2B Bulk Order</span>
        </div>
    </a>

    {{-- 2. Buana Food Services --}}
    <a class="unit-card" href="{{ route('food.index') }}">
        <div class="unit-header">
            <div class="unit-icon" style="background:#fef3c7;">🍱</div>
            <div>
                <h3 class="unit-title">Buana Food Services</h3>
                <div style="font-size: 0.78rem; color: #d97706; font-weight: 600;">Katering, Snack Box & Prasmanan</div>
            </div>
        </div>
        <p class="unit-desc">Etalase katering acara, snack box, nasi kotak, kustomisasi anggaran (budget calculator), dan jadwal pengiriman bertahap.</p>
        <div class="tag-list">
            <span class="tag tag-highlight">Snack Box & Katering</span>
            <span class="tag">Budget Calculator</span>
            <span class="tag">Preferensi Diet</span>
            <span class="tag">Rundown Delivery</span>
        </div>
    </a>

    {{-- 3. Buana Tour Travel --}}
    <a class="unit-card" href="{{ route('tour.index') }}">
        <div class="unit-header">
            <div class="unit-icon" style="background:#dcfce7;">✈️</div>
            <div>
                <h3 class="unit-title">Buana Tour & Travel</h3>
                <div style="font-size: 0.78rem; color: #16a34a; font-weight: 600;">Wisata, Ziarah Wali & Paket Umrah</div>
            </div>
        </div>
        <p class="unit-desc">Etalase paket study tour, ziarah wali santri, pendaftaran rombongan massal (upload KTP/Paspor), dan skema cicilan Umrah.</p>
        <div class="tag-list">
            <span class="tag tag-highlight">Wisata & Umrah</span>
            <span class="tag">Pendaftaran Rombongan</span>
            <span class="tag">Tabungan & Cicilan</span>
        </div>
    </a>

    {{-- 4. Buana Ticketing --}}
    <a class="unit-card" href="{{ route('ticketing.index') }}">
        <div class="unit-header">
            <div class="unit-icon" style="background:#ede9fe;">🎫</div>
            <div>
                <h3 class="unit-title">Buana Ticketing</h3>
                <div style="font-size: 0.78rem; color: #7c3aed; font-weight: 600;">Agregator Transportasi & Tiket Event</div>
            </div>
        </div>
        <p class="unit-desc">Pemesanan tiket kereta api, pesawat, kapal laut, bus antarkota, tiket seminar/konser Yayasan, dan E-Boarding Pass digital.</p>
        <div class="tag-list">
            <span class="tag tag-highlight">Tiket Kereta & Pesawat</span>
            <span class="tag">Tiket Event Kampus</span>
            <span class="tag">E-Boarding Pass QR</span>
        </div>
    </a>

    {{-- 5. Buana Konveksi --}}
    <a class="unit-card" href="{{ route('konveksi.index') }}">
        <div class="unit-header">
            <div class="unit-icon" style="background:#fce7f3;">👕</div>
            <div>
                <h3 class="unit-title">Buana Konveksi</h3>
                <div style="font-size: 0.78rem; color: #db2777; font-weight: 600;">Jas Almamater, Seragam PDH/PDL & Kaos</div>
            </div>
        </div>
        <p class="unit-desc">Katalog bahan kain, sistem kuotasi & upload desain mockup, estimasi harga instan, dan pelacakan progres produksi real-time.</p>
        <div class="tag-list">
            <span class="tag tag-highlight">Almamater & PDH</span>
            <span class="tag">Upload Mockup</span>
            <span class="tag">Estimasi Otomatis</span>
            <span class="tag">Live Status Jahit</span>
        </div>
    </a>
</div>

{{-- Tombol Keluar --}}
<div style="margin-top: 24px; text-align: center;">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-ghost" style="max-width: 200px; margin: 0 auto;">
            🚪 Keluar Akun
        </button>
    </form>
    <div style="margin-top: 16px; font-size: 0.76rem; color: #94a3b8;">
        Ekosistem Digital PT Buana Sejahtera Semarang &copy; {{ date('Y') }}
    </div>
</div>
@endsection
