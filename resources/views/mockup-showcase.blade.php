<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mockup Pemesanan Kampus Buana Group - Slide 8 Presentation</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#144b3c] text-white min-h-screen flex flex-col justify-between p-6 sm:p-10 font-sans selection:bg-emerald-700 selection:text-white">

    {{-- Top Header Section --}}
    <div class="max-w-7xl mx-auto w-full mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-emerald-800/60 pb-4">
            <div>
                <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight text-white">
                    Mockup pemesanan kampus Buana Group
                </h1>
                <p class="text-emerald-200/80 text-sm sm:text-base mt-1">
                    Dari kebutuhan kampus menuju pasar yang lebih luas — Alur Pemesanan Cepat & Terverifikasi
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="px-4 py-2 bg-emerald-800/80 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl border border-emerald-600/40 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    Buka Mode Mobile Native
                </a>
            </div>
        </div>
    </div>

    {{-- The 3 Phones Presentation Grid (Side-by-side identical to Slide 8) --}}
    <div class="max-w-7xl mx-auto w-full grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8 my-auto items-start">
        
        {{-- PHONE 1: BERANDA --}}
        <div class="bg-white text-gray-900 rounded-[32px] shadow-2xl border-[6px] border-slate-900 overflow-hidden flex flex-col h-[650px] relative">
            {{-- Status Bar --}}
            <div class="w-full bg-white px-5 pt-2.5 pb-1 flex justify-between items-center text-[10px] font-semibold text-gray-800 select-none border-b border-gray-50">
                <span>10.24</span>
                <div class="flex items-center gap-1.5">
                    <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M12 4C7.31 4 3.07 5.9 0 8.98L12 21 24 8.98C20.93 5.9 16.69 4 12 4z"/></svg>
                    <span>92%</span>
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M17 5v14a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2z"/></svg>
                </div>
            </div>

            {{-- Phone 1 Body --}}
            <div class="flex-1 flex flex-col justify-between px-4 pt-2 pb-3 overflow-y-auto">
                <div>
                    {{-- Header --}}
                    <div class="flex justify-between items-center py-1">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-[#0D5A47] flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18m-9-9c0 4.97 4.03 9 9 9 4.97 0 9-4.03 9-9 0-4.97-4.03-9-9-9-4.97 0-9 4.03-9 9z" /></svg>
                            </div>
                            <div>
                                <div class="text-[14px] font-bold text-[#0D5A47] leading-none">Buana Group</div>
                                <div class="text-[9px] text-gray-500 leading-none">Indonesian Buana Group</div>
                            </div>
                        </div>
                        <div class="relative p-1 text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                            <span class="absolute top-1 right-1 w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                        </div>
                    </div>

                    {{-- Tabs --}}
                    <div class="mt-2.5 bg-gray-100 p-0.5 rounded-full flex gap-1 border border-gray-200">
                        <div class="flex-1 py-1 rounded-full text-[10px] font-bold bg-[#0D5A47] text-white text-center shadow-xs">UNWAHAS</div>
                        <div class="flex-1 py-1 rounded-full text-[10px] font-medium text-gray-600 text-center">Umum</div>
                    </div>

                    {{-- Search --}}
                    <div class="relative mt-2.5">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </span>
                        <input type="text" placeholder="Cari layanan" readonly class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-8 pr-3 py-1.5 text-[10px] text-gray-800 placeholder-gray-400">
                    </div>

                    {{-- 5 Icons Row --}}
                    <div class="mt-4 grid grid-cols-5 gap-1 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-[#0D5A47]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 3h6m-5 0v3a2 2 0 01-1 1.732V19a2 2 0 002 2h2a2 2 0 002-2V7.732A2 2 0 0114 6V3m-4 8h4" /></svg>
                            </div>
                            <span class="mt-1 text-[9px] font-medium text-gray-700">Air Mineral</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-[#0D5A47]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v2m-8 10a8 8 0 0116 0H4zm-1 3h18" /></svg>
                            </div>
                            <span class="mt-1 text-[9px] font-medium text-gray-700">Katering</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-[#0D5A47]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 17l6-6 4 4 8-8M3 17h18M18 7a2 2 0 110-4 2 2 0 010 4z" /></svg>
                            </div>
                            <span class="mt-1 text-[9px] font-medium text-gray-700">Wisata</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-[#0D5A47]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 010 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 010-4V7a2 2 0 00-2-2H5z" /></svg>
                            </div>
                            <span class="mt-1 text-[9px] font-medium text-gray-700">Tiket</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-[#0D5A47]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6l4-2 4 3 4-3 4 2v4l-3 1v9H7v-9L4 10V6z" /></svg>
                            </div>
                            <span class="mt-1 text-[9px] font-medium text-gray-700">Konveksi</span>
                        </div>
                    </div>

                    {{-- Pesan Lagi --}}
                    <div class="mt-6">
                        <div class="text-[11px] font-bold text-gray-900 mb-2">Pesan lagi</div>
                        <a href="{{ route('order.campus.rincian') }}" class="flex items-center justify-between p-2.5 bg-white border border-gray-100 rounded-xl shadow-xs hover:border-emerald-300 transition">
                            <div class="flex items-center gap-2.5">
                                <img src="{{ asset('images/paket_rapat_fakultas.jpg') }}" alt="Paket" class="w-11 h-11 rounded-lg object-cover">
                                <div>
                                    <div class="text-[11px] font-bold text-gray-900 leading-tight">Paket Rapat Fakultas</div>
                                    <div class="text-[9px] text-gray-500 mt-0.5">Mulai Rp15.000 / orang</div>
                                </div>
                            </div>
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </div>
                </div>

                {{-- Bottom Nav --}}
                <div class="border-t border-gray-100 pt-2 flex justify-around text-[10px] text-gray-400">
                    <div class="flex flex-col items-center gap-0.5 text-[#0D5A47] font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span>Beranda</span>
                    </div>
                    <div class="flex flex-col items-center gap-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3"/></svg>
                        <span>Pesanan</span>
                    </div>
                    <div class="flex flex-col items-center gap-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span>Akun</span>
                    </div>
                </div>
            </div>
            {{-- Home Indicator --}}
            <div class="w-full bg-white flex justify-center py-1"><div class="w-24 h-1 bg-gray-300 rounded-full"></div></div>
        </div>

        {{-- PHONE 2: RINCIAN PESANAN --}}
        <div class="bg-white text-gray-900 rounded-[32px] shadow-2xl border-[6px] border-slate-900 overflow-hidden flex flex-col h-[650px] relative">
            {{-- Status Bar --}}
            <div class="w-full bg-white px-5 pt-2.5 pb-1 flex justify-between items-center text-[10px] font-semibold text-gray-800 select-none border-b border-gray-50">
                <span>10.24</span>
                <div class="flex items-center gap-1.5">
                    <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M12 4C7.31 4 3.07 5.9 0 8.98L12 21 24 8.98C20.93 5.9 16.69 4 12 4z"/></svg>
                    <span>92%</span>
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M17 5v14a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2z"/></svg>
                </div>
            </div>

            {{-- Phone 2 Body --}}
            <div class="flex-1 flex flex-col justify-between px-4 pt-2 pb-3 overflow-y-auto">
                <div>
                    {{-- Header --}}
                    <div class="flex items-center gap-2 py-1.5 border-b border-gray-100">
                        <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        <span class="text-xs font-bold text-gray-900">Rincian Pesanan</span>
                    </div>

                    {{-- Card Item --}}
                    <div class="flex items-center gap-2.5 py-3 border-b border-gray-100">
                        <img src="{{ asset('images/paket_rapat_fakultas.jpg') }}" alt="Paket" class="w-10 h-10 rounded-lg object-cover">
                        <div>
                            <div class="text-[11px] font-bold text-gray-900">Paket Rapat Fakultas</div>
                            <div class="text-[9px] text-gray-500">Snack box + air mineral</div>
                        </div>
                    </div>

                    {{-- Form Fields --}}
                    <div class="mt-3 space-y-3 text-[10px]">
                        {{-- Jumlah --}}
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-gray-800">Jumlah</span>
                            <div class="flex items-center bg-gray-100 rounded-lg p-0.5 border border-gray-200">
                                <span class="w-5 h-5 bg-white text-gray-700 flex items-center justify-center font-bold rounded shadow-2xs">&minus;</span>
                                <span class="font-bold text-gray-900 px-3">40</span>
                                <span class="w-5 h-5 bg-white text-gray-700 flex items-center justify-center font-bold rounded shadow-2xs">&#43;</span>
                            </div>
                        </div>

                        {{-- Jadwal Pengiriman --}}
                        <div>
                            <span class="font-bold text-gray-800 block mb-1">Jadwal pengiriman</span>
                            <div class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded-xl px-2.5 py-1.5">
                                <div class="flex items-center gap-2 text-gray-800">
                                    <svg class="w-3.5 h-3.5 text-[#0D5A47]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span class="font-medium">16 Nov 2026 · 09.00 WIB</span>
                                </div>
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>

                        {{-- Lokasi --}}
                        <div>
                            <span class="font-bold text-gray-800 block mb-1">Lokasi</span>
                            <div class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded-xl px-2.5 py-1.5">
                                <div class="flex items-center gap-2 text-gray-800">
                                    <svg class="w-3.5 h-3.5 text-[#0D5A47]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                    <span class="font-medium">Fakultas Farmasi UNWAHAS</span>
                                </div>
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Price & Submit --}}
                <div class="border-t border-gray-100 pt-3">
                    <div class="space-y-1 mb-3 text-[10px]">
                        <div class="flex justify-between text-gray-600">
                            <span>40 × Rp15.000</span>
                            <span class="font-semibold text-gray-900">Rp600.000</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-gray-900">Total</span>
                            <span class="font-bold text-[14px] text-[#0D5A47]">Rp600.000</span>
                        </div>
                    </div>
                    <a href="{{ route('order.campus.detail') }}" class="w-full py-2.5 bg-[#0D5A47] text-white font-bold rounded-xl text-[11px] flex items-center justify-center shadow-xs">
                        Ajukan Pesanan
                    </a>
                </div>
            </div>
            {{-- Home Indicator --}}
            <div class="w-full bg-white flex justify-center py-1"><div class="w-24 h-1 bg-gray-300 rounded-full"></div></div>
        </div>

        {{-- PHONE 3: DETAIL PESANAN --}}
        <div class="bg-white text-gray-900 rounded-[32px] shadow-2xl border-[6px] border-slate-900 overflow-hidden flex flex-col h-[650px] relative">
            {{-- Status Bar --}}
            <div class="w-full bg-white px-5 pt-2.5 pb-1 flex justify-between items-center text-[10px] font-semibold text-gray-800 select-none border-b border-gray-50">
                <span>10.25</span>
                <div class="flex items-center gap-1.5">
                    <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M12 4C7.31 4 3.07 5.9 0 8.98L12 21 24 8.98C20.93 5.9 16.69 4 12 4z"/></svg>
                    <span>92%</span>
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M17 5v14a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2z"/></svg>
                </div>
            </div>

            {{-- Phone 3 Body --}}
            <div class="flex-1 flex flex-col justify-between px-4 pt-2 pb-3 overflow-y-auto">
                <div>
                    {{-- Header --}}
                    <div class="flex items-center gap-2 py-1.5 border-b border-gray-100">
                        <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        <span class="text-xs font-bold text-gray-900">Detail Pesanan</span>
                    </div>

                    {{-- ID & Badge --}}
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-[#0D5A47]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span class="text-[10px] font-bold text-gray-900">BG-261114-001</span>
                        </div>
                        <span class="bg-[#FEF3C7] text-[#92400E] px-2 py-0.5 rounded-full text-[9px] font-semibold border border-[#FDE68A]/60">
                            Menunggu persetujuan
                        </span>
                    </div>

                    {{-- Product Card --}}
                    <div class="mt-2.5 bg-white border border-gray-100 rounded-xl p-2.5 shadow-2xs">
                        <div class="flex items-center gap-2.5">
                            <img src="{{ asset('images/paket_rapat_fakultas.jpg') }}" alt="Paket" class="w-10 h-10 rounded-lg object-cover">
                            <div>
                                <div class="text-[10px] font-bold text-gray-900">Paket Rapat Fakultas</div>
                                <div class="text-[9px] text-gray-500">40 paket · Rp600.000</div>
                            </div>
                        </div>
                        <div class="border-t border-gray-100 my-2"></div>
                        <div class="flex items-center gap-1.5 text-[9px] text-gray-600">
                            <svg class="w-3 h-3 text-[#0D5A47]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>Jadwal pengiriman</span>
                        </div>
                        <div class="text-[9px] font-medium text-gray-800 ml-4.5">16 Nov 2026 · 09.00 WIB</div>
                    </div>

                    {{-- Workflow Stepper --}}
                    <div class="mt-4 px-1 text-[9px]">
                        {{-- Step 1 --}}
                        <div class="flex items-start gap-2">
                            <div class="w-4.5 h-4.5 rounded-full bg-[#0D5A47] text-white flex items-center justify-center shrink-0">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <div class="font-bold text-gray-900 leading-tight">Pesanan diajukan</div>
                                <div class="text-gray-500 text-[8px]">14 Nov 2026 · 10.24 WIB</div>
                            </div>
                        </div>

                        <div class="w-0.5 h-5 bg-[#0D5A47] ml-2 my-0.5"></div>

                        {{-- Step 2 --}}
                        <div class="flex items-start gap-2">
                            <div class="w-4.5 h-4.5 rounded-full border-2 border-[#0D5A47] bg-white flex items-center justify-center shrink-0">
                                <div class="w-1.5 h-1.5 rounded-full bg-[#0D5A47]"></div>
                            </div>
                            <div>
                                <div class="font-bold text-gray-900 leading-tight">Persetujuan fakultas</div>
                                <div class="text-gray-500 text-[8px]">Menunggu persetujuan dari fakultas</div>
                            </div>
                        </div>

                        <div class="w-0.5 h-5 bg-gray-200 ml-2 my-0.5"></div>

                        {{-- Step 3 --}}
                        <div class="flex items-start gap-2">
                            <div class="w-4.5 h-4.5 rounded-full border-2 border-gray-300 bg-white flex items-center justify-center shrink-0"></div>
                            <div>
                                <div class="font-semibold text-gray-400 leading-tight">Diproses Buana</div>
                                <div class="text-gray-400 text-[8px]">Diproses setelah disetujui</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Button --}}
                <div class="border-t border-gray-100 pt-3">
                    <a href="{{ route('order.campus.detail') }}" class="w-full py-2 bg-white border border-gray-300 text-gray-800 font-semibold rounded-xl text-[10px] flex items-center justify-center shadow-2xs">
                        Lihat Rincian
                    </a>
                </div>
            </div>
            {{-- Home Indicator --}}
            <div class="w-full bg-white flex justify-center py-1"><div class="w-24 h-1 bg-gray-300 rounded-full"></div></div>
        </div>
    </div>

    {{-- Bottom Caption Section Sesuai Slide 8 --}}
    <div class="max-w-7xl mx-auto w-full mt-6 pt-4 border-t border-emerald-800/60 flex flex-col sm:flex-row justify-between items-center text-xs text-emerald-200/70">
        <div>
            Contoh alur: pilih paket rapat, ajukan 40 paket senilai Rp600.000, lalu pantau persetujuan.
        </div>
        <div class="mt-2 sm:mt-0 font-medium">
            BUANA GROUP / Usulan pengembangan • 14 November 2026 &nbsp;&bull;&nbsp; 08
        </div>
    </div>

</body>
</html>
