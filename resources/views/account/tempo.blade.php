@extends('layouts.app')
@section('title', 'Fasilitas & Tagihan Tempo B2B - Buana Group')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    {{-- Header Banner Tempo B2B --}}
    <div class="bg-gradient-to-r from-[#094234] via-[#0D5A47] to-[#0a4d3c] rounded-3xl p-6 sm:p-8 text-white shadow-lg relative overflow-hidden">
        <div class="absolute -right-8 -bottom-10 w-52 h-52 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="space-y-1.5">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-400/20 border border-emerald-300/30 text-emerald-100 text-[11px] font-bold">
                        {{ $user->role_badge }}
                    </span>
                    @if ($user->tempo_approved && $tempoLimit > 0)
                        <span class="px-2.5 py-0.5 rounded-full bg-blue-400/25 border border-blue-300/40 text-blue-100 text-[11px] font-bold">
                            TOP Maksimal 30 Hari Aktif
                        </span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full bg-amber-400/25 border border-amber-300/40 text-amber-100 text-[11px] font-bold">
                            Fasilitas Tempo Belum Aktif
                        </span>
                    @endif
                </div>
                <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight">Fasilitas &amp; Tagihan Tempo B2B</h1>
                <p class="text-xs sm:text-sm text-emerald-100">
                    {{ $user->name }}
                    @if ($user->company_name)
                        &bull; Instansi: <b class="text-white">{{ $user->company_name }}</b>
                    @endif
                    @if ($user->faculty)
                        &bull; Unit: <b class="text-white">{{ $user->faculty }}</b>
                    @endif
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2 shrink-0">
                <a href="{{ route('account.index') }}" class="px-4 py-2.5 rounded-xl bg-white/15 hover:bg-white/25 text-white font-bold text-xs border border-white/20 transition">
                    Profil &amp; Alamat
                </a>
                <a href="{{ route('orders.history') }}" class="px-4 py-2.5 rounded-xl bg-amber-400 hover:bg-amber-300 text-amber-950 font-bold text-xs shadow-sm transition">
                    Semua Pesanan
                </a>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 text-sm font-semibold">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if (session('info'))
        <div class="p-4 bg-blue-50 border border-blue-200 text-blue-800 rounded-2xl flex items-center gap-3 text-sm font-semibold">
            <svg class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>{{ session('info') }}</div>
        </div>
    @endif

    @if ($hasOverdue)
        <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-2xl flex items-start gap-3 text-xs">
            <svg class="w-5 h-5 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <div>
                <div class="font-extrabold text-sm">Terdapat Tagihan Tempo yang Melewati Jatuh Tempo (Overdue &gt; 30 Hari)</div>
                <p class="mt-0.5">Penggunaan Tempo baru dikunci sementara. Silakan lunasi tagihan yang jatuh tempo di bawah ini melalui Midtrans (QRIS, Virtual Account, atau E-Wallet) untuk membuka kembali fasilitas Tempo Anda.</p>
            </div>
        </div>
    @endif

    {{-- 1. RINGKASAN LIMIT & PROGRESS BAR --}}
    @if ($user->tempo_approved && $tempoLimit > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="card border border-emerald-200 bg-gradient-to-br from-emerald-50/70 to-white space-y-1">
                <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-700">Sisa Limit Tersedia</span>
                <div class="text-xl sm:text-2xl font-black text-[#0D5A47]">
                    Rp {{ number_format($remainingTempo, 0, ',', '.') }}
                </div>
                <p class="text-[11px] text-gray-500">Siap dipakai untuk transaksi B2B</p>
            </div>

            <div class="card border border-amber-200 bg-gradient-to-br from-amber-50/60 to-white space-y-1">
                <span class="text-[11px] font-bold uppercase tracking-wider text-amber-800">Tempo Terpakai (Aktif)</span>
                <div class="text-xl sm:text-2xl font-black text-amber-700">
                    Rp {{ number_format($usedTempo, 0, ',', '.') }}
                </div>
                <p class="text-[11px] text-gray-500">{{ $pendingPayments->count() }} invoice menunggu pelunasan</p>
            </div>

            <div class="card border border-blue-200 bg-gradient-to-br from-blue-50/60 to-white space-y-1">
                <span class="text-[11px] font-bold uppercase tracking-wider text-blue-800">Plafon Limit Disetujui</span>
                <div class="text-xl sm:text-2xl font-black text-blue-900">
                    Rp {{ number_format($tempoLimit, 0, ',', '.') }}
                </div>
                <p class="text-[11px] text-gray-500">Skema TOP maksimal 30 hari</p>
            </div>

            <div class="card border border-gray-200 bg-white space-y-1">
                <span class="text-[11px] font-bold uppercase tracking-wider text-gray-500">Total Tempo Dilunasi</span>
                <div class="text-xl sm:text-2xl font-black text-gray-900">
                    Rp {{ number_format($settledTotal, 0, ',', '.') }}
                </div>
                <p class="text-[11px] text-gray-500">{{ $settledPayments->count() }} invoice sudah lunas</p>
            </div>
        </div>

        {{-- Visual Progress Bar Pemakaian Limit --}}
        <div class="card space-y-3">
            <div class="flex flex-wrap items-center justify-between gap-2 text-xs">
                <div>
                    <span class="font-extrabold text-gray-900">Persentase Pemakaian Limit Tempo</span>
                    <span class="text-gray-500 ml-1">({{ $usagePercent }}% terpakai dari plafon Rp {{ number_format($tempoLimit, 0, ',', '.') }})</span>
                </div>
                <span class="font-bold {{ $usagePercent >= 80 ? 'text-red-600' : 'text-[#0D5A47]' }}">
                    Sisa {{ 100 - $usagePercent }}% (Rp {{ number_format($remainingTempo, 0, ',', '.') }})
                </span>
            </div>
            <div class="w-full h-3 bg-gray-100 rounded-full overflow-hidden border border-gray-200">
                <div
                    class="h-full rounded-full transition-all duration-300 {{ $usagePercent >= 85 ? 'bg-red-500' : ($usagePercent >= 60 ? 'bg-amber-500' : 'bg-[#0D5A47]') }}"
                    style="width: {{ $usagePercent }}%;"
                ></div>
            </div>
            <p class="text-[11px] text-gray-500">
                Setiap kali Anda melunasi tagihan Tempo melalui <b>Midtrans (QRIS, Virtual Account, GoPay, DANA)</b>, status tagihan otomatis berubah menjadi <b>Lunas</b> dan sisa limit Anda langsung pulih saat itu juga.
            </p>
        </div>
    @else
        <div class="card border border-amber-200 bg-amber-50/50 space-y-2">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center shrink-0 font-bold">
                    !
                </div>
                <div>
                    <h2 class="text-sm font-extrabold text-gray-900">Fasilitas Kredit Tempo B2B Belum Aktif pada Akun Ini</h2>
                    <p class="text-xs text-gray-600 mt-0.5">
                        Fasilitas pembayaran Tempo (TOP 30 Hari) diberikan kepada akun <b>Mitra B2B / Koordinator</b> atau <b>Civitas Akademika</b> yang telah diverifikasi dan disetujui plafon limit kreditnya oleh Admin PT Buana Sejahtera.
                    </p>
                </div>
            </div>
            <div class="pt-1">
                <a href="{{ route('account.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#0D5A47] hover:bg-[#094234] text-white text-xs font-bold transition">
                    <span>Lengkapi Data Instansi / Civitas di Profil</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>
    @endif

    {{-- 2. TAGIHAN TEMPO AKTIF (MENUNGGU PELUNASAN) --}}
    <div class="card space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-2 pb-3 border-b border-gray-100">
            <div>
                <h2 class="text-base font-extrabold text-gray-900">Tagihan Tempo Aktif (Menunggu Pelunasan)</h2>
                <p class="text-xs text-gray-500 mt-0.5">Rincian pesanan yang menggunakan limit Tempo Anda dan dapat langsung dilunasi via Midtrans.</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $pendingPayments->isNotEmpty() ? 'bg-amber-100 text-amber-900' : 'bg-emerald-100 text-emerald-800' }}">
                {{ $pendingPayments->count() }} Tagihan Aktif
            </span>
        </div>

        @if ($pendingPayments->isEmpty())
            <div class="py-8 text-center space-y-2">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-[#0D5A47] mx-auto flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="text-sm font-bold text-gray-800">Tidak Ada Tagihan Tempo yang Menunggu Pelunasan</div>
                <p class="text-xs text-gray-500">Seluruh limit Tempo Anda bebas dari tunggakan aktif.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($pendingPayments as $payment)
                    @php
                        $order = $payment->order;
                        $dueDate = $payment->due_date ?? $payment->created_at?->copy()->addDays(30);
                        $isOverdue = $dueDate !== null && $dueDate->isPast();
                        $daysDiff = $dueDate ? (int) max(1, now()->diffInDays($dueDate)) : 30;
                    @endphp
                    @if ($order)
                        <div class="p-4 sm:p-5 rounded-2xl border {{ $isOverdue ? 'border-red-300 bg-red-50/30' : 'border-blue-200 bg-blue-50/20' }} space-y-4">
                            {{-- Baris Atas: Info Invoice, Unit, Jatuh Tempo, Nominal --}}
                            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3 pb-3 border-b border-gray-200/80">
                                <div class="space-y-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="px-2.5 py-0.5 rounded-lg bg-[#0D5A47] text-white text-[11px] font-bold">
                                            {{ $order->unit->label() }}
                                        </span>
                                        <span class="font-mono text-xs font-extrabold text-gray-900">{{ $payment->ref }}</span>
                                        <span class="text-xs text-gray-500">(Order #{{ $order->code }})</span>
                                    </div>
                                    <div class="text-xs text-gray-600 flex flex-wrap items-center gap-x-3 gap-y-1">
                                        <span>Dipakai: <b>{{ $payment->created_at?->format('d M Y, H:i') }} WIB</b></span>
                                        @if ($dueDate)
                                            <span>&bull; Jatuh Tempo: <b class="{{ $isOverdue ? 'text-red-700' : 'text-gray-900' }}">{{ $dueDate->format('d M Y') }}</b></span>
                                        @endif
                                    </div>
                                </div>

                                <div class="sm:text-right space-y-1">
                                    <div class="text-xs text-gray-500">Nominal Pemakaian Tempo</div>
                                    <div class="text-lg font-black text-[#0D5A47]">
                                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                    </div>
                                    @if ($isOverdue)
                                        <span class="inline-block px-2.5 py-0.5 rounded-full bg-red-100 text-red-800 text-[11px] font-bold">
                                            Terlambat {{ (int) max(1, $dueDate->diffInDays(now())) }} Hari
                                        </span>
                                    @else
                                        <span class="inline-block px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[11px] font-bold">
                                            Jatuh Tempo {{ (int) now()->diffInDays($dueDate) }} Hari Lagi
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Baris Tengah: Rincian Penggunaan (Dipakai Untuk Apa Saja & Berapa) --}}
                            <div class="space-y-2">
                                <div class="text-[11px] font-bold uppercase tracking-wider text-gray-500">
                                    Rincian Penggunaan Tempo (Item &amp; Kuantitas)
                                </div>

                                @if ($order->items->isNotEmpty())
                                    <div class="bg-white rounded-xl border border-gray-200 divide-y divide-gray-100 text-xs">
                                        @foreach ($order->items as $item)
                                            <div class="py-2.5 px-3.5 flex items-center justify-between gap-3">
                                                <div>
                                                    <span class="font-bold text-gray-900">{{ $item->product?->name ?? 'Item Layanan' }}</span>
                                                    <span class="text-gray-500 ml-1">&times; {{ $item->qty }} {{ $item->product?->uom ?? 'pcs' }}</span>
                                                    <span class="text-[11px] text-gray-400 ml-1">(@ Rp {{ number_format($item->price, 0, ',', '.') }})</span>
                                                </div>
                                                <span class="font-bold text-gray-900 shrink-0">
                                                    Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="bg-white rounded-xl border border-gray-200 py-2.5 px-3.5 text-xs flex items-center justify-between">
                                        <span class="font-semibold text-gray-800">{{ $order->note ?: 'Pesanan '.$order->unit->label().' #'.$order->code }}</span>
                                        <span class="font-bold text-gray-900">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                                    </div>
                                @endif

                                @if ($order->note)
                                    <div class="text-xs text-gray-600">
                                        <span class="font-semibold text-gray-700">Keterangan Pesanan:</span> {{ $order->note }}
                                    </div>
                                @endif
                            </div>

                            {{-- Baris Bawah: Tombol Lunasi dengan Midtrans & Cetak Invoice --}}
                            <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
                                <div class="text-[11px] text-gray-500">
                                    Penerima: <b>{{ $order->ship_name }}</b> ({{ $order->ship_addr }})
                                </div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <a href="{{ route('payment.invoice', $order->code) }}" class="px-3.5 py-2 rounded-xl bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 font-bold text-xs transition">
                                        📄 Lihat / Cetak Invoice
                                    </a>
                                    <a href="{{ route('payment.checkout', $order->code) }}" class="px-4 py-2 rounded-xl bg-[#0D5A47] hover:bg-[#094234] text-white font-bold text-xs shadow-xs transition flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                        <span>Lunasi via Midtrans</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>

    {{-- 3. RIWAYAT PENGGUNAAN TEMPO YANG SUDAH LUNAS --}}
    <div class="card space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-2 pb-3 border-b border-gray-100">
            <div>
                <h2 class="text-base font-extrabold text-gray-900">Riwayat Penggunaan Tempo (Sudah Lunas)</h2>
                <p class="text-xs text-gray-500 mt-0.5">Arsip tagihan Tempo B2B yang telah berhasil dilunasi.</p>
            </div>
            <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-bold">
                {{ $settledPayments->count() }} Riwayat Lunas
            </span>
        </div>

        @if ($settledPayments->isEmpty())
            <div class="py-6 text-center text-xs text-gray-400">
                Belum ada riwayat tagihan Tempo yang dilunasi.
            </div>
        @else
            <div class="space-y-3">
                @foreach ($settledPayments as $payment)
                    @php
                        $order = $payment->order;
                        $gatewaySettlement = $order?->payments
                            ->where('id', '!=', $payment->id)
                            ->where('status', \App\Enums\PaymentStatus::Success)
                            ->last();
                    @endphp
                    @if ($order)
                        <div class="p-4 rounded-2xl border border-gray-200 bg-gray-50/60 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                            <div class="space-y-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 text-[10px] font-extrabold">
                                        LUNAS
                                    </span>
                                    <span class="font-bold text-gray-900">{{ $order->unit->label() }}</span>
                                    <span class="font-mono font-bold text-gray-700">{{ $payment->ref }}</span>
                                    <span class="text-gray-400">(#{{ $order->code }})</span>
                                </div>

                                @if ($order->items->isNotEmpty())
                                    <div class="text-gray-700 font-medium">
                                        @foreach ($order->items as $item)
                                            <span>{{ $item->product?->name ?? 'Item' }} &times; {{ $item->qty }}{{ ! $loop->last ? ', ' : '' }}</span>
                                        @endforeach
                                    </div>
                                @elseif ($order->note)
                                    <div class="text-gray-700 font-medium">{{ $order->note }}</div>
                                @endif

                                <div class="text-[11px] text-gray-500">
                                    Dipakai: {{ $payment->created_at?->format('d M Y') }}
                                    &bull; Dilunasi: <b class="text-emerald-700">{{ $payment->paid_at?->format('d M Y, H:i') ?? '-' }} WIB</b>
                                    @if ($gatewaySettlement)
                                        &bull; Metode Pelunasan: <b class="uppercase text-gray-700">{{ str_replace('_', ' ', $gatewaySettlement->method) }} ({{ $gatewaySettlement->ref }})</b>
                                    @endif
                                </div>
                            </div>

                            <div class="flex sm:flex-col items-center sm:items-end justify-between gap-2 shrink-0">
                                <span class="text-sm font-black text-gray-900">
                                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                </span>
                                <a href="{{ route('payment.invoice', $order->code) }}" class="px-3 py-1.5 rounded-xl bg-white hover:bg-gray-100 text-[#0D5A47] border border-gray-200 font-bold text-[11px] transition">
                                    📄 Kwitansi / Invoice
                                </a>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
