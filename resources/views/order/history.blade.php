@extends('layouts.app')
@section('title', 'Riwayat Pesanan Terpadu - Buana Group')

@section('content')
@php
    $unitTabs = [
        'all' => 'Semua Unit',
        'air' => 'Air Mineral',
        'food' => 'Food Services',
        'konveksi' => 'Konveksi',
        'tour' => 'Tour & Travel',
        'ticketing' => 'Ticketing',
    ];

    $statusTabs = [
        'all' => 'Semua (' . $counts['all'] . ')',
        'waiting' => 'Menunggu Konfirmasi (' . $counts['waiting'] . ')',
        'unpaid' => 'Belum Bayar (' . $counts['unpaid'] . ')',
        'active' => 'Diproses (' . $counts['active'] . ')',
        'done' => 'Selesai / Batal (' . $counts['done'] . ')',
    ];
@endphp

<div class="max-w-5xl mx-auto space-y-5">
    {{-- Header Banner --}}
    <div class="bg-gradient-to-r from-[#094234] via-[#0D5A47] to-[#0a4d3c] rounded-3xl p-6 sm:p-8 text-white shadow-lg">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-white/15 text-emerald-100 text-[11px] font-bold">
                    Satu Pintu Pemesanan • 5 Unit Usaha
                </span>
                <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight mt-2">Riwayat Pesanan Terpadu</h1>
                <p class="text-xs sm:text-sm text-emerald-100 mt-1">
                    Pantau status konfirmasi admin, pembayaran, invoice, dan pengiriman seluruh unit usaha dalam satu tempat.
                </p>
            </div>
            <div class="flex flex-wrap gap-2 shrink-0">
                @if ($counts['waiting'] > 0)
                    <a href="{{ route('orders.history', ['unit' => $unitFilter, 'status' => 'waiting']) }}" class="px-3.5 py-2 rounded-xl bg-amber-400 text-amber-950 font-bold text-xs shadow-sm">
                        {{ $counts['waiting'] }} Menunggu Konfirmasi
                    </a>
                @endif
                @if ($counts['unpaid'] > 0)
                    <a href="{{ route('orders.history', ['unit' => $unitFilter, 'status' => 'unpaid']) }}" class="px-3.5 py-2 rounded-xl bg-white text-[#0D5A47] font-bold text-xs shadow-sm">
                        {{ $counts['unpaid'] }} Siap Dibayar
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Filter Unit & Status --}}
    <div class="bg-white border border-gray-200 rounded-2xl p-4 shadow-2xs space-y-3">
        <div class="flex flex-wrap gap-2">
            @foreach ($unitTabs as $key => $label)
                <a href="{{ route('orders.history', ['unit' => $key, 'status' => $statusFilter]) }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-bold transition {{ $unitFilter === $key ? 'bg-[#0D5A47] text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="flex flex-wrap gap-1.5 pt-2 border-t border-gray-100">
            @foreach ($statusTabs as $key => $label)
                <a href="{{ route('orders.history', ['unit' => $unitFilter, 'status' => $key]) }}"
                   class="px-3 py-1.5 rounded-lg text-[11px] font-semibold transition {{ $statusFilter === $key ? 'bg-emerald-100 text-[#0D5A47] border border-emerald-300' : 'bg-gray-50 text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Daftar Pesanan --}}
    @if ($items->isEmpty())
        <div class="bg-white border border-dashed border-gray-300 rounded-3xl p-10 text-center space-y-3">
            <div class="text-sm font-extrabold text-gray-900">Belum Ada Riwayat Pesanan</div>
            <p class="text-xs text-gray-500 max-w-md mx-auto">
                Belum ada transaksi yang sesuai dengan filter ini. Silakan pilih layanan di Beranda untuk mulai memesan.
            </p>
            <div>
                <a href="{{ route('home') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl bg-[#0D5A47] hover:bg-[#094234] text-white font-bold text-xs transition">
                    Jelajahi Layanan Buana Group
                </a>
            </div>
        </div>
    @else
        <div class="space-y-3">
            @foreach ($items as $item)
                @php
                    $badgeClasses = match ($item['badge_color']) {
                        'amber' => 'bg-amber-100 text-amber-800 border-amber-200',
                        'orange' => 'bg-orange-100 text-orange-800 border-orange-200',
                        'emerald' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                        'red' => 'bg-red-100 text-red-800 border-red-200',
                        default => 'bg-blue-100 text-blue-800 border-blue-200',
                    };
                @endphp
                <div class="bg-white border border-gray-200 rounded-2xl p-4 sm:p-5 shadow-2xs hover:border-emerald-300 transition flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="space-y-1.5 min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="font-mono text-xs font-extrabold text-gray-900">#{{ $item['code'] }}</span>
                            <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[10px] font-bold">
                                {{ $item['unit_label'] }}
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full border text-[11px] font-bold {{ $badgeClasses }}">
                                {{ $item['status_label'] }}
                            </span>
                        </div>
                        <div class="text-sm font-bold text-gray-900 truncate">{{ $item['title'] }}</div>
                        <div class="text-xs text-gray-500 truncate">
                            {{ $item['subtitle'] ?: '-' }} &bull; {{ $item['created_at']?->format('d M Y, H:i') }} WIB
                        </div>
                    </div>

                    <div class="flex sm:flex-col sm:items-end justify-between items-center gap-2.5 shrink-0">
                        <div class="text-base font-black text-[#0D5A47]">
                            Rp {{ number_format($item['amount'], 0, ',', '.') }}
                        </div>
                        <div class="flex items-center gap-2">
                            @if ($item['invoice_url'])
                                <a href="{{ $item['invoice_url'] }}" class="px-3 py-2 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50 text-xs font-bold transition">
                                    Invoice
                                </a>
                            @endif
                            <a href="{{ $item['primary_url'] }}" class="px-4 py-2 rounded-xl bg-[#0D5A47] hover:bg-[#094234] text-white text-xs font-bold shadow-xs transition">
                                {{ $item['primary_label'] }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
