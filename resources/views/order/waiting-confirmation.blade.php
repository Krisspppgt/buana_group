@extends('layouts.app')
@section('title', 'Menunggu Konfirmasi Admin - #' . $order->code)

@section('content')
@php
    $isWaiting = $order->status === \App\Enums\OrderStatus::WaitingConfirmation;
    $isReadyToPay = $order->status === \App\Enums\OrderStatus::Pending;
    $isCancelled = $order->status === \App\Enums\OrderStatus::Cancelled;
    $isInProgressOrDone = in_array($order->status, [
        \App\Enums\OrderStatus::Paid,
        \App\Enums\OrderStatus::Processing,
        \App\Enums\OrderStatus::Shipped,
        \App\Enums\OrderStatus::Done,
    ], true);

    $unitDetailRoute = match ($order->unit) {
        \App\Enums\Unit::Air => route('water.show', $order->code),
        \App\Enums\Unit::Food => route('food.show', $order->code),
        \App\Enums\Unit::Konveksi => route('konveksi.tracking', $order->code),
        default => route('payment.checkout', $order->code),
    };

    $badgeClass = match ($order->status) {
        \App\Enums\OrderStatus::WaitingConfirmation => 'bg-amber-100 text-amber-800 border-amber-200',
        \App\Enums\OrderStatus::Pending => 'bg-emerald-100 text-emerald-800 border-emerald-200',
        \App\Enums\OrderStatus::Cancelled => 'bg-red-100 text-red-800 border-red-200',
        default => 'bg-blue-100 text-blue-800 border-blue-200',
    };
@endphp

