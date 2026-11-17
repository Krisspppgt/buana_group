@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Breadcrumb & Nav -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-emerald-700">Home</a>
            <span>/</span>
            <a href="{{ route('ticketing.index') }}" class="hover:text-emerald-700">Buana Ticketing</a>
            <span>/</span>
            <span class="text-gray-900 font-semibold">Riwayat Tiket</span>
        </div>
        <a href="{{ route('ticketing.index') }}" class="inline-flex items-center text-xs font-semibold px-3 py-1.5 rounded-lg bg-blue-600 text-white hover:bg-blue-700 shadow-sm">
            + Pesan Tiket Baru
        </a>
    </div>

    <div>
        <h1 class="text-2xl font-black text-gray-900">Tiket & E-Boarding Pass Saya</h1>
        <p class="text-sm text-gray-500 mt-1">Daftar tiket transportasi (KAI, pesawat, bus, kapal) dan tiket kegiatan kampus Yayasan Wahid Hasyim.</p>
    </div>

    @if($tickets->isEmpty())
        <div class="bg-white rounded-3xl p-12 text-center border border-gray-100 shadow-sm space-y-4">
            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-3xl mx-auto">
                🎫
            </div>
            <h3 class="text-lg font-bold text-gray-900">Belum Ada Tiket yang Dipesan</h3>
            <p class="text-sm text-gray-500 max-w-sm mx-auto">
                Temukan tiket perjalanan kereta api, pesawat, atau event kampus dengan penawaran terbaik civitas.
            </p>
            <a href="{{ route('ticketing.index') }}" class="inline-block px-5 py-2.5 bg-blue-600 text-white font-bold rounded-xl text-xs hover:bg-blue-700 shadow-sm">
                Cari Tiket Sekarang
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($tickets as $t)
                <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="space-y-2">
                        <div class="flex items-center space-x-2">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold font-mono bg-gray-100 text-gray-800">
                                #{{ $t->code }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold
                                @if($t->status === 1) bg-emerald-100 text-emerald-800
                                @elseif($t->status === 0) bg-blue-100 text-blue-800
                                @else bg-rose-100 text-rose-800 @endif">
                                {{ $t->status_label }}
                            </span>
                            <span class="text-xs text-gray-400">
                                {{ $t->created_at->format('d M Y H:i') }}
                            </span>
                        </div>

                        <div class="flex items-center space-x-2">
                            <span class="text-xl">
                                @if($t->category === 'transport') 🚆 @else 🎟️ @endif
                            </span>
                            <h3 class="text-base font-bold text-gray-900">
                                {{ $t->title }}
                            </h3>
                        </div>

                        <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500">
                            <span>👤 Penumpang: <strong>{{ $t->passenger_name }}</strong></span>
                            <span>•</span>
                            <span>🗓️ Jadwal: {{ $t->schedule_at->format('d M Y H:i') }} WIB</span>
                            <span>•</span>
                            <span>📍 {{ $t->seat_info }}</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between md:flex-col md:items-end gap-2 pt-3 md:pt-0 border-t md:border-t-0 border-gray-100">
                        <div>
                            <span class="text-[10px] text-gray-400 block md:text-right">Tarif:</span>
                            <span class="text-base font-extrabold text-blue-700">
                                {{ $t->price > 0 ? 'Rp ' . number_format($t->price, 0, ',', '.') : 'Gratis' }}
                            </span>
                        </div>
                        <a href="{{ route('ticketing.show', $t->code) }}" class="px-4 py-2 bg-blue-50 text-blue-700 font-bold text-xs rounded-xl hover:bg-blue-100 transition-colors inline-flex items-center space-x-1">
                            <span>Buka E-Boarding Pass</span>
                            <span>➔</span>
                        </a>
                    </div>
                </div>
            @endforeach

            <!-- Pagination -->
            <div class="mt-4">
                {{ $tickets->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
