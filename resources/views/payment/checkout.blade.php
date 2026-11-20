@extends('layouts.app')
@section('title', 'Payment Gateway Terpadu - ' . $order->code)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Header & Breadcrumb --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                <a href="{{ route('home') }}" class="hover:text-[#0D5A47]">Beranda</a>
                <span>&rsaquo;</span>
                <span>{{ $order->unit->label() }}</span>
                <span>&rsaquo;</span>
                <span class="font-bold text-gray-800">Payment Gateway Terpadu</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-gray-900">Checkout & Payment Gateway Terpadu</h1>
        </div>

        <a href="{{ route('loyalty.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs font-bold shadow-2xs hover:bg-amber-100 transition">
            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Saldo Poin: {{ number_format($user->loyalty_points, 0, ',', '.') }} Poin</span>
        </a>
    </div>

    @if (session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 text-sm font-semibold">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if ($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-xs font-semibold space-y-1">
            @foreach ($errors->all() as $error)
                <div>• {{ $error }}</div>
            @endforeach
        </div>
    @endif

    @if (session('info'))
        <div class="p-4 bg-blue-50 border border-blue-200 text-blue-800 rounded-2xl flex items-center gap-3 text-sm font-semibold">
            <svg class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>{{ session('info') }}</div>
        </div>
    @endif

    @if (! $midtransConfigured)
        <div class="p-4 bg-amber-50 border border-amber-300 text-amber-900 rounded-2xl text-xs space-y-1">
            <div class="font-bold">Midtrans Sandbox Belum Dikonfigurasi</div>
            <p class="leading-relaxed">
                Untuk mencoba popup pembayaran Midtrans (QRIS, Virtual Account, GoPay), daftar gratis di <b>dashboard.sandbox.midtrans.com</b> lalu masukkan <code>MIDTRANS_SERVER_KEY</code> dan <code>MIDTRANS_CLIENT_KEY</code> ke file <code>.env</code>. Metode <b>Tempo Institusi / B2B</b> tersedia untuk akun yang telah disetujui admin.
            </p>
        </div>
    @endif

    @if (session('snap_token'))
        <div class="p-5 bg-gradient-to-r from-indigo-900 to-blue-900 text-white rounded-2xl shadow-lg flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="space-y-1">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-white/15 text-blue-100 text-[11px] font-bold">
                    Menunggu Pembayaran Midtrans {{ $midtransIsProduction ? 'Production' : 'Sandbox' }}
                </span>
                <h2 class="text-base font-extrabold">Selesaikan Pembayaran Anda</h2>
                <p class="text-xs text-blue-100">Jika jendela popup Midtrans belum terbuka, klik tombol di samping.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <button type="button" onclick="openMidtransSnap()" class="px-4 py-2.5 bg-amber-400 hover:bg-amber-300 text-amber-950 font-bold text-xs rounded-xl shadow-sm cursor-pointer">
                    Buka Popup Midtrans
                </button>
                <a href="{{ route('payment.checkout', $order->code) }}" class="px-4 py-2.5 bg-white/15 hover:bg-white/25 text-white font-bold text-xs rounded-xl">
                    Cek Status
                </a>
            </div>
        </div>
    @elseif ($latestPayment && $latestPayment->status === \App\Enums\PaymentStatus::Pending && $latestPayment->method === 'tempo_b2b')
        <div class="p-4 bg-blue-50 border border-blue-200 text-blue-900 rounded-2xl flex flex-wrap items-center justify-between gap-3 text-xs">
            <div>
                <span class="font-bold block">Invoice Tempo Institusi / B2B Diterbitkan ({{ $latestPayment->ref }})</span>
                <span>Pesanan diproses dengan skema TOP maksimal 30 hari. Poin loyalitas akan masuk setelah tagihan dilunasi.</span>
            </div>
            <span class="px-3 py-1.5 bg-blue-100 text-blue-800 font-bold rounded-xl">Menunggu Pelunasan</span>
        </div>
    @elseif ($latestPayment && $latestPayment->status === \App\Enums\PaymentStatus::Pending)
        <div class="p-4 bg-amber-50 border border-amber-200 text-amber-900 rounded-2xl flex flex-wrap items-center justify-between gap-3 text-xs">
            <div>
                <span class="font-bold block">Transaksi Menunggu Pembayaran ({{ $latestPayment->ref }})</span>
                <span>Silakan selesaikan pembayaran di aplikasi bank/e-wallet atau tekan tombol Cek Status setelah membayar.</span>
            </div>
            <a href="{{ route('payment.checkout', $order->code) }}" class="px-3.5 py-2 bg-amber-600 text-white font-bold rounded-xl hover:bg-amber-700">
                Cek Status Pembayaran
            </a>
        </div>
    @endif

    {{-- Status Pembayaran Jika Sudah Dibayar --}}
    @if ($latestPayment && $latestPayment->status === \App\Enums\PaymentStatus::Success)
        <div class="bg-gradient-to-r from-emerald-900 to-teal-800 text-white rounded-2xl p-6 shadow-lg flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="space-y-1">
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-400/20 border border-emerald-300/30 text-emerald-200 text-xs font-bold">
                    LUNAS TERVERIFIKASI GATEWAY
                </span>
                <h2 class="text-lg font-extrabold">Ref Transaksi: {{ $latestPayment->ref }}</h2>
                <p class="text-xs text-emerald-100">
                    Metode: <b class="uppercase">{{ $latestPayment->method }}</b> &bull; Waktu Bayar: {{ $latestPayment->paid_at?->format('d M Y, H:i') }} WIB
                </p>
                @if ($order->points_earned > 0)
                    <p class="text-xs text-amber-300 font-bold pt-1">
                        +{{ number_format($order->points_earned, 0, ',', '.') }} Buana Loyalty Points telah ditambahkan ke akun Anda.
                    </p>
                @endif
            </div>
            <div class="text-left sm:text-right">
                <span class="text-xs text-emerald-200 block">Total Dibayar</span>
                <span class="text-2xl font-black">Rp {{ number_format($latestPayment->amount, 0, ',', '.') }}</span>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('payment.process', $order->code) }}" class="unit-grid-2col">
        @csrf

        {{-- KOLOM KIRI: PILIHAN METODE PAYMENT GATEWAY TERPADU --}}
        <div class="space-y-4">
            <div class="card space-y-4">
                <div>
                    <h2 class="text-base font-extrabold text-gray-900">1. Pilih Metode Pembayaran Terpadu</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Mendukung QRIS, Virtual Account Bank, E-Wallet, serta Tempo Institusi / B2B bagi akun terverifikasi.</p>
                </div>

                {{-- A. QRIS Terpadu --}}
                <div class="space-y-2">
                    <div class="text-[11px] font-extrabold uppercase tracking-wider text-gray-400">QRIS Instan Nasional</div>
                    <label class="flex items-center justify-between p-3.5 rounded-2xl border-2 border-emerald-600 bg-emerald-50/40 cursor-pointer transition hover:bg-emerald-50" id="label_qris">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="method" value="qris" checked onchange="selectGatewayMethod('qris')" class="w-4 h-4 text-[#0D5A47]">
                            <div>
                                <div class="text-xs font-bold text-gray-900 flex items-center gap-2">
                                    <span>QRIS Terpadu Buana Group</span>
                                    <span class="px-2 py-0.5 text-[10px] bg-emerald-100 text-[#0D5A47] rounded-full font-bold">Rekomendasi</span>
                                </div>
                                <div class="text-[11px] text-gray-500">Scan via Mobile Banking, GoPay, OVO, DANA, ShopeePay, LinkAja</div>
                            </div>
                        </div>
                        <span class="text-xs font-black text-[#0D5A47]">QRIS</span>
                    </label>
                </div>

                {{-- B. Virtual Account Bank --}}
                <div class="space-y-2">
                    <div class="text-[11px] font-extrabold uppercase tracking-wider text-gray-400">Virtual Account Bank Mitra</div>
                    <div class="grid grid-cols-1 gap-2">
                        <label class="flex items-center justify-between p-3.5 rounded-2xl border border-gray-200 hover:border-emerald-500 cursor-pointer transition">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="method" value="va_bsi" onchange="selectGatewayMethod('va_bsi')" class="w-4 h-4 text-[#0D5A47]">
                                <div>
                                    <div class="text-xs font-bold text-gray-900">Bank Syariah Indonesia (BSI VA)</div>
                                    <div class="text-[11px] text-gray-500">Verifikasi otomatis 24 jam tanpa unggah bukti transfer</div>
                                </div>
                            </div>
                            <span class="text-[11px] font-bold text-teal-700">BSI</span>
                        </label>

                        <label class="flex items-center justify-between p-3.5 rounded-2xl border border-gray-200 hover:border-emerald-500 cursor-pointer transition">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="method" value="va_jateng" onchange="selectGatewayMethod('va_jateng')" class="w-4 h-4 text-[#0D5A47]">
                                <div>
                                    <div class="text-xs font-bold text-gray-900">Bank Jateng / Syariah VA</div>
                                    <div class="text-[11px] text-gray-500">Mitra perbankan resmi lingkungan kampus Semarang</div>
                                </div>
                            </div>
                            <span class="text-[11px] font-bold text-blue-700">JATENG</span>
                        </label>

                        <label class="flex items-center justify-between p-3.5 rounded-2xl border border-gray-200 hover:border-emerald-500 cursor-pointer transition">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="method" value="va_mandiri" onchange="selectGatewayMethod('va_mandiri')" class="w-4 h-4 text-[#0D5A47]">
                                <div>
                                    <div class="text-xs font-bold text-gray-900">Bank Mandiri Virtual Account</div>
                                    <div class="text-[11px] text-gray-500">Livin' by Mandiri & ATM Bersama</div>
                                </div>
                            </div>
                            <span class="text-[11px] font-bold text-amber-700">MANDIRI</span>
                        </label>
                    </div>
                </div>

                {{-- C. E-Wallet --}}
                <div class="space-y-2">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-gray-400">E-Wallet / Dompet Digital</div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <label class="flex items-center gap-3 p-3.5 rounded-2xl border border-gray-200 hover:border-emerald-500 cursor-pointer transition">
                            <input type="radio" name="method" value="gopay" onchange="selectGatewayMethod('gopay')" class="w-4 h-4 text-[#0D5A47]">
                            <div>
                                <div class="text-xs font-bold text-gray-900">GoPay</div>
                                <div class="text-[10px] text-gray-500">Auto-debit aplikasi Gojek</div>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-3.5 rounded-2xl border border-gray-200 hover:border-emerald-500 cursor-pointer transition">
                            <input type="radio" name="method" value="dana" onchange="selectGatewayMethod('dana')" class="w-4 h-4 text-[#0D5A47]">
                            <div>
                                <div class="text-xs font-bold text-gray-900">DANA / ShopeePay</div>
                                <div class="text-[10px] text-gray-500">Saldo dompet digital</div>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- D. Tempo Institusi / B2B (Disetujui Admin) & Paylater (Tahap Lanjutan) --}}
                @php
                    $tempoAllowed = $user->canUseTempo((int) $order->total);
                @endphp
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-700">Fasilitas Tempo Institusi &amp; B2B</span>
                        @if ($tempoAllowed)
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800">
                                Limit Tersisa: Rp {{ number_format($user->remainingTempoLimit(), 0, ',', '.') }}
                            </span>
                        @elseif ($user->isCivitas() || $user->isVerifiedMitra())
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-800">Perlu Persetujuan Tempo Admin</span>
                        @else
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">Khusus Akun Terverifikasi</span>
                        @endif
                    </div>

                    <label class="flex items-center justify-between p-3.5 rounded-2xl border {{ $tempoAllowed ? 'border-emerald-300 bg-emerald-50/30 cursor-pointer hover:border-emerald-600' : 'border-gray-200 bg-gray-50 opacity-60 cursor-not-allowed' }} transition">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="method" value="tempo_b2b" onchange="selectGatewayMethod('tempo_b2b')" {{ $tempoAllowed ? '' : 'disabled' }} class="w-4 h-4 text-[#0D5A47]">
                            <div>
                                <div class="text-xs font-bold text-gray-900">Invoice Tempo Institusi / B2B (TOP 30 Hari)</div>
                                <div class="text-[11px] text-gray-500">
                                    @if ($tempoAllowed)
                                        Pembayaran kolektif SPK fakultas / mitra B2B dengan batas waktu maksimal 30 hari.
                                    @else
                                        Status civitas/mitra tidak otomatis memberi kredit; butuh persetujuan &amp; limit admin.
                                    @endif
                                </div>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-lg bg-blue-100 text-blue-800">TOP 30H</span>
                    </label>

                    <label class="flex items-center justify-between p-3.5 rounded-2xl border border-gray-200 bg-gray-50 opacity-60 cursor-not-allowed transition">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="method" value="paylater" disabled class="w-4 h-4 text-gray-400">
                            <div>
                                <div class="text-xs font-bold text-gray-700">Paylater Mahasiswa / Pegawai</div>
                                <div class="text-[11px] text-gray-500">Ditunda dari fase pilot — tersedia pada tahap lanjutan melalui mitra berizin.</div>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-lg bg-gray-200 text-gray-600">Tahap Lanjutan</span>
                    </label>
                </div>
            </div>

            {{-- INFO TERMINAL MIDTRANS SNAP --}}
            <div class="card bg-slate-900 text-white border-slate-800" id="gateway_preview_box">
                <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-400 block">Terminal Payment Gateway Midtrans</span>
                        <h3 class="text-sm font-bold" id="preview_title">QRIS Terpadu PT Buana Sejahtera</h3>
                    </div>
                    <span class="px-2.5 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-[10px] font-bold">
                        {{ $midtransIsProduction ? 'PRODUCTION' : 'SANDBOX MODE' }}
                    </span>
                </div>

                <div class="py-4 flex items-center gap-4">
                    <div class="w-11 h-11 rounded-xl bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <div class="text-xs space-y-1 text-slate-300">
                        <div id="preview_instruction" class="font-semibold text-white">
                            QRIS Dinamis / Nomor Virtual Account resmi Midtrans akan muncul otomatis di jendela popup setelah Anda menekan tombol "Konfirmasi & Bayar Sekarang".
                        </div>
                        <div>Kode Pesanan: <span class="font-mono font-bold text-emerald-300">{{ $order->code }}</span></div>
                        <div>Merchant: <b>PT Buana Sejahtera Semarang (Yayasan Wahid Hasyim)</b></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: TUKAR LOYALTY POINTS & RINGKASAN TAGIHAN --}}
        <div class="unit-sticky-col space-y-4">
            {{-- FITUR BUANA LOYALTY POINTS (CROSS-SELLING) --}}
            <div class="card border-2 border-amber-300 bg-gradient-to-br from-amber-50/90 to-yellow-50/50 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-amber-200/70 text-amber-800 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-xs font-bold text-amber-950">Buana Loyalty Points</h3>
                            <p class="text-[11px] text-amber-800">Saldo Anda: <b>{{ number_format($user->loyalty_points, 0, ',', '.') }} Poin</b> (Senilai Rp {{ number_format($user->loyalty_points, 0, ',', '.') }})</p>
                        </div>
                    </div>
                    <span class="px-2 py-0.5 rounded-full bg-amber-200/80 text-amber-950 text-[10px] font-bold">Cross-Selling</span>
                </div>

                @if ($order->points_redeemed > 0)
                    <div class="p-2.5 rounded-xl bg-white/90 border border-amber-200 text-xs text-emerald-800 font-bold">
                        Anda telah menukarkan {{ number_format($order->points_redeemed, 0, ',', '.') }} Poin (-Rp {{ number_format($order->points_redeemed, 0, ',', '.') }}) pada pesanan ini.
                    </div>
                @elseif ($maxRedeemablePoints > 0)
                    <label class="flex items-center justify-between p-3 rounded-xl bg-white border border-amber-300 cursor-pointer hover:bg-amber-50/40 transition m-0">
                        <div class="flex items-center gap-2.5">
                            <input type="checkbox" name="use_points" id="use_points_toggle" value="1" onchange="recalculateWithPoints()" class="w-4 h-4 text-amber-600 rounded">
                            <div>
                                <span class="text-xs font-extrabold text-gray-900 block">Tukar {{ number_format($maxRedeemablePoints, 0, ',', '.') }} Poin Sekarang</span>
                                <span class="text-[10px] text-emerald-700 font-semibold">Potongan langsung -Rp {{ number_format($maxRedeemablePoints, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <span class="text-xs font-black text-amber-700">-Rp {{ number_format($maxRedeemablePoints, 0, ',', '.') }}</span>
                    </label>
                @else
                    <div class="text-[11px] text-amber-800/90 bg-white/70 p-2.5 rounded-xl border border-amber-200/60">
                        Selesaikan pembayaran pesanan ini untuk mendapatkan <b>+{{ number_format($potentialEarnedPoints, 0, ',', '.') }} Poin</b> yang bisa dipakai diskon di seluruh 5 unit usaha!
                    </div>
                @endif
            </div>

            {{-- RINGKASAN RINCIAN PESANAN --}}
            <div class="card space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 block">Ringkasan Tagihan</span>
                        <h3 class="text-sm font-extrabold text-gray-900">{{ $order->unit->label() }} &bull; {{ $order->code }}</h3>
                    </div>
                    <span class="badge" style="background:#e6f4ea; color:#0D5A47;">{{ $order->status->label() }}</span>
                </div>

                @if ($order->items->isNotEmpty())
                    <div class="space-y-2 text-xs border-b border-gray-100 pb-3">
                        @foreach ($order->items as $item)
                            <div class="flex justify-between text-gray-700">
                                <span>{{ $item->product?->name ?? 'Item Pesanan' }} &times; {{ $item->qty }}</span>
                                <span class="font-bold">Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="space-y-2 text-xs">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal Pesanan:</span>
                        <span class="font-bold text-gray-900">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Ongkos Kirim / Logistik:</span>
                        <span class="font-bold text-emerald-700">{{ $order->ship_fee > 0 ? 'Rp '.number_format($order->ship_fee, 0, ',', '.') : 'GRATIS' }}</span>
                    </div>
                    <div id="points_discount_row" class="flex justify-between text-amber-700 font-bold" style="display: {{ $order->points_redeemed > 0 ? 'flex' : 'none' }};">
                        <span>Potongan Buana Loyalty Points:</span>
                        <span id="points_discount_display">-Rp {{ number_format($order->points_redeemed > 0 ? $order->points_redeemed : $maxRedeemablePoints, 0, ',', '.') }}</span>
                    </div>
                    <div class="border-t border-dashed border-gray-200 pt-3 flex justify-between items-center">
                        <span class="text-sm font-extrabold text-gray-900">Total Bayar:</span>
                        <span id="final_total_display" class="text-2xl font-black text-[#0D5A47]">
                            Rp {{ number_format($order->total, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <div class="p-3 rounded-2xl bg-emerald-50 border border-emerald-200/70 flex items-center justify-between text-xs text-emerald-900">
                    <span>Reward Poin Transaksi Ini:</span>
                    <span class="font-bold text-[#0D5A47]" id="earned_points_display">+{{ number_format($potentialEarnedPoints, 0, ',', '.') }} Poin</span>
                </div>

                <button type="submit" class="btn" style="padding: 14px; font-size: 0.95rem;">
                    Konfirmasi & Bayar Sekarang
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    const baseTotal = {{ (int) $order->total }};
    const maxRedeem = {{ (int) $maxRedeemablePoints }};

    function recalculateWithPoints() {
        const toggle = document.getElementById('use_points_toggle');
        const discountRow = document.getElementById('points_discount_row');
        const finalDisplay = document.getElementById('final_total_display');
        const earnedDisplay = document.getElementById('earned_points_display');

        let newTotal = baseTotal;
        if (toggle && toggle.checked) {
            newTotal = Math.max(0, baseTotal - maxRedeem);
            if (discountRow) discountRow.style.display = 'flex';
        } else {
            if (discountRow) discountRow.style.display = 'none';
        }

        const newEarned = Math.floor(newTotal * 0.01);
        if (finalDisplay) finalDisplay.innerText = 'Rp ' + newTotal.toLocaleString('id-ID');
        if (earnedDisplay) earnedDisplay.innerText = '+' + newEarned.toLocaleString('id-ID') + ' Poin';
    }

    function selectGatewayMethod(method) {
        const title = document.getElementById('preview_title');
        const instruction = document.getElementById('preview_instruction');

        const map = {
            qris: ['QRIS Terpadu PT Buana Sejahtera', 'Pindai kode QRIS di samping atau tekan tombol "Konfirmasi & Bayar Sekarang" untuk pelunasan instan.'],
            va_bsi: ['Virtual Account Bank Syariah Indonesia (BSI)', 'Nomor VA BSI: 9008-1122-3344-5566 (a.n. PT Buana Sejahtera Semarang).'],
            va_jateng: ['Virtual Account Bank Jateng Syariah', 'Nomor VA Bank Jateng: 7021-8899-0011-2233 (a.n. PT Buana Sejahtera).'],
            va_mandiri: ['Mandiri Bill Payment Virtual Account', 'Kode Perusahaan 89022 • No. VA: 89022-081234567890.'],
            gopay: ['E-Wallet GoPay Direct Link', 'Notifikasi pembayaran otomatis terhubung ke aplikasi Gojek / GoPay Anda.'],
            dana: ['E-Wallet DANA / ShopeePay', 'Pembayaran instan menggunakan saldo dompet digital terhubung.'],
            tempo_b2b: ['Invoice Tempo Institusi / B2B (TOP 30 Hari)', 'Tagihan masuk ke rekapitulasi SPK Fakultas / Mitra B2B dengan batas waktu maksimal 30 hari.'],
        };

        if (map[method]) {
            title.innerText = map[method][0];
            instruction.innerText = map[method][1];
        }
    }
</script>

@if ($midtransConfigured && $midtransClientKey)
    <script src="{{ $midtransSnapJsUrl }}" data-client-key="{{ $midtransClientKey }}"></script>
    <script>
        function openMidtransSnap() {
            const token = @json(session('snap_token'));
            if (!token || typeof window.snap === 'undefined') {
                return;
            }
            window.snap.pay(token, {
                onSuccess: function () {
                    window.location.href = @json(route('payment.checkout', $order->code));
                },
                onPending: function () {
                    window.location.href = @json(route('payment.checkout', $order->code));
                },
                onError: function () {
                    alert('Pembayaran gagal atau dibatalkan.');
                },
                onClose: function () {
                    // User closed popup without finishing
                }
            });
        }

        @if (session('snap_token'))
            document.addEventListener('DOMContentLoaded', function () {
                openMidtransSnap();
            });
        @endif
    </script>
@endif
@endsection
