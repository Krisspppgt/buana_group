@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Breadcrumb & Title -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-emerald-700">Home</a>
            <span>/</span>
            <a href="{{ route('tour.index') }}" class="hover:text-emerald-700">Tour & Travel</a>
            <span>/</span>
            <span class="text-gray-900 font-semibold">Riwayat Booking</span>
        </div>
        <a href="{{ route('tour.index') }}" class="inline-flex items-center text-xs font-semibold px-3 py-1.5 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">
            + Pesan Paket Baru
        </a>
    </div>

    <div>
        <h1 class="text-2xl font-black text-gray-900">Riwayat Pendaftaran Tour & Travel</h1>
        <p class="text-sm text-gray-500 mt-1">Daftar pendaftaran ziarah wali, study tour, dan paket tabungan Umrah Anda.</p>
    </div>

    @if($bookings->isEmpty())
        <div class="bg-white rounded-3xl p-12 text-center border border-gray-100 shadow-sm space-y-4">
            <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-3xl mx-auto">
                🕌
            </div>
            <h3 class="text-lg font-bold text-gray-900">Belum Ada Riwayat Perjalanan</h3>
            <p class="text-sm text-gray-500 max-w-sm mx-auto">
                Anda belum melakukan pemesanan paket wisata, ziarah, atau program cicilan Umrah.
            </p>
            <a href="{{ route('tour.index') }}" class="inline-block px-5 py-2.5 bg-emerald-600 text-white font-bold rounded-xl text-xs hover:bg-emerald-700 shadow-sm">
                Jelajahi Paket Wisata & Umrah
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($bookings as $booking)
                <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="space-y-2">
                        <div class="flex items-center space-x-2">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold font-mono bg-gray-100 text-gray-800">
                                #{{ $booking->code }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold
                                @if($booking->status === 2) bg-emerald-100 text-emerald-800
                                @elseif($booking->status === 1) bg-amber-100 text-amber-800
                                @else bg-blue-100 text-blue-800 @endif">
                                {{ $booking->status_label }}
                            </span>
                            <span class="text-xs text-gray-400">
                                {{ $booking->created_at->format('d M Y H:i') }}
                            </span>
                        </div>

                        <h3 class="text-base font-bold text-gray-900">
                            {{ $booking->tourPackage->title }}
                        </h3>

                        <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500">
                            <span>👥 {{ $booking->total_pax }} Jamaah/Peserta</span>
                            <span>•</span>
                            <span>🗓️ Berangkat: {{ $booking->tourPackage->departure_date->format('d M Y') }}</span>
                            <span>•</span>
                            <span class="capitalize">💳 Skema: {{ $booking->payment_scheme }}</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between md:flex-col md:items-end gap-2 pt-3 md:pt-0 border-t md:border-t-0 border-gray-100">
                        <div>
                            <span class="text-[10px] text-gray-400 block md:text-right">Total Biaya:</span>
                            <span class="text-base font-extrabold text-emerald-700">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                        </div>
                        <a href="{{ route('tour.bookings.show', $booking->code) }}" class="px-4 py-2 bg-emerald-50 text-emerald-700 font-bold text-xs rounded-xl hover:bg-emerald-100 transition-colors inline-flex items-center space-x-1">
                            <span>Detail Booking</span>
                            <span>➔</span>
                        </a>
                    </div>
                </div>
            @endforeach

            <!-- Pagination -->
            <div class="mt-4">
                {{ $bookings->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
