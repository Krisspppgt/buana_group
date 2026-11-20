@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-6" x-data="{ activeTab: 'transport', bookingModal: false, selectedRoute: null, eventModal: false, selectedEvent: null }">
    <!-- Breadcrumb & Nav -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-emerald-700">Home</a>
            <span>/</span>
            <span class="text-gray-900 font-semibold">Buana Ticketing</span>
        </div>
    </div>

    <!-- Alert / Messages -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center space-x-3 text-sm">
            <span class="text-xl">✅</span>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <!-- Hero Banner -->
    <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-sky-800 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-8 -bottom-8 w-60 h-60 bg-white/10 rounded-full blur-3xl"></div>
        <div class="relative z-10 max-w-2xl">
            <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-bold tracking-wide uppercase mb-3">
                Unit 4 • Buana Ticketing
            </span>
            <h1 class="text-2xl sm:text-3xl font-black leading-tight">
                Agregator Tiket Transportasi & Event Kampus
            </h1>
            <p class="text-blue-100 text-xs sm:text-sm mt-2 leading-relaxed">
                Pesan tiket resmi Kereta Api (KAI), Penerbangan Domestik, Bus Antarkota, Kapal Laut (PELNI), serta tiket kegiatan civitas Yayasan Wahid Hasyim dengan E-Boarding Pass digital ber-QR Code.
            </p>
        </div>
    </div>

    <!-- Tab Selector -->
    <div class="flex border-b border-gray-200 gap-6">
        <button type="button" 
            @click="activeTab = 'transport'" 
            :class="activeTab === 'transport' ? 'border-blue-600 text-blue-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 font-medium'"
            class="pb-3 text-sm border-b-2 transition-all cursor-pointer">
            <span>Transportasi (KAI / Pesawat / Bus / Kapal)</span>
        </button>
        <button type="button" 
            @click="activeTab = 'events'" 
            :class="activeTab === 'events' ? 'border-blue-600 text-blue-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 font-medium'"
            class="pb-3 text-sm border-b-2 transition-all cursor-pointer">
            <span>Tiket Event & Seminar Kampus</span>
        </button>
    </div>

    <!-- TAB 1: AGREGATOR TRANSPORTASI -->
    <div x-show="activeTab === 'transport'" class="space-y-4">
        <!-- Form Pencarian & Filter Agregator Transportasi -->
        <form method="GET" action="{{ route('ticketing.index') }}" class="bg-white p-4 rounded-3xl border border-gray-200 shadow-2xs grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
            <div class="sm:col-span-4">
                <label class="text-xs font-bold text-gray-700 block mb-1 mt-0">Cari Rute / Kota / Operator</label>
                <input type="text" name="q" value="{{ $searchQuery ?? '' }}" placeholder="Contoh: Semarang, Jakarta, Garuda, KAI..." class="w-full px-3.5 py-2 text-xs border border-gray-300 rounded-xl">
            </div>
            <div class="sm:col-span-3">
                <label class="text-xs font-bold text-gray-700 block mb-1 mt-0">Moda Transportasi</label>
                <select name="mode" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-xl">
                    <option value="all" @selected(($selectedMode ?? '') === '' || ($selectedMode ?? '') === 'all')>Semua Moda Transportasi</option>
                    <option value="kereta" @selected(($selectedMode ?? '') === 'kereta')>Kereta Api (KAI)</option>
                    <option value="pesawat" @selected(($selectedMode ?? '') === 'pesawat')>Pesawat Terbang</option>
                    <option value="bus" @selected(($selectedMode ?? '') === 'bus')>Bus AKAP Antarkota</option>
                    <option value="kapal" @selected(($selectedMode ?? '') === 'kapal')>Kapal Laut (PELNI)</option>
                </select>
            </div>
            <div class="sm:col-span-3">
                <label class="text-xs font-bold text-gray-700 block mb-1 mt-0">Tanggal Keberangkatan</label>
                <input type="date" name="date" value="{{ $travelDate ?? '' }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-xl">
            </div>
            <div class="sm:col-span-2 flex gap-1.5">
                <button type="submit" class="flex-1 py-2.5 px-3 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs transition cursor-pointer">
                    Cari Rute
                </button>
                @if(!empty($searchQuery) || (!empty($selectedMode) && $selectedMode !== 'all') || !empty($travelDate))
                    <a href="{{ route('ticketing.index') }}" class="py-2.5 px-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        <div class="flex items-center justify-between text-xs text-gray-500">
            <span>Menampilkan {{ count($transportRoutes) }} jadwal & rute keberangkatan terverifikasi langsung dengan operator transportasi</span>
            <span class="font-bold text-blue-700">Terintegrasi E-Boarding Pass</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($transportRoutes as $r)
                <div class="bg-white rounded-3xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-all flex flex-col justify-between space-y-4">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center space-x-1.5 px-3 py-1 bg-blue-50 text-blue-700 text-xs font-bold rounded-full">
                                <span>{{ $r['icon'] }}</span>
                                <span>{{ $r['mode_name'] }}</span>
                            </span>
                            <span class="text-xs text-gray-400 font-mono">{{ $r['date'] }}</span>
                        </div>

                        <div>
                            <h3 class="font-bold text-gray-900 text-base">{{ $r['title'] }}</h3>
                            <p class="text-xs text-gray-600 mt-1 flex items-center space-x-1">
                                <span>📍</span>
                                <span>{{ $r['route'] }}</span>
                            </p>
                            <p class="text-xs text-gray-400 mt-0.5">⏰ Waktu: {{ $r['schedule'] }} • Kursi Tersedia: {{ $r['seat_default'] }}</p>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-gray-100 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] text-gray-400 block">Tarif Tiket:</span>
                            <span class="text-lg font-black text-blue-800">Rp {{ number_format($r['price'], 0, ',', '.') }}</span>
                        </div>
                        <button type="button" 
                            @click="selectedRoute = {{ json_encode($r) }}; bookingModal = true"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm transition-colors">
                            Pesan Tiket ➔
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- TAB 2: TIKET EVENT KAMPUS -->
    <div x-show="activeTab === 'events'" class="space-y-4" style="display: none;">
        @if($user && $user->isCivitas())
            <div class="p-3.5 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center justify-between text-xs text-emerald-800">
                <span class="font-semibold">🎓 Benefit Akun Civitas Unwahas Aktif:</span>
                <span class="font-bold">Potongan harga tiket khusus mahasiswa & dosen terapkan otomatis!</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($events as $event)
                @php
                    $isCivitas = $user && $user->isCivitas();
                    $activePrice = $event->priceFor($user);
                    $quotaLeft = max(0, $event->quota - $event->booked);
                @endphp
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden flex flex-col justify-between hover:shadow-md transition-shadow">
                    <div class="p-5 space-y-3">
                        <div class="flex items-center justify-between text-xs">
                            <span class="px-2.5 py-0.5 bg-purple-50 text-purple-700 font-bold rounded-full uppercase">
                                {{ $event->organizer }}
                            </span>
                            <span class="text-gray-400">{{ $event->event_date->format('d M Y') }}</span>
                        </div>

                        <div>
                            <h3 class="font-bold text-gray-900 text-base leading-snug">{{ $event->title }}</h3>
                            <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $event->description }}</p>
                        </div>

                        <div class="text-xs text-gray-600 space-y-1 pt-2 border-t border-gray-50">
                            <p>📍 {{ $event->location }}</p>
                            <p>⏰ Pukul {{ $event->event_date->format('H:i') }} WIB</p>
                            <p class="text-emerald-700 font-medium">🎟️ Sisa Kuota: {{ $quotaLeft }} tiket</p>
                        </div>
                    </div>

                    <div class="p-5 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                        <div>
                            @if($isCivitas && $event->price_civitas < $event->price_general)
                                <span class="text-[10px] text-gray-400 line-through block">Rp {{ number_format($event->price_general, 0, ',', '.') }}</span>
                            @endif
                            <span class="text-base font-black text-gray-900">
                                {{ $activePrice > 0 ? 'Rp ' . number_format($activePrice, 0, ',', '.') : 'GRATIS' }}
                            </span>
                        </div>

                        @if($quotaLeft > 0)
                            <button type="button"
                                @click="selectedEvent = {{ json_encode($event) }}; eventModal = true"
                                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition-colors">
                                Daftar / Beli
                            </button>
                        @else
                            <button disabled class="px-4 py-2 bg-gray-300 text-gray-500 text-xs font-bold rounded-xl cursor-not-allowed">
                                Kuota Penuh
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- MODAL BOOKING TRANSPORT -->
    <div x-show="bookingModal" 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
        style="display: none;">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 space-y-5 shadow-2xl" @click.away="bookingModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-black text-gray-900">Formulir Tiket Transportasi</h3>
                    <p class="text-xs text-gray-500" x-text="selectedRoute ? selectedRoute.title : ''"></p>
                </div>
                <button @click="bookingModal = false" class="text-gray-400 hover:text-gray-600 text-xl font-bold">×</button>
            </div>

            <form action="{{ route('ticketing.order.transport') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="title" :value="selectedRoute ? selectedRoute.title : ''">
                <input type="hidden" name="price" :value="selectedRoute ? selectedRoute.price : 0">
                <input type="hidden" name="seat_info" :value="selectedRoute ? selectedRoute.seat_default : ''">
                <input type="hidden" name="schedule_at" value="{{ now()->addDays(2)->format('Y-m-d H:i:s') }}">

                <div class="p-3 bg-blue-50 rounded-2xl text-xs text-blue-900 space-y-1">
                    <p class="font-bold" x-text="selectedRoute ? selectedRoute.route : ''"></p>
                    <p class="text-blue-700">Waktu: <span x-text="selectedRoute ? selectedRoute.schedule : ''"></span> | Nomor Kursi: <span class="font-mono font-bold" x-text="selectedRoute ? selectedRoute.seat_default : ''"></span></p>
                    <p class="font-black text-sm text-blue-950 pt-1">Total: Rp <span x-text="selectedRoute ? new Intl.NumberFormat('id-ID').format(selectedRoute.price) : '0'"></span></p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Nama Lengkap Penumpang (Sesuai KTP)</label>
                    <input type="text" name="passenger_name" value="{{ $user->name ?? '' }}" required class="w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Nomor Identitas (NIK / Paspor)</label>
                    <input type="text" name="passenger_id" value="{{ $user->isCivitas() ? $user->campus_id : '3301000000000001' }}" required class="w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 font-mono">
                    <p class="text-[10px] text-gray-400 mt-1">Digunakan untuk verifikasi saat cetak boarding pass di stasiun/bandara/pelabuhan.</p>
                </div>

                <div class="flex items-center justify-end space-x-2 pt-2">
                    <button type="button" @click="bookingModal = false" class="px-4 py-2 border border-gray-300 text-gray-700 text-xs font-bold rounded-xl hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md">
                        Konfirmasi & Terbitkan Tiket
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL BOOKING EVENT -->
    <div x-show="eventModal" 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
        style="display: none;">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 space-y-5 shadow-2xl" @click.away="eventModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-black text-gray-900">Registrasi Tiket Event Kampus</h3>
                    <p class="text-xs text-gray-500" x-text="selectedEvent ? selectedEvent.title : ''"></p>
                </div>
                <button @click="eventModal = false" class="text-gray-400 hover:text-gray-600 text-xl font-bold">×</button>
            </div>

            <form action="{{ route('ticketing.order.event') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="event_id" :value="selectedEvent ? selectedEvent.id : ''">

                <div class="p-3 bg-indigo-50 rounded-2xl text-xs text-indigo-900 space-y-1">
                    <p class="font-bold" x-text="selectedEvent ? selectedEvent.title : ''"></p>
                    <p class="text-indigo-700">Lokasi: <span x-text="selectedEvent ? selectedEvent.location : ''"></span></p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Nama Peserta / Pengunjung</label>
                    <input type="text" name="passenger_name" value="{{ $user->name ?? '' }}" required class="w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Nomor Identitas (NIM / NIP / NIK)</label>
                    <input type="text" name="passenger_id" value="{{ $user->isCivitas() ? $user->campus_id : '3301000000000001' }}" required class="w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 font-mono">
                </div>

                <div class="flex items-center justify-end space-x-2 pt-2">
                    <button type="button" @click="eventModal = false" class="px-4 py-2 border border-gray-300 text-gray-700 text-xs font-bold rounded-xl hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-md">
                        Konfirmasi Tiket Masuk
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
