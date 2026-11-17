@extends('layouts.app')
@section('title', 'Buana Group - Dari Kebutuhan Kampus Menuju Pasar yang Lebih Luas')

@section('content')

{{-- ========================================================================= --}}
{{-- 1. TAMPILAN RESPONSIVE MOBILE (Persis 100% Mockup Slide 8)                --}}
{{-- Ditampilkan pada perangkat smartphone / layar < md                        --}}
{{-- ========================================================================= --}}
<div class="block md:hidden px-5 pt-2 pb-24 bg-white min-h-screen">
    {{-- Mobile Header: Logo + Bell Notification --}}
    <div class="flex justify-between items-center py-2">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-[#0D5A47] flex items-center justify-center shadow-xs">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18m-9-9c0 4.97 4.03 9 9 9 4.97 0 9-4.03 9-9 0-4.97-4.03-9-9-9-4.97 0-9 4.03-9 9z" />
                </svg>
            </div>
            <div>
                <h1 class="text-[17px] font-bold text-[#0D5A47] leading-none tracking-tight">Buana Group</h1>
                <span class="text-[11px] text-gray-500 leading-none">Indonesian Buana Group</span>
            </div>
        </div>

        <button type="button" class="relative p-2 text-gray-600 hover:text-gray-900 focus:outline-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
        </button>
    </div>

    {{-- Segmented Tabs: UNWAHAS (Active) | Umum --}}
    <div class="mt-3.5 bg-gray-100/90 p-1 rounded-full flex gap-1 border border-gray-200/60" id="campus-toggle-mobile">
        <button type="button" class="flex-1 py-1.5 px-4 rounded-full text-xs font-semibold bg-[#0D5A47] text-white shadow-xs text-center transition" onclick="switchMobileTab('unwahas')">
            UNWAHAS
        </button>
        <button type="button" class="flex-1 py-1.5 px-4 rounded-full text-xs font-medium text-gray-600 hover:text-gray-900 text-center transition" onclick="switchMobileTab('umum')">
            Umum
        </button>
    </div>

    {{-- Search Bar --}}
    <div class="relative mt-3.5">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </span>
        <input type="text" placeholder="Cari layanan" class="w-full bg-gray-50/80 border border-gray-200/90 rounded-2xl pl-10 pr-4 py-2.5 text-xs text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#0D5A47] focus:border-[#0D5A47] transition">
    </div>

    {{-- 5 Unit Bisnis Grid (Horizontal Icon Row Sesuai Mockup) --}}
    <div class="mt-6 grid grid-cols-5 gap-2 text-center">
        <a href="{{ route('water.index') }}" class="group flex flex-col items-center">
            <div class="w-13 h-13 rounded-2xl bg-emerald-50/70 border border-emerald-100 flex items-center justify-center text-[#0D5A47] group-hover:scale-105 transition shadow-2xs">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 3h6m-5 0v3a2 2 0 01-1 1.732V19a2 2 0 002 2h2a2 2 0 002-2V7.732A2 2 0 0114 6V3m-4 8h4" />
                </svg>
            </div>
            <span class="mt-2 text-[11px] font-medium text-gray-700 leading-tight">Air Mineral</span>
        </a>

        <a href="{{ route('food.index') }}" class="group flex flex-col items-center">
            <div class="w-13 h-13 rounded-2xl bg-emerald-50/70 border border-emerald-100 flex items-center justify-center text-[#0D5A47] group-hover:scale-105 transition shadow-2xs">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v2m-8 10a8 8 0 0116 0H4zm-1 3h18" />
                </svg>
            </div>
            <span class="mt-2 text-[11px] font-medium text-gray-700 leading-tight">Katering</span>
        </a>

        <a href="{{ route('tour.index') }}" class="group flex flex-col items-center">
            <div class="w-13 h-13 rounded-2xl bg-emerald-50/70 border border-emerald-100 flex items-center justify-center text-[#0D5A47] group-hover:scale-105 transition shadow-2xs">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 17l6-6 4 4 8-8M3 17h18M18 7a2 2 0 110-4 2 2 0 010 4z" />
                </svg>
            </div>
            <span class="mt-2 text-[11px] font-medium text-gray-700 leading-tight">Wisata</span>
        </a>

        <a href="{{ route('ticketing.index') }}" class="group flex flex-col items-center">
            <div class="w-13 h-13 rounded-2xl bg-emerald-50/70 border border-emerald-100 flex items-center justify-center text-[#0D5A47] group-hover:scale-105 transition shadow-2xs">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 010 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 010-4V7a2 2 0 00-2-2H5z" />
                </svg>
            </div>
            <span class="mt-2 text-[11px] font-medium text-gray-700 leading-tight">Tiket</span>
        </a>

        <a href="{{ route('konveksi.index') }}" class="group flex flex-col items-center">
            <div class="w-13 h-13 rounded-2xl bg-emerald-50/70 border border-emerald-100 flex items-center justify-center text-[#0D5A47] group-hover:scale-105 transition shadow-2xs">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6l4-2 4 3 4-3 4 2v4l-3 1v9H7v-9L4 10V6z" />
                </svg>
            </div>
            <span class="mt-2 text-[11px] font-medium text-gray-700 leading-tight">Konveksi</span>
        </a>
    </div>

    {{-- Section "Pesan lagi" Sesuai Mockup --}}
    <div class="mt-8">
        <h2 class="text-sm font-bold text-gray-900 mb-3">Pesan lagi</h2>
        
        <a href="{{ route('order.campus.rincian') }}" class="flex items-center justify-between p-3 bg-white border border-gray-100 rounded-2xl shadow-[0_2px_8px_-2px_rgba(0,0,0,0.06)] hover:border-emerald-200 transition group">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/paket_rapat_fakultas.jpg') }}" alt="Paket Rapat Fakultas" class="w-14 h-14 rounded-xl object-cover border border-gray-100">
                <div>
                    <h3 class="text-xs font-bold text-gray-900 group-hover:text-[#0D5A47] transition">Paket Rapat Fakultas</h3>
                    <p class="text-[11px] text-gray-500 mt-0.5">Mulai Rp15.000 / orang</p>
                </div>
            </div>
            
            <div class="text-gray-400 group-hover:text-[#0D5A47] transition pr-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </a>
    </div>
</div>

{{-- ========================================================================= --}}
{{-- 2. TAMPILAN RESPONSIVE WEB DESKTOP (Berdasarkan PDF Konsep Aplikasi)      --}}
{{-- Ditampilkan pada layar desktop / tablet lebar (>= md)                     --}}
{{-- ========================================================================= --}}
<div class="hidden md:block max-w-7xl mx-auto px-6 py-8">
    
    {{-- A. Strategic Hero Section (Slide 1 & Slide 2 PDF) --}}
    <div class="relative bg-gradient-to-br from-[#144b3c] to-[#0D5A47] text-white rounded-3xl p-8 lg:p-10 shadow-xl overflow-hidden mb-10">
        {{-- Background Geometric Watermark --}}
        <div class="absolute -right-12 -bottom-16 w-80 h-80 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute right-10 top-1/2 -translate-y-1/2 opacity-10 pointer-events-none hidden lg:block">
            <svg class="w-80 h-80" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2L4 7v10l8 5 8-5V7l-8-5zm0 2.2l6 3.75v7.8L12 19.5 6 15.75v-7.8l6-3.75z"/>
            </svg>
        </div>

        <div class="relative z-10 max-w-3xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-200 border border-emerald-400/30 text-xs font-semibold mb-4">
                <span>🏢</span>
                <span>PT BUANA SEJAHTERA SEMARANG • YAYASAN WAHID HASYIM</span>
            </div>
            <h1 class="text-3xl lg:text-4xl font-extrabold tracking-tight leading-tight">
                Dari kebutuhan kampus menuju pasar yang lebih luas
            </h1>
            <p class="mt-3 text-emerald-100 text-sm lg:text-base leading-relaxed">
                Platform terpadu pemesanan resmi civitas akademika UNWAHAS. Menjaga margin terukur, pengiriman tepat waktu, serta skema pembayaran tempo B2B institusi yang aman dan tertib rekonsiliasi.
            </p>

            {{-- 3 Value Points dari Slide 2 --}}
            <div class="mt-6 grid grid-cols-3 gap-4 pt-4 border-t border-emerald-700/60 text-xs">
                <div class="bg-emerald-950/40 p-3 rounded-xl border border-emerald-600/30">
                    <span class="text-emerald-300 font-bold block mb-0.5">Pasar Awal Terukur</span>
                    <span class="text-emerald-100/80">Kebutuhan fakultas, dekanat, unit kerja, dosen & mahasiswa.</span>
                </div>
                <div class="bg-emerald-950/40 p-3 rounded-xl border border-emerald-600/30">
                    <span class="text-emerald-300 font-bold block mb-0.5">Tempo Terkendali</span>
                    <span class="text-emerald-100/80">Maksimal 30 hari via SPK terverifikasi tanpa kebocoran arus kas.</span>
                </div>
                <div class="bg-emerald-950/40 p-3 rounded-xl border border-emerald-600/30">
                    <span class="text-emerald-300 font-bold block mb-0.5">Satu Pintu Layanan</span>
                    <span class="text-emerald-100/80">5 unit usaha tersinkronisasi dalam katalog & invoice terpusat.</span>
                </div>
            </div>
        </div>
    </div>

    {{-- B. Featured Quick Order & Live Calculation (Slide 8 Adapted to Web Dashboard) --}}
    <div class="mb-12">
        <div class="flex justify-between items-end mb-4">
            <div>
                <span class="text-xs font-bold text-[#0D5A47] uppercase tracking-wider">Fast Ordering Kampus</span>
                <h2 class="text-xl font-bold text-gray-900">Pemesanan Rutin: Paket Rapat Fakultas</h2>
                <p class="text-xs text-gray-500">Pesan langsung paket konsumsi rapat dengan harga khusus civitas dan jadwal presisi.</p>
            </div>
            <a href="{{ route('order.campus.rincian') }}" class="text-xs font-semibold text-[#0D5A47] hover:underline flex items-center gap-1">
                Buka Formulir Penuh <span>&rarr;</span>
            </a>
        </div>

        <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden grid grid-cols-12">
            {{-- Left Column: Product Showcase --}}
            <div class="col-span-5 p-6 bg-slate-50/60 border-r border-gray-100 flex flex-col justify-between">
                <div>
                    <div class="relative rounded-2xl overflow-hidden border border-gray-200 mb-4 bg-white">
                        <img src="{{ asset('images/paket_rapat_fakultas.jpg') }}" alt="Paket Rapat Fakultas" class="w-full h-48 object-cover">
                        <span class="absolute top-3 left-3 px-3 py-1 bg-[#0D5A47] text-white font-bold text-xs rounded-full shadow-sm">
                            ⭐ Terlaris di UNWAHAS
                        </span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Paket Rapat Fakultas</h3>
                    <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                        Kombinasi snack box higienis (3 macam kue pilihan) + Air Mineral Unwahas Botol Segar. Dirancang khusus untuk efisiensi rapat dekanat, prodi, dan seminar kampus.
                    </p>
                </div>

                <div class="mt-4 pt-4 border-t border-gray-200 flex items-center justify-between text-xs">
                    <span class="text-gray-500">Harga Satuan Civitas:</span>
                    <span class="text-base font-extrabold text-[#0D5A47]">Rp 15.000 <span class="text-xs font-normal text-gray-500">/ porsi</span></span>
                </div>
            </div>

            {{-- Right Column: Live Web Fast-Order Form --}}
            <div class="col-span-7 p-6 flex flex-col justify-between">
                <form id="desktopFastOrderForm" method="POST" action="{{ route('order.campus.store') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="qty" id="deskInputQty" value="40">
                    <input type="hidden" name="schedule" id="deskInputSchedule" value="16 Nov 2026 · 09.00 WIB">

                    <div class="grid grid-cols-2 gap-4">
                        {{-- Stepper Jumlah --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 block mb-1">Jumlah Pesanan (Porsi)</label>
                            <div class="flex items-center bg-gray-50 border border-gray-200 rounded-xl p-1.5">
                                <button type="button" onclick="changeDeskQty(-5)" class="w-8 h-8 rounded-lg bg-white shadow-xs text-gray-700 font-bold hover:bg-gray-100 transition">&minus;</button>
                                <span id="deskQtyDisplay" class="flex-1 text-center font-bold text-sm text-gray-900">40</span>
                                <button type="button" onclick="changeDeskQty(5)" class="w-8 h-8 rounded-lg bg-white shadow-xs text-gray-700 font-bold hover:bg-gray-100 transition">&#43;</button>
                            </div>
                        </div>

                        {{-- Jadwal Pengiriman --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 block mb-1">Jadwal Pengantaran</label>
                            <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-xs text-gray-800">
                                <svg class="w-4 h-4 text-[#0D5A47] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="font-medium">16 Nov 2026 · 09.00 WIB</span>
                            </div>
                        </div>
                    </div>

                    {{-- Lokasi Gedung / Ruangan --}}
                    <div>
                        <label class="text-xs font-bold text-gray-700 block mb-1">Lokasi Gedung / Fakultas Pengantaran</label>
                        <select name="location" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs font-medium text-gray-800 focus:outline-none focus:ring-1 focus:ring-[#0D5A47]">
                            <option value="Fakultas Farmasi UNWAHAS" selected>Fakultas Farmasi UNWAHAS</option>
                            <option value="Fakultas Kedokteran UNWAHAS">Fakultas Kedokteran UNWAHAS</option>
                            <option value="Fakultas Teknik UNWAHAS">Fakultas Teknik UNWAHAS</option>
                            <option value="Fakultas Ekonomi & Bisnis UNWAHAS">Fakultas Ekonomi & Bisnis UNWAHAS</option>
                            <option value="Fakultas Agama Islam UNWAHAS">Fakultas Agama Islam UNWAHAS</option>
                            <option value="Gedung Rektorat UNWAHAS">Gedung Rektorat UNWAHAS</option>
                        </select>
                    </div>

                    {{-- Price Summary Box --}}
                    <div class="bg-emerald-50/70 border border-emerald-200/60 rounded-2xl p-4 flex items-center justify-between">
                        <div>
                            <span class="text-xs text-gray-600 block">Kalkulasi Tagihan Tempo:</span>
                            <span id="deskFormula" class="text-xs font-semibold text-gray-800">40 porsi &times; Rp 15.000</span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-gray-500 block">Total Pembayaran:</span>
                            <span id="deskTotal" class="text-xl font-extrabold text-[#0D5A47]">Rp 600.000</span>
                        </div>
                    </div>

                    {{-- Action Row --}}
                    <div class="flex items-center gap-4 pt-2">
                        <button type="submit" class="flex-1 py-3 bg-[#0D5A47] hover:bg-[#084032] text-white font-bold rounded-xl text-xs shadow-sm transition">
                            Ajukan Pesanan Cepat
                        </button>
                        <a href="{{ route('order.campus.rincian') }}" class="px-5 py-3 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl text-xs hover:bg-gray-50 transition">
                            Kustomisasi Pesanan
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- C. Lima Unit Usaha Dalam Satu Pintu (Slide 3 & 5 PDF) --}}
    <div class="mb-14">
        <div class="mb-6">
            <span class="text-xs font-bold text-[#0D5A47] uppercase tracking-wider">Ekosistem Bisnis</span>
            <h2 class="text-xl font-bold text-gray-900">Lima Unit Usaha Dalam Satu Pintu Pemesanan</h2>
            <p class="text-xs text-gray-500">Mulai dari kebutuhan rutin harian hingga agenda besar akademik UNWAHAS.</p>
        </div>

        <div class="grid grid-cols-5 gap-5">
            {{-- 1. Air Mineral --}}
            <a href="{{ route('water.index') }}" class="group bg-white p-5 rounded-2xl border border-gray-200 shadow-2xs hover:shadow-md hover:border-emerald-300 transition flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center text-xl mb-3 group-hover:scale-105 transition">
                        💧
                    </div>
                    <h3 class="font-bold text-sm text-gray-900 group-hover:text-[#0D5A47] transition">Air Mineral Unwahas</h3>
                    <p class="text-xs text-gray-500 mt-1 line-clamp-2">Pemesanan rutin galon 19L & karton botol/gelas untuk rapat dan kantor.</p>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs font-bold text-[#0D5A47]">
                    <span>Buka Katalog</span>
                    <span>&rarr;</span>
                </div>
            </a>

            {{-- 2. Buana Food --}}
            <a href="{{ route('food.index') }}" class="group bg-white p-5 rounded-2xl border border-gray-200 shadow-2xs hover:shadow-md hover:border-emerald-300 transition flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center text-xl mb-3 group-hover:scale-105 transition">
                        🍱
                    </div>
                    <h3 class="font-bold text-sm text-gray-900 group-hover:text-[#0D5A47] transition">Buana Food Services</h3>
                    <p class="text-xs text-gray-500 mt-1 line-clamp-2">Katering, snack box, nasi kotak & menu anggaran sesuai rundown acara.</p>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs font-bold text-[#0D5A47]">
                    <span>Buka Katalog</span>
                    <span>&rarr;</span>
                </div>
            </a>

            {{-- 3. Tour & Travel --}}
            <a href="{{ route('tour.index') }}" class="group bg-white p-5 rounded-2xl border border-gray-200 shadow-2xs hover:shadow-md hover:border-emerald-300 transition flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-[#0D5A47] flex items-center justify-center text-xl mb-3 group-hover:scale-105 transition">
                        ✈️
                    </div>
                    <h3 class="font-bold text-sm text-gray-900 group-hover:text-[#0D5A47] transition">Buana Tour & Travel</h3>
                    <p class="text-xs text-gray-500 mt-1 line-clamp-2">Wisata, ziarah wali, KKL rombongan fakultas & tabungan cicilan Umrah.</p>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs font-bold text-[#0D5A47]">
                    <span>Buka Katalog</span>
                    <span>&rarr;</span>
                </div>
            </a>

            {{-- 4. Ticketing --}}
            <a href="{{ route('ticketing.index') }}" class="group bg-white p-5 rounded-2xl border border-gray-200 shadow-2xs hover:shadow-md hover:border-emerald-300 transition flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center text-xl mb-3 group-hover:scale-105 transition">
                        🎫
                    </div>
                    <h3 class="font-bold text-sm text-gray-900 group-hover:text-[#0D5A47] transition">Buana Ticketing</h3>
                    <p class="text-xs text-gray-500 mt-1 line-clamp-2">Transportasi dinas, tiket kereta/pesawat, dan tiket seminar Yayasan.</p>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs font-bold text-[#0D5A47]">
                    <span>Buka Katalog</span>
                    <span>&rarr;</span>
                </div>
            </a>

            {{-- 5. Konveksi --}}
            <a href="{{ route('konveksi.index') }}" class="group bg-white p-5 rounded-2xl border border-gray-200 shadow-2xs hover:shadow-md hover:border-emerald-300 transition flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-pink-50 text-pink-700 flex items-center justify-center text-xl mb-3 group-hover:scale-105 transition">
                        👕
                    </div>
                    <h3 class="font-bold text-sm text-gray-900 group-hover:text-[#0D5A47] transition">Buana Konveksi</h3>
                    <p class="text-xs text-gray-500 mt-1 line-clamp-2">Jas almamater, PDH/PDL, kaos seragam & pelacakan progres jahit real-time.</p>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs font-bold text-[#0D5A47]">
                    <span>Buka Katalog</span>
                    <span>&rarr;</span>
                </div>
            </a>
        </div>
    </div>

    {{-- D. Alur Pemeriksaan & Tata Kelola Keuangan (Slide 10 & Slide 11 PDF) --}}
    <div class="bg-white rounded-3xl border border-gray-200 p-8 shadow-xs">
        <div class="max-w-2xl mb-6">
            <span class="text-xs font-bold text-[#0D5A47] uppercase tracking-wider">Standar Operasional Prosedur</span>
            <h2 class="text-xl font-bold text-gray-900">Alur Pemeriksaan Transaksi & Perlindungan Kas</h2>
            <p class="text-xs text-gray-500">Setiap pesanan dinas melewati 4 gerbang verifikasi untuk menjaga kualitas dan kepastian arus kas.</p>
        </div>

        <div class="grid grid-cols-4 gap-4 text-xs">
            <div class="p-4 rounded-2xl bg-slate-50 border border-gray-100">
                <span class="w-6 h-6 rounded-full bg-[#0D5A47] text-white flex items-center justify-center font-bold text-[11px] mb-2">1</span>
                <h4 class="font-bold text-gray-900">Permintaan</h4>
                <p class="text-gray-500 mt-1">Pemesan memilih layanan. Admin memeriksa kapasitas vendor, harga, dan ketersediaan stok.</p>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-gray-100">
                <span class="w-6 h-6 rounded-full bg-[#0D5A47] text-white flex items-center justify-center font-bold text-[11px] mb-2">2</span>
                <h4 class="font-bold text-gray-900">Persetujuan</h4>
                <p class="text-gray-500 mt-1">Verifikasi penanggung jawab fakultas, surat pesanan (SPK), serta limit kredit tempo bulanan.</p>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-gray-100">
                <span class="w-6 h-6 rounded-full bg-[#0D5A47] text-white flex items-center justify-center font-bold text-[11px] mb-2">3</span>
                <h4 class="font-bold text-gray-900">Pelaksanaan</h4>
                <p class="text-gray-500 mt-1">PIC unit mengonfirmasi ke vendor, memantau pengantaran, dan mengunggah bukti serah terima digital.</p>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-gray-100">
                <span class="w-6 h-6 rounded-full bg-[#0D5A47] text-white flex items-center justify-center font-bold text-[11px] mb-2">4</span>
                <h4 class="font-bold text-gray-900">Penutupan & Audit</h4>
                <p class="text-gray-500 mt-1">Keuangan mencocokkan pembayaran tempo, biaya vendor, laba bersih, dan rekonsiliasi akhir.</p>
            </div>
        </div>
    </div>
</div>

<script>
// Switcher Tab Mobile
function switchMobileTab(type) {
    const container = document.getElementById('campus-toggle-mobile');
    const buttons = container.querySelectorAll('button');
    if (type === 'unwahas') {
        buttons[0].className = "flex-1 py-1.5 px-4 rounded-full text-xs font-semibold bg-[#0D5A47] text-white shadow-xs text-center transition";
        buttons[1].className = "flex-1 py-1.5 px-4 rounded-full text-xs font-medium text-gray-600 hover:text-gray-900 text-center transition";
    } else {
        buttons[1].className = "flex-1 py-1.5 px-4 rounded-full text-xs font-semibold bg-[#0D5A47] text-white shadow-xs text-center transition";
        buttons[0].className = "flex-1 py-1.5 px-4 rounded-full text-xs font-medium text-gray-600 hover:text-gray-900 text-center transition";
    }
}

// Interactive Desktop Stepper & Total Calculation
let deskQty = 40;
const deskPrice = 15000;

function formatRupiahDesk(val) {
    return 'Rp ' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function changeDeskQty(delta) {
    deskQty = Math.max(5, deskQty + delta);
    document.getElementById('deskQtyDisplay').innerText = deskQty;
    document.getElementById('deskInputQty').value = deskQty;
    
    const total = deskQty * deskPrice;
    document.getElementById('deskFormula').innerText = `${deskQty} porsi × ${formatRupiahDesk(deskPrice)}`;
    document.getElementById('deskTotal').innerText = formatRupiahDesk(total);
}
</script>
@endsection
