@extends('layouts.app')
@section('title', 'Detail Pesanan - ' . ($order['code'] ?? 'BG-261114-001'))

@section('content')

{{-- ========================================================================= --}}
{{-- 1. TAMPILAN RESPONSIVE MOBILE (Persis 100% Mockup Slide 8 Screen 3)       --}}
{{-- ========================================================================= --}}
<div class="block md:hidden px-5 pt-2 pb-24 bg-white min-h-screen">
    {{-- Top App Bar: Back Arrow + Title --}}
    <div class="flex items-center gap-3 py-2 border-b border-gray-100 pb-3">
        <a href="{{ route('home') }}" class="p-1 -ml-1 text-gray-600 hover:text-gray-900 rounded-lg hover:bg-gray-100 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="text-[16px] font-bold text-gray-900">Detail Pesanan</h1>
    </div>

    {{-- Order ID & Status Header Row Sesuai Mockup --}}
    <div class="flex items-center justify-between py-3.5 border-b border-gray-100">
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-[#0D5A47]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span class="text-xs font-bold text-gray-900 tracking-tight">{{ $order['code'] ?? 'BG-261114-001' }}</span>
        </div>
        
        <span class="bg-[#FEF3C7] text-[#92400E] px-2.5 py-1 rounded-full text-[11px] font-semibold border border-[#FDE68A]/60">
            {{ $order['status_label'] ?? 'Menunggu persetujuan' }}
        </span>
    </div>

    {{-- Product Summary Card Sesuai Mockup --}}
    <div class="mt-3.5 bg-white border border-gray-100 rounded-2xl p-3.5 shadow-[0_2px_8px_-2px_rgba(0,0,0,0.05)]">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/paket_rapat_fakultas.jpg') }}" alt="Paket Rapat Fakultas" class="w-13 h-13 rounded-xl object-cover border border-gray-100">
            <div>
                <h2 class="text-xs font-bold text-gray-900">{{ $order['package_name'] ?? 'Paket Rapat Fakultas' }}</h2>
                <p class="text-[11px] text-gray-500 mt-0.5">
                    {{ $order['qty'] ?? 40 }} paket &middot; Rp {{ number_format($order['total'] ?? 600000, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <div class="border-t border-gray-100 my-3"></div>

        <div class="flex items-center gap-2 text-[11px] text-gray-600">
            <svg class="w-4 h-4 text-[#0D5A47] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span>Jadwal pengiriman</span>
        </div>
        <div class="text-[11px] font-medium text-gray-800 ml-6 mt-0.5">
            {{ $order['schedule'] ?? '16 Nov 2026 · 09.00 WIB' }}
        </div>
    </div>

    {{-- Workflow Stepper Timeline Sesuai Mockup --}}
    <div class="mt-6 px-1">
        {{-- Step 1: Pesanan Diajukan (Selesai) --}}
        <div class="flex items-start gap-3">
            <div class="w-6 h-6 rounded-full bg-[#0D5A47] text-white flex items-center justify-center shrink-0">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div class="pt-0.5">
                <h3 class="text-xs font-bold text-gray-900 leading-tight">Pesanan diajukan</h3>
                <p class="text-[11px] text-gray-500 mt-0.5">{{ $order['created_at'] ?? '14 Nov 2026 · 10.24 WIB' }}</p>
            </div>
        </div>

        <div class="w-0.5 h-7 bg-[#0D5A47] ml-3 my-0.5"></div>

        {{-- Step 2: Persetujuan Fakultas (Sedang Berjalan) --}}
        <div class="flex items-start gap-3">
            <div class="w-6 h-6 rounded-full border-2 border-[#0D5A47] bg-white flex items-center justify-center shrink-0">
                <div class="w-2 h-2 rounded-full bg-[#0D5A47]"></div>
            </div>
            <div class="pt-0.5">
                <h3 class="text-xs font-bold text-gray-900 leading-tight">Persetujuan fakultas</h3>
                <p class="text-[11px] text-gray-500 mt-0.5">Menunggu persetujuan dari fakultas</p>
            </div>
        </div>

        <div class="w-0.5 h-7 bg-gray-200 ml-3 my-0.5"></div>

        {{-- Step 3: Diproses Buana (Akan Datang) --}}
        <div class="flex items-start gap-3">
            <div class="w-6 h-6 rounded-full border-2 border-gray-300 bg-white flex items-center justify-center shrink-0"></div>
            <div class="pt-0.5">
                <h3 class="text-xs font-semibold text-gray-400 leading-tight">Diproses Buana</h3>
                <p class="text-[11px] text-gray-400 mt-0.5">Diproses setelah disetujui</p>
            </div>
        </div>
    </div>

    {{-- Bottom Action Button: Lihat Rincian Sesuai Mockup --}}
    <div class="mt-8 pt-4">
        <button type="button" onclick="openDetailsModalMobile()" class="w-full py-3 bg-white border border-gray-300 text-gray-800 font-semibold rounded-2xl text-xs hover:bg-gray-50 transition shadow-2xs flex items-center justify-center">
            Lihat Rincian
        </button>
    </div>
</div>

{{-- ========================================================================= --}}
{{-- 2. TAMPILAN RESPONSIVE WEB DESKTOP (Adaptasi Dokumen PDF Slide 10 & 11)   --}}
{{-- ========================================================================= --}}
<div class="hidden md:block max-w-7xl mx-auto px-6 py-8">
    
    {{-- Top Header & Status Bar --}}
    <div class="flex justify-between items-center pb-6 border-b border-gray-200 mb-8">
        <div>
            <nav class="flex items-center gap-2 text-xs text-gray-500 mb-2">
                <a href="{{ route('home') }}" class="hover:text-[#0D5A47]">Beranda</a>
                <span>&rsaquo;</span>
                <span class="text-gray-900 font-semibold">Pelacakan Pesanan Kampus</span>
            </nav>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Pesanan {{ $order['code'] ?? 'BG-261114-001' }}</h1>
                <span class="px-3 py-1 bg-[#FEF3C7] text-[#92400E] font-bold text-xs rounded-full border border-[#FDE68A]">
                    {{ $order['status_label'] ?? 'Menunggu persetujuan' }}
                </span>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('home') }}" class="px-4 py-2 border border-gray-300 rounded-xl text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
                Kembali ke Beranda
            </a>
            <a href="{{ route('order.campus.rincian') }}" class="px-4 py-2 bg-[#0D5A47] text-white rounded-xl text-xs font-semibold hover:bg-[#084032] transition">
                + Buat Pesanan Baru
            </a>
        </div>
    </div>

    {{-- Horizontal Workflow Lifecycle Stepper (Slide 10 PDF) --}}
    <div class="bg-white rounded-3xl border border-gray-200 p-6 shadow-xs mb-8">
        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-6">Alur Status Transaksi Institusi (SOP Buana)</h3>
        
        <div class="grid grid-cols-4 gap-4 relative">
            {{-- Connecting Line across steps --}}
            <div class="absolute top-4 left-10 right-10 h-0.5 bg-gray-200 -z-0"></div>

            {{-- Step 1: Permintaan (Selesai) --}}
            <div class="relative z-10 flex flex-col items-center text-center">
                <div class="w-8 h-8 rounded-full bg-[#0D5A47] text-white flex items-center justify-center font-bold text-xs shadow-xs mb-2">
                    ✓
                </div>
                <span class="font-bold text-xs text-gray-900">1. Permintaan Diajukan</span>
                <span class="text-[11px] text-gray-500 mt-0.5">{{ $order['created_at'] ?? '14 Nov 2026 · 10.24 WIB' }}</span>
            </div>

            {{-- Step 2: Persetujuan (Sedang Berjalan) --}}
            <div class="relative z-10 flex flex-col items-center text-center">
                <div class="w-8 h-8 rounded-full border-2 border-[#0D5A47] bg-white text-[#0D5A47] flex items-center justify-center font-bold text-xs shadow-xs mb-2">
                    <div class="w-2.5 h-2.5 rounded-full bg-[#0D5A47]"></div>
                </div>
                <span class="font-bold text-xs text-gray-900">2. Persetujuan Fakultas</span>
                <span class="text-[11px] text-emerald-700 font-medium mt-0.5">Menunggu approval dekanat</span>
            </div>

            {{-- Step 3: Pelaksanaan (Menunggu) --}}
            <div class="relative z-10 flex flex-col items-center text-center">
                <div class="w-8 h-8 rounded-full border-2 border-gray-300 bg-white text-gray-400 flex items-center justify-center font-bold text-xs mb-2">
                    3
                </div>
                <span class="font-semibold text-xs text-gray-400">3. Pelaksanaan Buana</span>
                <span class="text-[11px] text-gray-400 mt-0.5">Produksi & serah terima</span>
            </div>

            {{-- Step 4: Penutupan & Rekonsiliasi (Menunggu) --}}
            <div class="relative z-10 flex flex-col items-center text-center">
                <div class="w-8 h-8 rounded-full border-2 border-gray-300 bg-white text-gray-400 flex items-center justify-center font-bold text-xs mb-2">
                    4
                </div>
                <span class="font-semibold text-xs text-gray-400">4. Penutupan Invoice</span>
                <span class="text-[11px] text-gray-400 mt-0.5">Pembayaran tempo 30 hari</span>
            </div>
        </div>
    </div>

    {{-- Main 2-Column Desktop Grid --}}
    <div class="grid grid-cols-12 gap-8 items-start">
        {{-- Left Column: Order Content & Tracking Details (7 Cols) --}}
        <div class="col-span-7 space-y-6">
            <div class="bg-white rounded-3xl border border-gray-200 p-6 shadow-xs">
                <h3 class="text-sm font-bold text-gray-900 pb-3 border-b border-gray-100 uppercase tracking-wider text-xs">
                    Rincian Barang & Jadwal Logistik
                </h3>

                <div class="mt-4 flex items-center gap-4 pb-4 border-b border-gray-100">
                    <img src="{{ asset('images/paket_rapat_fakultas.jpg') }}" alt="Paket" class="w-16 h-16 rounded-xl object-cover border border-gray-100">
                    <div class="flex-1">
                        <h4 class="font-bold text-sm text-gray-900">{{ $order['package_name'] ?? 'Paket Rapat Fakultas' }}</h4>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $order['package_subtitle'] ?? 'Snack box + air mineral' }}</p>
                        <div class="mt-2 flex items-center gap-4 text-xs">
                            <span class="font-semibold text-gray-700">Jumlah: {{ $order['qty'] ?? 40 }} Paket</span>
                            <span class="text-gray-400">&bull;</span>
                            <span class="font-semibold text-gray-700">Harga: Rp 15.000 / paket</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-base font-bold text-[#0D5A47]">Rp {{ number_format($order['total'] ?? 600000, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Delivery Destination & Schedule --}}
                <div class="mt-5 grid grid-cols-2 gap-4 text-xs">
                    <div class="p-4 rounded-2xl bg-slate-50 border border-gray-100">
                        <span class="text-gray-500 block mb-1">Jadwal Pengantaran:</span>
                        <span class="font-bold text-gray-900 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-[#0D5A47]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ $order['schedule'] ?? '16 Nov 2026 · 09.00 WIB' }}
                        </span>
                    </div>
                    <div class="p-4 rounded-2xl bg-slate-50 border border-gray-100">
                        <span class="text-gray-500 block mb-1">Lokasi Kampus:</span>
                        <span class="font-bold text-gray-900 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-[#0D5A47]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            {{ $order['location'] ?? 'Fakultas Farmasi UNWAHAS' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Institutional Billing Breakdown (5 Cols) --}}
        <div class="col-span-5 space-y-4">
            <div class="bg-white rounded-3xl border border-gray-200 p-6 shadow-xs">
                <h3 class="text-sm font-bold text-gray-900 pb-3 border-b border-gray-100 uppercase tracking-wider text-xs">
                    Informasi Penagihan & Tempo
                </h3>

                <div class="mt-4 space-y-3 text-xs">
                    <div class="flex justify-between text-gray-600">
                        <span>Nomor Dokumen:</span>
                        <span class="font-bold text-gray-900">{{ $order['code'] ?? 'BG-261114-001' }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Pihak Pemesan:</span>
                        <span class="font-semibold text-gray-900">{{ auth()->user()->name ?? 'Civitas UNWAHAS' }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Skema Bayar:</span>
                        <span class="font-semibold text-emerald-800">Tempo B2B (TOP 30 Hari)</span>
                    </div>
                    <div class="border-t border-gray-100 pt-3 flex justify-between items-center">
                        <span class="text-sm font-bold text-gray-900">Total Invoice:</span>
                        <span class="text-2xl font-extrabold text-[#0D5A47]">Rp {{ number_format($order['total'] ?? 600000, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-100 flex gap-3">
                    <button type="button" onclick="alert('Salinan invoice digital siap diunduh saat status disetujui dekanat.')" class="flex-1 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold rounded-xl text-xs transition">
                        🖨️ Cetak Surat Pesanan
                    </button>
                    <a href="https://wa.me/?text=Konfirmasi%20Pesanan%20{{ $order['code'] ?? 'BG-261114-001' }}" target="_blank" class="px-4 py-2.5 bg-emerald-50 text-[#0D5A47] hover:bg-emerald-100 font-semibold rounded-xl text-xs transition">
                        Hubungi PIC Buana
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Detail Modal Drawer Mobile --}}
<div id="detailsModalMobile" class="fixed inset-0 bg-black/40 z-50 hidden flex items-end sm:items-center justify-center p-0 sm:p-4">
    <div class="w-full sm:max-w-md bg-white rounded-t-3xl sm:rounded-2xl p-6 shadow-xl">
        <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-3">
            <h3 class="text-sm font-bold text-gray-900">Rincian Lengkap Pesanan</h3>
            <button type="button" onclick="closeDetailsModalMobile()" class="text-gray-400 hover:text-gray-600 p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        
        <div class="space-y-3 text-xs text-gray-600">
            <div class="flex justify-between"><span class="text-gray-500">Nomor Pesanan:</span><span class="font-bold text-gray-800">{{ $order['code'] ?? 'BG-261114-001' }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Pemesan:</span><span class="font-medium text-gray-800">{{ auth()->user()->name ?? 'Civitas UNWAHAS' }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Lokasi Antar:</span><span class="font-medium text-gray-800">{{ $order['location'] ?? 'Fakultas Farmasi UNWAHAS' }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Skema Pembayaran:</span><span class="font-semibold text-[#0D5A47]">Invoice / Tempo Institusi UNWAHAS</span></div>
            <div class="border-t border-gray-100 pt-2 flex justify-between font-bold text-gray-900">
                <span>Total Biaya:</span>
                <span class="text-[#0D5A47] text-sm">Rp {{ number_format($order['total'] ?? 600000, 0, ',', '.') }}</span>
            </div>
        </div>

        <button type="button" onclick="closeDetailsModalMobile()" class="w-full mt-5 py-2.5 bg-[#0D5A47] text-white font-bold text-xs rounded-xl">
            Tutup
        </button>
    </div>
</div>

<script>
function openDetailsModalMobile() {
    document.getElementById('detailsModalMobile').classList.remove('hidden');
}
function closeDetailsModalMobile() {
    document.getElementById('detailsModalMobile').classList.add('hidden');
}
</script>
@endsection