<div class="max-w-5xl mx-auto space-y-6">
    {{-- Header Status Card --}}
    <div class="bg-white border border-gray-200 rounded-3xl p-5 sm:p-7 shadow-xs">
        <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="w-9 h-9 rounded-xl border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-50 transition" title="Kembali ke Beranda">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h1 class="text-lg sm:text-xl font-extrabold text-gray-900">Pesanan #{{ $order->code }}</h1>
                        <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 text-[11px] font-bold">
                            {{ $order->unit->label() }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Diajukan pada {{ $order->created_at?->format('d M Y · H:i') }} WIB
                    </p>
                </div>
            </div>

            <span class="px-3 py-1.5 rounded-full border text-xs font-bold {{ $badgeClass }}">
                {{ $order->status->label() }}
            </span>
        </div>

        @if (session('success'))
            <div class="mt-4 p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif

        @if (session('info'))
            <div class="mt-4 p-3.5 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs font-semibold">
                {{ session('info') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-4 p-3.5 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-xs font-semibold space-y-1">
                @foreach ($errors->all() as $error)
                    <div>• {{ $error }}</div>
                @endforeach
            </div>
        @endif

        {{-- Status Callout --}}
        @if ($isWaiting)
            <div class="mt-4 p-4 rounded-2xl bg-amber-50 border border-amber-200 flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="text-xs text-amber-900 space-y-1">
                    <div class="font-extrabold text-sm">Menunggu Pemeriksaan &amp; Konfirmasi Admin</div>
                    <p class="leading-relaxed">
                        Karena pesanan ini termasuk kategori <b>borongan / skala besar</b>, tim admin Buana Group sedang memeriksa ketersediaan stok, kapasitas vendor, dan biaya pengiriman final. Tombol pembayaran akan aktif otomatis setelah pesanan disetujui (estimasi maks. 1x24 jam).
                    </p>
                </div>
            </div>
        @elseif ($isReadyToPay)
            <div class="mt-4 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="text-xs text-emerald-900 space-y-1">
                    <div class="font-extrabold text-sm">Pesanan Dikonfirmasi Admin — Siap Dibayar</div>
                    <p>
                        Kapasitas &amp; harga final telah dikonfirmasi{{ $order->confirmed_at ? ' pada '.$order->confirmed_at->format('d M Y, H:i').' WIB' : '' }}. Silakan tinjau rincian biaya di bawah lalu lanjutkan ke pembayaran.
                    </p>
                    @if ($order->admin_note)
                        <p class="font-semibold text-emerald-800">Catatan Admin: "{{ $order->admin_note }}"</p>
                    @endif
                </div>
                <a href="{{ route('payment.checkout', $order->code) }}" class="px-5 py-3 rounded-xl bg-[#0D5A47] hover:bg-[#094234] text-white font-bold text-xs shadow-sm transition text-center shrink-0">
                    Lanjut ke Pembayaran
                </a>
            </div>
        @elseif ($isCancelled)
            <div class="mt-4 p-4 rounded-2xl bg-red-50 border border-red-200 text-xs text-red-900 space-y-1">
                <div class="font-extrabold text-sm">Pesanan Dibatalkan</div>
                <p>{{ $order->admin_note ?: 'Pesanan ini telah dibatalkan sebelum tahap pembayaran.' }}</p>
            </div>
        @else
            <div class="mt-4 p-4 rounded-2xl bg-blue-50 border border-blue-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="text-xs text-blue-900">
                    <div class="font-extrabold text-sm">Pesanan Sudah Dikonfirmasi &amp; Diproses</div>
                    <p class="mt-0.5">Status saat ini: <b>{{ $order->status->label() }}</b>.</p>
                </div>
                <a href="{{ $unitDetailRoute }}" class="px-4 py-2.5 rounded-xl bg-blue-700 hover:bg-blue-800 text-white font-bold text-xs transition text-center shrink-0">
                    Lihat Detail &amp; Pelacakan
                </a>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        {{-- Kolom Kiri: Timeline Stepper (Sesuai Mockup PDF Slide 8 & 10) --}}
        <div class="lg:col-span-5 bg-white border border-gray-200 rounded-3xl p-5 sm:p-6 shadow-xs space-y-5">
            <h2 class="text-sm font-extrabold text-gray-900 pb-3 border-b border-gray-100">Alur Persetujuan &amp; Pembayaran</h2>

            <div class="space-y-0">
                {{-- Step 1: Pesanan Diajukan --}}
                <div class="flex items-start gap-3">
                    <div class="w-7 h-7 rounded-full bg-[#0D5A47] text-white flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div class="pt-0.5">
                        <div class="text-xs font-bold text-gray-900">1. Pesanan Diajukan</div>
                        <div class="text-[11px] text-gray-500 mt-0.5">{{ $order->created_at?->format('d M Y · H:i') }} WIB</div>
                    </div>
                </div>

                <div class="w-0.5 h-7 {{ $isWaiting || $isReadyToPay || $isInProgressOrDone ? 'bg-[#0D5A47]' : 'bg-gray-200' }} ml-3.5 my-1"></div>

                {{-- Step 2: Konfirmasi Admin --}}
                <div class="flex items-start gap-3">
                    @if ($isReadyToPay || $isInProgressOrDone)
                        <div class="w-7 h-7 rounded-full bg-[#0D5A47] text-white flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    @elseif ($isWaiting)
                        <div class="w-7 h-7 rounded-full border-2 border-amber-500 bg-amber-50 flex items-center justify-center shrink-0">
                            <div class="w-2.5 h-2.5 rounded-full bg-amber-500"></div>
                        </div>
                    @else
                        <div class="w-7 h-7 rounded-full border-2 border-red-300 bg-red-50 flex items-center justify-center shrink-0 text-red-600 text-xs font-bold">✕</div>
                    @endif
                    <div class="pt-0.5">
                        <div class="text-xs font-bold {{ $isWaiting ? 'text-amber-800' : 'text-gray-900' }}">
                            2. Konfirmasi Admin (Cek Vendor &amp; Harga)
                        </div>
                        <div class="text-[11px] text-gray-500 mt-0.5">
                            @if ($isWaiting)
                                Menunggu pemeriksaan kapasitas vendor &amp; harga final oleh admin
                            @elseif ($isCancelled)
                                Pesanan dibatalkan sebelum disetujui
                            @else
                                Disetujui{{ $order->confirmed_at ? ' pada '.$order->confirmed_at->format('d M Y · H:i').' WIB' : '' }}
                            @endif
                        </div>
                    </div>
                </div>

                <div class="w-0.5 h-7 {{ $isReadyToPay || $isInProgressOrDone ? 'bg-[#0D5A47]' : 'bg-gray-200' }} ml-3.5 my-1"></div>

                {{-- Step 3: Pembayaran --}}
                <div class="flex items-start gap-3">
                    @if ($isInProgressOrDone)
                        <div class="w-7 h-7 rounded-full bg-[#0D5A47] text-white flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    @elseif ($isReadyToPay)
                        <div class="w-7 h-7 rounded-full border-2 border-[#0D5A47] bg-emerald-50 flex items-center justify-center shrink-0">
                            <div class="w-2.5 h-2.5 rounded-full bg-[#0D5A47]"></div>
                        </div>
                    @else
                        <div class="w-7 h-7 rounded-full border-2 border-gray-300 bg-white shrink-0"></div>
                    @endif
                    <div class="pt-0.5">
                        <div class="text-xs font-bold {{ $isReadyToPay || $isInProgressOrDone ? 'text-gray-900' : 'text-gray-400' }}">
                            3. Persetujuan &amp; Pembayaran
                        </div>
                        <div class="text-[11px] {{ $isReadyToPay ? 'text-emerald-700 font-semibold' : 'text-gray-400' }} mt-0.5">
                            @if ($isReadyToPay)
                                Terbuka — silakan pilih QRIS, VA, E-Wallet, atau Tempo Disetujui
                            @elseif ($isInProgressOrDone)
                                Pembayaran / Invoice Tempo telah tercatat
                            @else
                                Dibuka setelah admin mengonfirmasi pesanan
                            @endif
                        </div>
                    </div>
                </div>

                <div class="w-0.5 h-7 {{ $isInProgressOrDone ? 'bg-[#0D5A47]' : 'bg-gray-200' }} ml-3.5 my-1"></div>

                {{-- Step 4: Diproses Buana --}}
                <div class="flex items-start gap-3">
                    @if ($isInProgressOrDone)
                        <div class="w-7 h-7 rounded-full bg-[#0D5A47] text-white flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    @else
                        <div class="w-7 h-7 rounded-full border-2 border-gray-300 bg-white shrink-0"></div>
                    @endif
                    <div class="pt-0.5">
                        <div class="text-xs font-bold {{ $isInProgressOrDone ? 'text-gray-900' : 'text-gray-400' }}">
                            4. Diproses &amp; Dikirim Buana
                        </div>
                        <div class="text-[11px] text-gray-400 mt-0.5">
                            {{ $isInProgressOrDone ? $order->status->label() : 'Diproses setelah pembayaran diselesaikan' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="pt-4 border-t border-gray-100 space-y-2.5">
                @if ($isWaiting)
                    <button type="button" disabled class="w-full py-3 px-4 rounded-xl bg-gray-100 text-gray-400 font-bold text-xs cursor-not-allowed">
                        Pembayaran Terkunci (Menunggu Konfirmasi Admin)
                    </button>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <a href="https://wa.me/6281234567890?text={{ urlencode("Halo Admin Buana Group, mohon konfirmasi pesanan borongan #{$order->code}.") }}" target="_blank" rel="noopener noreferrer" class="py-2.5 px-3 rounded-xl border border-emerald-300 text-emerald-800 hover:bg-emerald-50 font-bold text-xs text-center transition">
                            Hubungi Admin (WA)
                        </a>

                        <form method="POST" action="{{ route('order.cancel-waiting', $order->code) }}" onsubmit="return confirm('Batalkan pengajuan pesanan #{{ $order->code }}?');">
                            @csrf
                            <button type="submit" class="w-full py-2.5 px-3 rounded-xl border border-red-200 text-red-700 hover:bg-red-50 font-bold text-xs transition cursor-pointer">
                                Batalkan Pesanan
                            </button>
                        </form>
                    </div>
                @elseif ($isReadyToPay)
                    <a href="{{ route('payment.checkout', $order->code) }}" class="block w-full py-3 px-4 rounded-xl bg-[#0D5A47] hover:bg-[#094234] text-white font-bold text-xs text-center shadow-sm transition">
                        Lanjut ke Pembayaran
                    </a>
                @elseif ($isInProgressOrDone)
                    <a href="{{ $unitDetailRoute }}" class="block w-full py-3 px-4 rounded-xl bg-[#0D5A47] hover:bg-[#094234] text-white font-bold text-xs text-center shadow-sm transition">
                        Pantau Progres Pesanan
                    </a>
                @endif
            </div>
        </div>

        {{-- Kolom Kanan: Rincian Pesanan & Pengiriman --}}
        <div class="lg:col-span-7 bg-white border border-gray-200 rounded-3xl p-5 sm:p-6 shadow-xs space-y-5">
            <h2 class="text-sm font-extrabold text-gray-900 pb-3 border-b border-gray-100">Rincian Pengajuan Pesanan</h2>

            {{-- Daftar Item --}}
            <div class="space-y-3">
                @forelse ($order->items as $item)
                    <div class="flex items-center justify-between gap-3 p-3 rounded-2xl bg-slate-50 border border-slate-100">
                        <div>
                            <div class="text-xs font-bold text-gray-900">{{ $item->product?->name ?? 'Produk Buana' }}</div>
                            <div class="text-[11px] text-gray-500 mt-0.5">
                                {{ number_format($item->qty, 0, ',', '.') }} {{ $item->product?->uom ?? 'unit' }} &times; Rp {{ number_format($item->price, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="text-xs font-extrabold text-gray-900 shrink-0">
                            Rp {{ number_format($item->qty * $item->price, 0, ',', '.') }}
                        </div>
                    </div>
                @empty
                    <div class="p-3 rounded-2xl bg-slate-50 text-xs text-gray-500">
                        {{ $order->note ?: 'Pesanan layanan Buana Group' }}
                    </div>
                @endforelse
            </div>

            {{-- Info Pengiriman --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-100 text-xs">
                <div>
                    <span class="text-[11px] text-gray-400 font-bold uppercase block">Penerima / PIC</span>
                    <span class="font-bold text-gray-900">{{ $order->ship_name }}</span>
                    <span class="block text-gray-600 mt-0.5">{{ $order->ship_phone }}</span>
                </div>
                <div>
                    <span class="text-[11px] text-gray-400 font-bold uppercase block">Jadwal Pengiriman</span>
                    <span class="font-bold text-gray-900">{{ $order->send_at?->format('d M Y · H:i') ?? 'Segera dijadwalkan' }} WIB</span>
                </div>
                <div class="sm:col-span-2">
                    <span class="text-[11px] text-gray-400 font-bold uppercase block">Lokasi Pengiriman</span>
                    <span class="text-gray-800">{{ $order->ship_addr }}</span>
                </div>
                @if ($order->note)
                    <div class="sm:col-span-2">
                        <span class="text-[11px] text-gray-400 font-bold uppercase block">Catatan Pesanan</span>
                        <span class="text-gray-800">{{ $order->note }}</span>
                    </div>
                @endif
            </div>

            {{-- Ringkasan Biaya --}}
            <div class="border-t border-gray-100 pt-4 space-y-2 text-xs">
                <div class="flex justify-between text-gray-600">
                    <span>Subtotal Pesanan</span>
                    <span class="font-semibold text-gray-900">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Biaya Pengiriman</span>
                    <span class="font-semibold text-gray-900">
                        {{ $order->ship_fee > 0 ? 'Rp '.number_format($order->ship_fee, 0, ',', '.') : 'Gratis / Sesuai Konfirmasi' }}
                    </span>
                </div>
                <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                    <span class="text-sm font-extrabold text-gray-900">
                        {{ $isWaiting ? 'Estimasi Total Tagihan' : 'Total Tagihan Final' }}
                    </span>
                    <span class="text-lg font-black text-[#0D5A47]">
                        Rp {{ number_format($order->total, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
