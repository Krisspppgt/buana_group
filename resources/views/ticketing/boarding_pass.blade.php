@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Breadcrumb & Nav -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-emerald-700">Home</a>
            <span>/</span>
            <a href="{{ route('ticketing.index') }}" class="hover:text-emerald-700">Buana Ticketing</a>
            <span>/</span>
            <span class="text-gray-900 font-semibold">E-Boarding Pass</span>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('ticketing.history') }}" class="inline-flex items-center text-xs font-semibold px-3 py-1.5 rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 shadow-sm">
                📋 Tiket Saya
            </a>
            <button onclick="window.print()" class="inline-flex items-center text-xs font-semibold px-3 py-1.5 rounded-lg bg-blue-600 text-white hover:bg-blue-700 shadow-sm">
                🖨️ Cetak Pass
            </button>
        </div>
    </div>

    <!-- Alert / Messages -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center space-x-3 text-sm">
            <span class="text-xl">✅</span>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('warning'))
        <div class="p-4 bg-amber-50 border border-amber-200 text-amber-800 rounded-2xl flex items-center space-x-3 text-sm">
            <span class="text-xl">⚠️</span>
            <p>{{ session('warning') }}</p>
        </div>
    @endif

    <!-- E-BOARDING PASS CARD DESIGN -->
    <div class="bg-white rounded-3xl shadow-xl border border-gray-200 overflow-hidden relative">
        <!-- Top Banner Header -->
        <div class="p-6 text-white flex items-center justify-between
            @if($ticket->category === 'transport') bg-gradient-to-r from-blue-800 to-indigo-900
            @else bg-gradient-to-r from-purple-800 to-pink-900 @endif">
            <div class="flex items-center space-x-3">
                <span class="text-3xl">
                    @if($ticket->category === 'transport') 🚆 @else 🎟️ @endif
                </span>
                <div>
                    <h2 class="text-base font-black uppercase tracking-wider">
                        {{ $ticket->category === 'transport' ? 'Digital E-Boarding Pass' : 'Campus Event E-Ticket' }}
                    </h2>
                    <p class="text-xs text-blue-200">PT Buana Sejahtera • Yayasan Wahid Hasyim</p>
                </div>
            </div>
            <div class="text-right">
                <span class="inline-block px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider
                    @if($ticket->status === 1) bg-emerald-400 text-teal-950
                    @elseif($ticket->status === 0) bg-amber-300 text-amber-950
                    @else bg-rose-400 text-rose-950 @endif">
                    {{ $ticket->status_label }}
                </span>
                <p class="text-[10px] text-blue-200 mt-1 font-mono">#{{ $ticket->code }}</p>
            </div>
        </div>

        <!-- Body Main Content -->
        <div class="p-6 space-y-6">
            <!-- Event / Transport Title & Location -->
            <div>
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Layanan / Acara</p>
                <h3 class="text-xl font-black text-gray-900 mt-0.5">{{ $ticket->title }}</h3>
                <p class="text-xs text-gray-600 mt-1 flex items-center space-x-1.5">
                    <span>📍</span>
                    <span>{{ $ticket->seat_info }}</span>
                </p>
            </div>

            <!-- Detail Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-100 text-xs">
                <div>
                    <span class="text-gray-400 block font-medium">Nama Penumpang</span>
                    <span class="font-bold text-gray-900 text-sm mt-0.5 block truncate">{{ $ticket->passenger_name }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block font-medium">No. Identitas / NIK</span>
                    <span class="font-bold text-gray-900 font-mono text-sm mt-0.5 block truncate">{{ $ticket->passenger_id }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block font-medium">Jadwal Keberangkatan</span>
                    <span class="font-bold text-gray-900 text-sm mt-0.5 block">{{ $ticket->schedule_at->format('d M Y') }}</span>
                    <span class="text-[10px] text-gray-500 font-mono">{{ $ticket->schedule_at->format('H:i') }} WIB</span>
                </div>
                <div>
                    <span class="text-gray-400 block font-medium">Tarif Tiket</span>
                    <span class="font-extrabold text-blue-700 text-sm mt-0.5 block">
                        {{ $ticket->price > 0 ? 'Rp ' . number_format($ticket->price, 0, ',', '.') : 'Gratis' }}
                    </span>
                </div>
            </div>

            <!-- Perforation Cut Line with Notches -->
            <div class="relative py-2 flex items-center justify-between">
                <div class="absolute -left-10 w-8 h-8 bg-gray-100 rounded-full border-r border-gray-300"></div>
                <div class="w-full border-b-2 border-dashed border-gray-300"></div>
                <div class="absolute -right-10 w-8 h-8 bg-gray-100 rounded-full border-l border-gray-300"></div>
            </div>

            <!-- Digital QR & Barcode Section -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-6 pt-2">
                <!-- QR Code Box -->
                <div class="flex flex-col items-center p-4 bg-white border-2 border-gray-200 rounded-2xl shadow-inner">
                    <!-- SVG QR Code Generator Visual Simulation -->
                    <div class="w-36 h-36 bg-white p-2 flex flex-col justify-between border border-gray-100 rounded-lg">
                        <svg viewBox="0 0 100 100" class="w-full h-full text-gray-900 fill-current">
                            <!-- Outer Corners -->
                            <rect x="5" y="5" width="28" height="28" fill="none" stroke="currentColor" stroke-width="4"/>
                            <rect x="11" y="11" width="16" height="16"/>
                            <rect x="67" y="5" width="28" height="28" fill="none" stroke="currentColor" stroke-width="4"/>
                            <rect x="73" y="11" width="16" height="16"/>
                            <rect x="5" y="67" width="28" height="28" fill="none" stroke="currentColor" stroke-width="4"/>
                            <rect x="11" y="73" width="16" height="16"/>
                            <!-- Data Matrix Simulation -->
                            <rect x="38" y="8" width="6" height="6"/>
                            <rect x="48" y="8" width="6" height="12"/>
                            <rect x="38" y="20" width="18" height="6"/>
                            <rect x="8" y="38" width="12" height="6"/>
                            <rect x="25" y="38" width="6" height="16"/>
                            <rect x="38" y="38" width="8" height="8"/>
                            <rect x="52" y="38" width="12" height="6"/>
                            <rect x="70" y="38" width="8" height="16"/>
                            <rect x="84" y="38" width="8" height="8"/>
                            <rect x="38" y="52" width="6" height="16"/>
                            <rect x="50" y="52" width="14" height="6"/>
                            <rect x="50" y="64" width="8" height="14"/>
                            <rect x="68" y="60" width="14" height="8"/>
                            <rect x="38" y="74" width="8" height="18"/>
                            <rect x="52" y="84" width="16" height="8"/>
                            <rect x="74" y="74" width="18" height="18"/>
                        </svg>
                    </div>
                    <span class="text-[10px] text-gray-400 font-mono mt-2 uppercase tracking-wider">Pindai di Gate Masuk</span>
                </div>

                <!-- Barcode & Instructions -->
                <div class="flex-1 space-y-3 text-center sm:text-left">
                    <div>
                        <h4 class="text-xs font-bold text-gray-700 uppercase">Petunjuk Check-in Digital</h4>
                        <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                            Tunjukkan kode QR ini langsung dari layar ponsel Anda kepada petugas gerbang stasiun, bandara, atau panitia event di lokasi untuk proses verifikasi instan.
                        </p>
                    </div>

                    <!-- Simulated Barcode Lines -->
                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 flex flex-col items-center sm:items-start space-y-1">
                        <div class="flex items-center space-x-0.5 h-10 w-full justify-center sm:justify-start overflow-hidden">
                            @for($i = 0; $i < 48; $i++)
                                <div class="bg-gray-800 h-full" style="width: {{ ($i % 3 === 0) ? '3px' : (($i % 2 === 0) ? '1.5px' : '4px') }}; margin-right: {{ ($i % 4 === 0) ? '3px' : '1.5px' }};"></div>
                            @endfor
                        </div>
                        <p class="text-[11px] font-mono tracking-widest text-gray-600 font-bold">
                            {{ $ticket->qr_payload }}
                        </p>
                    </div>

                    @if($ticket->checked_in_at)
                        <div class="text-[11px] text-emerald-700 bg-emerald-50 p-2 rounded-lg font-medium">
                            🕒 Waktu Check-in: {{ $ticket->checked_in_at->format('d M Y - H:i:s') }} WIB
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- PETUGAS SCAN SIMULATOR (JIKA PETUGAS / OPERATOR / ADMIN) -->
        @if(auth()->user() && (auth()->user()->isAdmin() || auth()->user()->isStaff()))
            <div class="p-5 bg-gradient-to-r from-slate-900 to-gray-900 text-white border-t border-gray-800 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="text-lg">🛡️</span>
                        <div>
                            <h4 class="text-xs font-black uppercase text-amber-400">Gate Scanner Simulator (Staff Mode)</h4>
                            <p class="text-[11px] text-gray-400">Fitur petugas pintu masuk stasiun/bandara/venue event untuk validasi check-in.</p>
                        </div>
                    </div>
                    <div>
                        <form action="{{ route('ticketing.scan', $ticket->code) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                @if($ticket->status === 1) disabled class="px-4 py-2 bg-gray-700 text-gray-400 rounded-xl text-xs font-bold cursor-not-allowed"
                                @else class="px-4 py-2 bg-emerald-500 hover:bg-emerald-400 text-slate-950 rounded-xl text-xs font-black shadow-lg transition-transform active:scale-95" @endif>
                                {{ $ticket->status === 1 ? 'Sudah Di-Scan' : '⚡ Pindai / Validasi Masuk' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Back button -->
    <div class="text-center pt-2">
        <a href="{{ route('ticketing.index') }}" class="text-xs text-blue-600 hover:underline font-semibold">
            ← Kembali ke Katalog Tiket
        </a>
    </div>
</div>
@endsection
