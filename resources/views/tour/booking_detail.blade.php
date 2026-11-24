@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Breadcrumb & Top Bar -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center space-x-2 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-emerald-700">Home</a>
            <span>/</span>
            <a href="{{ route('tour.index') }}" class="hover:text-emerald-700">Tour & Travel</a>
            <span>/</span>
            <span class="text-gray-900 font-semibold">Detail Booking #{{ $booking->code }}</span>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('orders.history') }}" class="inline-flex items-center text-xs font-semibold px-3 py-1.5 rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50">
                📋 Riwayat Booking
            </a>
            <button onclick="window.print()" class="inline-flex items-center text-xs font-semibold px-3 py-1.5 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm">
                🖨️ Cetak Bukti
            </button>
        </div>
    </div>

    <!-- Alert Success / Info -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center space-x-3 text-sm">
            <span class="text-xl">✅</span>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <!-- Header Card -->
    <div class="bg-gradient-to-r from-teal-800 to-emerald-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-8 -bottom-8 w-48 h-48 bg-white/10 rounded-full blur-2xl"></div>
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-bold tracking-wider uppercase mb-2">
                    Kode Booking: {{ $booking->code }}
                </span>
                <h1 class="text-2xl sm:text-3xl font-black">{{ $booking->tourPackage->title }}</h1>
                <p class="text-emerald-200 text-sm mt-1 flex items-center space-x-2">
                    <span class="capitalize">🏷️ {{ $booking->tourPackage->category }}</span>
                    <span>•</span>
                    <span>⏳ {{ $booking->tourPackage->duration }}</span>
                    <span>•</span>
                    <span>🗓️ Berangkat: {{ $booking->tourPackage->departure_date->format('d M Y') }}</span>
                </p>
            </div>
            <div class="sm:text-right">
                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-black shadow-md
                    @if($booking->status === 2) bg-emerald-400 text-teal-950
                    @elseif($booking->status === 1) bg-amber-300 text-amber-950
                    @else bg-blue-300 text-blue-950 @endif">
                    {{ $booking->status_label }}
                </span>
                <p class="text-xs text-emerald-200 mt-2">Didaftarkan: {{ $booking->created_at->format('d M Y H:i') }} WIB</p>
            </div>
        </div>
    </div>

    <!-- Grid Detail: Status Pembayaran & Skema -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Kolom Info Pembayaran -->
        <div class="md:col-span-2 bg-white rounded-3xl p-6 border border-gray-100 shadow-sm space-y-6">
            <h2 class="text-lg font-bold text-gray-900 flex items-center space-x-2">
                <span>💳</span>
                <span>Rincian Skema & Pembayaran</span>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                    <p class="text-xs text-gray-500 font-medium">Metode Skema</p>
                    <p class="text-base font-bold text-gray-900 mt-0.5 capitalize flex items-center space-x-1.5">
                        @if($booking->payment_scheme === 'cicilan')
                            <span>🏦 Cicilan / Tabungan Umrah</span>
                        @else
                            <span>💵 Pembayaran Tunai (Lunas)</span>
                        @endif
                    </p>
                    <p class="text-xs text-gray-400 mt-1">Tenor: {{ $booking->installment_tenor }} Bulan</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                    <p class="text-xs text-gray-500 font-medium">Jumlah Jamaah / Peserta</p>
                    <p class="text-base font-bold text-gray-900 mt-0.5">{{ $booking->total_pax }} Orang</p>
                    <p class="text-xs text-gray-400 mt-1">Koordinator: {{ $booking->coordinator_name }}</p>
                </div>
            </div>

            <!-- Progress Pembayaran Tabungan / Cicilan -->
            @php
                $percentage = $booking->total_amount > 0 ? min(100, round(($booking->paid_amount / $booking->total_amount) * 100)) : 0;
                $remaining = max(0, $booking->total_amount - $booking->paid_amount);
                $perMonth = ($booking->installment_tenor > 0 && $remaining > 0) ? round($remaining / $booking->installment_tenor) : 0;
            @endphp

            <div class="p-5 bg-gradient-to-br from-teal-50 to-emerald-50 rounded-2xl border border-emerald-100 space-y-4">
                <div class="flex items-center justify-between text-sm">
                    <span class="font-semibold text-gray-700">Progres Pembayaran</span>
                    <span class="font-extrabold text-teal-800">{{ $percentage }}% Terbayar</span>
                </div>
                
                <div class="w-full bg-emerald-200/60 h-3 rounded-full overflow-hidden">
                    <div class="bg-gradient-to-r from-teal-600 to-emerald-600 h-full rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 pt-2 text-xs">
                    <div>
                        <span class="text-gray-500 block">Total Tagihan:</span>
                        <span class="font-bold text-gray-900 text-sm">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div>
                        <span class="text-emerald-700 block">Sudah Dibayar (DP):</span>
                        <span class="font-bold text-emerald-800 text-sm">Rp {{ number_format($booking->paid_amount, 0, ',', '.') }}</span>
                    </div>
                    <div>
                        <span class="text-rose-600 block">Sisa Angsuran:</span>
                        <span class="font-bold text-rose-700 text-sm">Rp {{ number_format($remaining, 0, ',', '.') }}</span>
                    </div>
                </div>

                @if($booking->payment_scheme === 'cicilan' && $remaining > 0)
                    <div class="p-4 bg-white rounded-2xl border border-emerald-200 text-xs text-gray-700 space-y-3 shadow-2xs">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <div>
                                <span class="font-bold text-emerald-900 block">Setor Angsuran / Tabungan Perjalanan:</span>
                                <span class="text-gray-500">Estimasi bulanan: Rp {{ number_format($perMonth, 0, ',', '.') }} / bulan ({{ $booking->installment_tenor }} bln)</span>
                            </div>
                            <a href="https://wa.me/6282222222222?text=Halo%20Admin%20Buana%20Tour,%20saya%20ingin%20bayar%20angsuran%20booking%20{{ $booking->code }}" target="_blank" class="text-emerald-700 hover:underline font-semibold">
                                💬 Konfirmasi via WA ↗
                            </a>
                        </div>

                        <form method="POST" action="{{ route('tour.bookings.pay', $booking->code) }}" class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 pt-2 border-t border-gray-100">
                            @csrf
                            <div>
                                <label class="text-[11px] font-bold text-gray-600 block mb-1">Nominal Setoran (Rp)</label>
                                <input type="number" name="amount" value="{{ min($remaining, max(10000, $perMonth)) }}" min="10000" max="{{ $remaining }}" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-xl font-bold text-gray-900">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-600 block mb-1">Kanal Pembayaran</label>
                                <select name="payment_method" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-xl text-gray-800">
                                    <option value="QRIS Terpadu Buana">QRIS Terpadu Buana</option>
                                    <option value="VA Bank Syariah Indonesia">VA Bank Syariah (BSI)</option>
                                    <option value="VA Bank Jateng Syariah">VA Bank Jateng</option>
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="w-full py-2 px-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold shadow-xs transition">
                                    💳 Bayar Cicilan Sekarang
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>

            <!-- Manifes Anggota Rombongan -->
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="font-bold text-gray-900 text-sm flex items-center space-x-2">
                        <span>👥</span>
                        <span>Manifest Data Rombongan ({{ count($booking->members_data ?? []) }} Jamaah/Peserta)</span>
                    </h3>
                    @if($booking->manifest_file)
                        <a href="{{ asset('storage/' . $booking->manifest_file) }}" target="_blank" class="text-xs text-emerald-600 hover:text-emerald-700 font-semibold underline">
                            📄 Unduh Lampiran Berkas
                        </a>
                    @endif
                </div>

                @if(!empty($booking->members_data) && count($booking->members_data) > 0)
                    <div class="overflow-x-auto rounded-2xl border border-gray-100">
                        <table class="w-full text-xs text-left">
                            <thead class="bg-gray-50 text-gray-600 font-semibold border-b border-gray-100">
                                <tr>
                                    <th class="p-3">No</th>
                                    <th class="p-3">Nama Jamaah / Peserta</th>
                                    <th class="p-3">No. KTP / Paspor / NIM</th>
                                    <th class="p-3">Status Dokumen</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($booking->members_data as $idx => $member)
                                    <tr class="hover:bg-gray-50/50">
                                        <td class="p-3 font-semibold text-gray-400">{{ $idx + 1 }}</td>
                                        <td class="p-3 font-bold text-gray-900">{{ $member['name'] ?? '-' }}</td>
                                        <td class="p-3 font-mono text-gray-600">{{ $member['id_number'] ?? '-' }}</td>
                                        <td class="p-3">
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                                Tervalidasi
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-4 bg-gray-50 rounded-2xl text-center text-xs text-gray-500">
                        Manifest rombongan diunggah melalui berkas terlampir oleh koordinator.
                    </div>
                @endif
            </div>
        </div>

        <!-- Kolom Info Kontak & Itinerary Ringkas -->
        <div class="space-y-6">
            <!-- Kontak Koordinator -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm space-y-4">
                <h3 class="font-bold text-gray-900 text-sm flex items-center space-x-2">
                    <span>👤</span>
                    <span>Informasi Koordinator</span>
                </h3>
                <div class="space-y-3 text-xs">
                    <div>
                        <span class="text-gray-400 block">Nama Koordinator:</span>
                        <span class="font-semibold text-gray-900">{{ $booking->coordinator_name }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block">No. Kontak WhatsApp:</span>
                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', $booking->coordinator_phone) }}" target="_blank" class="font-mono font-semibold text-emerald-600 hover:underline">
                            {{ $booking->coordinator_phone }} ↗
                        </a>
                    </div>
                    <div>
                        <span class="text-gray-400 block">Akun Pemesan:</span>
                        <span class="font-semibold text-gray-800">{{ $booking->user->name }} ({{ $booking->user->phone }})</span>
                    </div>
                </div>
            </div>

            <!-- Itinerary Ringkas -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm space-y-4">
                <h3 class="font-bold text-gray-900 text-sm flex items-center space-x-2">
                    <span>✨</span>
                    <span>Itinerary Perjalanan</span>
                </h3>
                <p class="text-xs text-gray-600 leading-relaxed">
                    {{ $booking->tourPackage->itinerary ?? 'Itinerary lengkap akan diinformasikan oleh koordinator tour.' }}
                </p>
            </div>

            <!-- Help & Hotline -->
            <div class="bg-gradient-to-br from-teal-900 to-emerald-950 rounded-3xl p-6 text-white text-xs space-y-3 shadow-lg">
                <h4 class="font-bold text-sm text-emerald-300">Butuh Bantuan Rombongan?</h4>
                <p class="text-gray-200 leading-relaxed">
                    Hubungi Customer Relation Buana Tour & Travel jika memerlukan penyesuaian jadwal atau penambahan kuota jamaah rombongan Anda.
                </p>
                <a href="https://wa.me/6282222222222?text=Halo%20Buana%20Tour,%20saya%20butuh%20info%20terkait%20booking%20{{ $booking->code }}" target="_blank" class="block w-full py-2.5 bg-emerald-500 hover:bg-emerald-400 text-teal-950 font-black rounded-xl text-center shadow-md">
                    Hubungi CS Tour (WhatsApp)
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
