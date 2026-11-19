@extends('layouts.app')
@section('title', 'Buana Loyalty Points - Promosi Silang 5 Unit Usaha')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    {{-- Hero Banner Loyalty Points --}}
    <div class="bg-gradient-to-r from-amber-700 via-yellow-600 to-amber-800 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-8 -bottom-10 w-56 h-56 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div class="max-w-xl">
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-white/20 text-amber-100 text-xs font-bold uppercase tracking-wider mb-3">
                    Buana Loyalty Points &bull; Cross-Selling Ecosystem
                </span>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight">
                    Satu Poin Terintegrasi untuk 5 Unit Usaha
                </h1>
                <p class="text-xs sm:text-sm text-amber-100 mt-1.5 leading-relaxed">
                    Dapatkan cashback <b>1% Buana Points</b> dari setiap transaksi di Air Mineral, Katering, Tour & Travel, Ticketing, maupun Konveksi, lalu tukarkan sebagai potongan harga di unit usaha mana saja!
                </p>
            </div>

            <div class="bg-white/15 backdrop-blur-md border border-white/25 rounded-3xl p-5 text-center sm:text-right shrink-0">
                <span class="text-xs text-amber-100 font-semibold block">Saldo Aktif Anda</span>
                <div class="text-3xl font-black text-white mt-0.5">
                    {{ number_format($user->loyalty_points, 0, ',', '.') }} <span class="text-sm font-bold">Poin</span>
                </div>
                <span class="text-xs text-amber-200 font-bold block mt-1">
                    Setara Diskon Rp {{ number_format($user->loyalty_points, 0, ',', '.') }}
                </span>
            </div>
        </div>
    </div>

    {{-- Statistik Poin --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="card flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-amber-50 border border-amber-200 text-amber-700 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div class="text-xs text-gray-500 font-semibold">Saldo Poin Tersedia</div>
                <div class="text-xl font-black text-gray-900">{{ number_format($user->loyalty_points, 0, ',', '.') }} Poin</div>
            </div>
        </div>

        <div class="card flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
            <div>
                <div class="text-xs text-gray-500 font-semibold">Total Poin Diperoleh</div>
                <div class="text-xl font-black text-emerald-700">+{{ number_format($totalEarned, 0, ',', '.') }} Poin</div>
            </div>
        </div>

        <div class="card flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
            </div>
            <div>
                <div class="text-xs text-gray-500 font-semibold">Total Poin Ditukarkan</div>
                <div class="text-xl font-black text-rose-700">{{ number_format($totalRedeemed, 0, ',', '.') }} Poin</div>
            </div>
        </div>
    </div>

    {{-- Katalog Promosi Silang (Cross-Selling) 5 Unit Usaha --}}
    <div class="card space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <div>
                <h2 class="text-base font-bold text-gray-900">Tukarkan Poin Lintas Unit Usaha (Cross-Selling)</h2>
                <p class="text-xs text-gray-500">Contoh: Kumpulkan poin dari pemesanan Katering Fakultas, lalu gunakan untuk diskon Galon Air Mineral atau PDH Konveksi.</p>
            </div>
            <span class="text-xs font-bold text-[#0D5A47] bg-emerald-50 px-3 py-1 rounded-full">1 Poin = Rp 1</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-5 gap-3 text-xs">
            <a href="{{ route('water.index') }}" class="p-3.5 rounded-2xl border border-gray-200 hover:border-sky-400 hover:bg-sky-50/40 transition flex flex-col justify-between gap-2">
                <div>
                    <div class="font-bold text-gray-900">Air Mineral</div>
                    <p class="text-[11px] text-gray-500 mt-1">Tukar poin untuk diskon galon 19L & dus botol</p>
                </div>
                <span class="font-bold text-sky-700">Gunakan Poin &rarr;</span>
            </a>

            <a href="{{ route('food.index') }}" class="p-3.5 rounded-2xl border border-gray-200 hover:border-amber-400 hover:bg-amber-50/40 transition flex flex-col justify-between gap-2">
                <div>
                    <div class="font-bold text-gray-900">Food Services</div>
                    <p class="text-[11px] text-gray-500 mt-1">Potongan harga snack box & nasi kotak rapat</p>
                </div>
                <span class="font-bold text-amber-700">Gunakan Poin &rarr;</span>
            </a>

            <a href="{{ route('tour.index') }}" class="p-3.5 rounded-2xl border border-gray-200 hover:border-emerald-400 hover:bg-emerald-50/40 transition flex flex-col justify-between gap-2">
                <div>
                    <div class="font-bold text-gray-900">Tour & Travel</div>
                    <p class="text-[11px] text-gray-500 mt-1">Cashback poin besar dari tabungan Umrah & KKL</p>
                </div>
                <span class="font-bold text-[#0D5A47]">Kumpulkan Poin &rarr;</span>
            </a>

            <a href="{{ route('ticketing.index') }}" class="p-3.5 rounded-2xl border border-gray-200 hover:border-indigo-400 hover:bg-indigo-50/40 transition flex flex-col justify-between gap-2">
                <div>
                    <div class="font-bold text-gray-900">Ticketing</div>
                    <p class="text-[11px] text-gray-500 mt-1">Dapatkan poin dari tiket kereta, pesawat & seminar</p>
                </div>
                <span class="font-bold text-indigo-700">Kumpulkan Poin &rarr;</span>
            </a>

            <a href="{{ route('konveksi.index') }}" class="p-3.5 rounded-2xl border border-gray-200 hover:border-pink-400 hover:bg-pink-50/40 transition flex flex-col justify-between gap-2">
                <div>
                    <div class="font-bold text-gray-900">Konveksi</div>
                    <p class="text-[11px] text-gray-500 mt-1">Tukar poin untuk potongan order PDH & almamater</p>
                </div>
                <span class="font-bold text-pink-700">Gunakan Poin &rarr;</span>
            </a>
        </div>
    </div>

    {{-- Riwayat Mutasi Poin --}}
    <div class="card space-y-4">
        <h2 class="text-base font-bold text-gray-900">Riwayat Perolehan & Penukaran Poin</h2>

        @if ($histories->isEmpty())
            <div class="text-center py-8 text-xs text-gray-500">
                <div class="font-bold text-gray-800 text-sm">Belum Ada Riwayat Mutasi Poin</div>
                <p class="mt-1">Lakukan pemesanan dan pembayaran di salah satu unit usaha untuk mulai mengumpulkan Buana Loyalty Points.</p>
            </div>
        @else
            <div class="divide-y divide-gray-100">
                @foreach ($histories as $h)
                    <div class="py-3 flex items-center justify-between gap-4 text-xs">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-base font-bold shrink-0 {{ $h->type === 'earn' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-800' }}">
                                {{ $h->type === 'earn' ? '+' : '−' }}
                            </div>
                            <div>
                                <div class="font-bold text-gray-900">{{ $h->description }}</div>
                                <div class="text-[11px] text-gray-400">
                                    {{ $h->created_at->format('d M Y, H:i') }} WIB
                                    @if ($h->unit)
                                        &bull; {{ $h->unit->label() }}
                                    @endif
                                    @if ($h->reference_code)
                                        &bull; Ref: <span class="font-mono">{{ $h->reference_code }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="font-black text-sm {{ $h->points >= 0 ? 'text-emerald-700' : 'text-amber-700' }}">
                            {{ $h->points >= 0 ? '+'.number_format($h->points, 0, ',', '.') : number_format($h->points, 0, ',', '.') }} Poin
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pt-3">
                {{ $histories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
