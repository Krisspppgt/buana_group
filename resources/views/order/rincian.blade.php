@extends('layouts.app')
@section('title', 'Rincian Pesanan - Paket Rapat Fakultas')

@section('content')

{{-- ========================================================================= --}}
{{-- 1. TAMPILAN RESPONSIVE MOBILE (Persis 100% Mockup Slide 8 Screen 2)       --}}
{{-- ========================================================================= --}}
<div class="block md:hidden px-5 pt-2 pb-24 bg-white min-h-screen">
    {{-- Top App Bar: Back Arrow + Title --}}
    <div class="flex items-center gap-3 py-2 border-b border-gray-100 pb-3">
        <a href="{{ route('home') }}" class="p-1 -ml-1 text-gray-600 hover:text-gray-900 rounded-lg hover:bg-gray-100 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="text-[16px] font-bold text-gray-900">Rincian Pesanan</h1>
    </div>

    {{-- Product Summary Header Card --}}
    <div class="flex items-center gap-3.5 py-4 border-b border-gray-100">
        <img src="{{ asset('images/paket_rapat_fakultas.jpg') }}" alt="Paket Rapat Fakultas" class="w-13 h-13 rounded-xl object-cover border border-gray-100">
        <div>
            <h2 class="text-sm font-bold text-gray-900">Paket Rapat Fakultas</h2>
            <p class="text-xs text-gray-500 mt-0.5">Snack box + air mineral</p>
        </div>
    </div>

    {{-- Form Fields --}}
    <form id="mobileOrderForm" method="POST" action="{{ route('order.campus.store') }}" class="mt-4 space-y-4">
        @csrf
        <input type="hidden" name="qty" id="mobileInputQty" value="40">
        <input type="hidden" name="schedule" id="mobileInputSchedule" value="16 Nov 2026 · 09.00 WIB">

        {{-- 1. Stepper: Jumlah --}}
        <div class="flex items-center justify-between py-1">
            <label class="text-xs font-bold text-gray-800">Jumlah</label>
            <div class="flex items-center bg-gray-100/80 rounded-xl p-1 border border-gray-200/60">
                <button type="button" onclick="changeMobileQty(-1)" class="w-7 h-7 rounded-lg bg-white shadow-2xs text-gray-700 flex items-center justify-center font-bold text-sm hover:bg-gray-50 active:scale-95 transition">
                    &minus;
                </button>
                <span id="mobileQtyDisplay" class="font-bold text-xs text-gray-900 px-4 min-w-[2.5rem] text-center">40</span>
                <button type="button" onclick="changeMobileQty(1)" class="w-7 h-7 rounded-lg bg-white shadow-2xs text-gray-700 flex items-center justify-center font-bold text-sm hover:bg-gray-50 active:scale-95 transition">
                    &#43;
                </button>
            </div>
        </div>

        {{-- 2. Jadwal Pengiriman --}}
        <div>
            <label class="text-xs font-bold text-gray-800 block mb-1.5">Jadwal pengiriman</label>
            <div class="flex items-center justify-between bg-gray-50/90 border border-gray-200/80 rounded-2xl px-3.5 py-2.5 text-xs text-gray-800 hover:border-gray-300 transition">
                <div class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-[#0D5A47]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="font-medium">16 Nov 2026 · 09.00 WIB</span>
                </div>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>

        {{-- 3. Lokasi --}}
        <div>
            <label class="text-xs font-bold text-gray-800 block mb-1.5">Lokasi</label>
            <div class="relative flex items-center bg-gray-50/90 border border-gray-200/80 rounded-2xl px-3.5 py-1 text-xs text-gray-800 hover:border-gray-300 transition">
                <svg class="w-4 h-4 text-[#0D5A47] mr-2 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <select name="location" class="w-full bg-transparent py-2 pr-4 text-xs font-medium text-gray-800 focus:outline-none appearance-none cursor-pointer">
                    <option value="Fakultas Farmasi UNWAHAS" selected>Fakultas Farmasi UNWAHAS</option>
                    <option value="Fakultas Kedokteran UNWAHAS">Fakultas Kedokteran UNWAHAS</option>
                    <option value="Fakultas Teknik UNWAHAS">Fakultas Teknik UNWAHAS</option>
                    <option value="Fakultas Ekonomi & Bisnis UNWAHAS">Fakultas Ekonomi & Bisnis UNWAHAS</option>
                    <option value="Fakultas Agama Islam UNWAHAS">Fakultas Agama Islam UNWAHAS</option>
                    <option value="Gedung Rektorat UNWAHAS">Gedung Rektorat UNWAHAS</option>
                </select>
                <svg class="w-4 h-4 text-gray-400 absolute right-3 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>
    </form>

    {{-- Bottom Calculation & Action Button --}}
    <div class="mt-8 pt-4 border-t border-gray-100">
        <div class="space-y-2 mb-4">
            <div class="flex justify-between items-center text-xs text-gray-600">
                <span id="mobileFormulaLabel">40 × Rp15.000</span>
                <span id="mobileSubtotalDisplay" class="font-semibold text-gray-900">Rp600.000</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-xs font-bold text-gray-900">Total</span>
                <span id="mobileTotalDisplay" class="text-[17px] font-bold text-[#0D5A47]">Rp600.000</span>
            </div>
        </div>

        <button type="submit" form="mobileOrderForm" class="w-full py-3.5 bg-[#0D5A47] hover:bg-[#084032] text-white font-bold rounded-2xl text-xs tracking-wide shadow-sm active:scale-[0.99] transition flex items-center justify-center">
            Ajukan Pesanan
        </button>
    </div>
</div>

{{-- ========================================================================= --}}
{{-- 2. TAMPILAN RESPONSIVE WEB DESKTOP (Adaptasi Dokumen PDF)                 --}}
{{-- ========================================================================= --}}
<div class="hidden md:block max-w-7xl mx-auto px-6 py-8">
    
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-xs text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-[#0D5A47]">Beranda</a>
        <span>&rsaquo;</span>
        <a href="{{ route('food.index') }}" class="hover:text-[#0D5A47]">Buana Food Services</a>
        <span>&rsaquo;</span>
        <span class="text-gray-900 font-semibold">Rincian Pemesanan Kampus</span>
    </nav>

    <div class="grid grid-cols-12 gap-8 items-start">
        {{-- Left Column: Order Form Configuration (7 Cols) --}}
        <div class="col-span-7 space-y-6">
            <div class="bg-white rounded-3xl border border-gray-200 p-6 shadow-xs">
                <div class="flex items-center gap-4 pb-6 border-b border-gray-100">
                    <img src="{{ asset('images/paket_rapat_fakultas.jpg') }}" alt="Paket Rapat Fakultas" class="w-20 h-20 rounded-2xl object-cover border border-gray-200 shadow-2xs">
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-[#0D5A47] text-[11px] font-bold mb-1">
                            🍱 Katering & Konsumsi Kampus
                        </div>
                        <h1 class="text-xl font-bold text-gray-900">Paket Rapat Fakultas</h1>
                        <p class="text-xs text-gray-500 mt-0.5">Kombinasi 3 macam kue snack box higienis + Air Mineral Unwahas Botol Segar.</p>
                    </div>
                </div>

                <form id="webOrderForm" method="POST" action="{{ route('order.campus.store') }}" class="mt-6 space-y-5">
                    @csrf
                    <input type="hidden" name="qty" id="webInputQty" value="40">
                    <input type="hidden" name="schedule" value="16 Nov 2026 · 09.00 WIB">

                    {{-- Stepper Jumlah --}}
                    <div>
                        <label class="text-xs font-bold text-gray-800 block mb-1.5">Jumlah Porsi Konsumsi</label>
                        <div class="flex items-center gap-3">
                            <div class="flex items-center bg-gray-50 border border-gray-200 rounded-xl p-1.5 max-w-[200px]">
                                <button type="button" onclick="changeWebQty(-5)" class="w-9 h-9 rounded-lg bg-white shadow-xs text-gray-700 font-bold hover:bg-gray-100 transition">&minus;</button>
                                <span id="webQtyDisplay" class="flex-1 text-center font-bold text-base text-gray-900">40</span>
                                <button type="button" onclick="changeWebQty(5)" class="w-9 h-9 rounded-lg bg-white shadow-xs text-gray-700 font-bold hover:bg-gray-100 transition">&#43;</button>
                            </div>
                            <span class="text-xs text-gray-500">Porsi disesuaikan dengan daftar hadir rapat/seminar.</span>
                        </div>
                    </div>

                    {{-- Jadwal Pengiriman --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-gray-800 block mb-1.5">Tanggal & Waktu Pengantaran</label>
                            <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs text-gray-800">
                                <svg class="w-4 h-4 text-[#0D5A47]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="font-medium">16 Nov 2026 · 09.00 WIB</span>
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-800 block mb-1.5">Gedung / Fakultas Tujuan</label>
                            <select name="location" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs font-medium text-gray-800 focus:outline-none focus:ring-1 focus:ring-[#0D5A47]">
                                <option value="Fakultas Farmasi UNWAHAS" selected>Fakultas Farmasi UNWAHAS</option>
                                <option value="Fakultas Kedokteran UNWAHAS">Fakultas Kedokteran UNWAHAS</option>
                                <option value="Fakultas Teknik UNWAHAS">Fakultas Teknik UNWAHAS</option>
                                <option value="Fakultas Ekonomi & Bisnis UNWAHAS">Fakultas Ekonomi & Bisnis UNWAHAS</option>
                                <option value="Fakultas Agama Islam UNWAHAS">Fakultas Agama Islam UNWAHAS</option>
                                <option value="Gedung Rektorat UNWAHAS">Gedung Rektorat UNWAHAS</option>
                            </select>
                        </div>
                    </div>

                    {{-- Ruangan / Detail Lokasi & PIC --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-gray-800 block mb-1.5">Ruang Rapat / Lantai</label>
                            <input type="text" name="room" value="Ruang Sidang Dekanat Lt. 2" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs text-gray-800">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-800 block mb-1.5">Penanggung Jawab / PIC Acara</label>
                            <input type="text" name="pic" value="{{ auth()->user()->name ?? 'Biro Dekanat' }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs text-gray-800">
                        </div>
                    </div>

                    {{-- Catatan Rundown & Diet --}}
                    <div>
                        <label class="text-xs font-bold text-gray-800 block mb-1.5">Catatan Rundown / Preferensi Khusus</label>
                        <textarea rows="2" placeholder="Contoh: Pengantaran 15 menit sebelum acara dimulai, 2 box tanpa gula untuk pimpinan." class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs text-gray-800"></textarea>
                    </div>
                </form>
            </div>
        </div>

        {{-- Right Column: Invoice & Billing Card (5 Cols Sticky) --}}
        <div class="col-span-5 sticky top-24 space-y-4">
            <div class="bg-white rounded-3xl border border-gray-200 p-6 shadow-sm">
                <h3 class="text-sm font-bold text-gray-900 pb-3 border-b border-gray-100 uppercase tracking-wider text-xs">
                    Ringkasan Tagihan Institusi
                </h3>

                <div class="mt-4 space-y-3 text-xs">
                    <div class="flex justify-between items-center text-gray-600">
                        <span id="webFormulaLabel">40 porsi &times; Rp 15.000</span>
                        <span id="webSubtotalDisplay" class="font-bold text-gray-800">Rp 600.000</span>
                    </div>
                    <div class="flex justify-between items-center text-gray-600">
                        <span>Ongkir Internal Kampus:</span>
                        <span class="text-emerald-700 font-bold">GRATIS</span>
                    </div>
                    <div class="border-t border-dashed border-gray-200 pt-3 flex justify-between items-center">
                        <span class="text-sm font-bold text-gray-900">Total Pembayaran:</span>
                        <span id="webTotalDisplay" class="text-2xl font-extrabold text-[#0D5A47]">Rp 600.000</span>
                    </div>
                </div>

                {{-- Ketentuan Tempo Sesuai Slide 11 PDF --}}
                <div class="mt-5 bg-emerald-50/70 border border-emerald-200 rounded-2xl p-4 text-[11px] text-emerald-950 space-y-1.5">
                    <div class="font-bold flex items-center gap-1.5 text-xs text-[#0D5A47]">
                        <span>🛡️</span> Fasilitas Tempo B2B Institusi UNWAHAS
                    </div>
                    <p class="leading-relaxed text-gray-600">
                        Pesanan ini akan diterbitkan dengan skema <b>Term of Payment (TOP) 30 hari</b> setelah verifikasi pejabat dekanat.
                    </p>
                </div>

                <div class="mt-5">
                    <button type="submit" form="webOrderForm" class="w-full py-3.5 bg-[#0D5A47] hover:bg-[#084032] text-white font-bold rounded-xl text-xs tracking-wide shadow-sm transition">
                        Ajukan Pesanan Resmi
                    </button>
                    <p class="text-[10px] text-gray-400 text-center mt-2">
                        Dengan mengajukan, pesanan akan masuk ke antrean persetujuan dekanat/fakultas.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Script Mobile
let mobileQty = 40;
const unitPrice = 15000;

function formatRupiah(num) {
    return 'Rp' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function changeMobileQty(delta) {
    mobileQty = Math.max(1, mobileQty + delta);
    document.getElementById('mobileQtyDisplay').innerText = mobileQty;
    document.getElementById('mobileInputQty').value = mobileQty;
    
    const total = mobileQty * unitPrice;
    document.getElementById('mobileFormulaLabel').innerText = `${mobileQty} × ${formatRupiah(unitPrice)}`;
    document.getElementById('mobileSubtotalDisplay').innerText = formatRupiah(total);
    document.getElementById('mobileTotalDisplay').innerText = formatRupiah(total);
}

// Script Desktop
let webQty = 40;
function changeWebQty(delta) {
    webQty = Math.max(5, webQty + delta);
    document.getElementById('webQtyDisplay').innerText = webQty;
    document.getElementById('webInputQty').value = webQty;

    const total = webQty * unitPrice;
    document.getElementById('webFormulaLabel').innerText = `${webQty} porsi × ${formatRupiah(unitPrice)}`;
    document.getElementById('webSubtotalDisplay').innerText = formatRupiah(total);
    document.getElementById('webTotalDisplay').innerText = formatRupiah(total);
}
</script>
@endsection
