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
            <h1 class="text-xl sm:text-2xl font-extrabold text-gray-900">💳 Checkout & Payment Gateway Terpadu</h1>
        </div>

        <a href="{{ route('loyalty.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs font-extrabold shadow-2xs hover:bg-amber-100 transition">
            <span>🪙</span>
            <span>Saldo Poin: {{ number_format($user->loyalty_points, 0, ',', '.') }} Poin</span>
        </a>
    </div>

    @if (session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 text-sm font-semibold">
            <span class="text-xl">✅</span>
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
            <span class="text-xl">ℹ️</span>
            <div>{{ session('info') }}</div>
        </div>
    @endif

    @if (! $midtransConfigured)
        <div class="p-4 bg-amber-50 border border-amber-300 text-amber-900 rounded-2xl text-xs space-y-1">
            <div class="font-extrabold flex items-center gap-1.5">
                <span>⚙️ Midtrans Sandbox Belum Dikonfigurasi</span>
            </div>
            <p class="leading-relaxed">
                Untuk mencoba popup pembayaran Midtrans (QRIS, Virtual Account, GoPay), daftar gratis di <b>dashboard.sandbox.midtrans.com</b> lalu masukkan <code>MIDTRANS_SERVER_KEY</code> dan <code>MIDTRANS_CLIENT_KEY</code> ke file <code>.env</code>. Metode <b>Paylater Civitas / Tempo B2B</b> tetap dapat digunakan langsung.
            </p>
        </div>
    @endif

    @if (session('snap_token'))
        <div class="p-5 bg-gradient-to-r from-indigo-900 to-blue-900 text-white rounded-3xl shadow-lg flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="space-y-1">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-white/15 text-blue-100 text-[11px] font-bold">
                    ⏳ Menunggu Pembayaran Midtrans {{ $midtransIsProduction ? 'Production' : 'Sandbox' }}
                </span>
                <h2 class="text-base font-extrabold">Selesaikan Pembayaran Anda</h2>
                <p class="text-xs text-blue-100">Jika jendela popup Midtrans belum terbuka, klik tombol di samping.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <button type="button" onclick="openMidtransSnap()" class="px-4 py-2.5 bg-amber-400 hover:bg-amber-300 text-amber-950 font-extrabold text-xs rounded-xl shadow-sm cursor-pointer">
                    🚀 Buka Popup Midtrans
                </button>
                <a href="{{ route('payment.checkout', $order->code) }}" class="px-4 py-2.5 bg-white/15 hover:bg-white/25 text-white font-bold text-xs rounded-xl">
                    🔄 Cek Status
                </a>
            </div>
        </div>
    @elseif ($latestPayment && $latestPayment->status === \App\Enums\PaymentStatus::Pending)
        <div class="p-4 bg-amber-50 border border-amber-200 text-amber-900 rounded-2xl flex flex-wrap items-center justify-between gap-3 text-xs">
            <div>
                <span class="font-extrabold block">⏳ Transaksi Menunggu Pembayaran ({{ $latestPayment->ref }})</span>
                <span>Silakan selesaikan pembayaran di aplikasi bank/e-wallet atau tekan tombol Cek Status setelah membayar.</span>
            </div>
            <a href="{{ route('payment.checkout', $order->code) }}" class="px-3.5 py-2 bg-amber-600 text-white font-bold rounded-xl hover:bg-amber-700">
                🔄 Cek Status Pembayaran
            </a>
        </div>
    @endif

    {{-- Status Pembayaran Jika Sudah Dibayar --}}
    @if ($latestPayment && $latestPayment->status === \App\Enums\PaymentStatus::Success)
        <div class="bg-gradient-to-r from-emerald-900 to-teal-800 text-white rounded-3xl p-6 shadow-lg flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="space-y-1">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-400/20 border border-emerald-300/30 text-emerald-200 text-xs font-extrabold">
                    ✅ LUNAS TERVERIFIKASI GATEWAY
                </span>
                <h2 class="text-lg font-extrabold">Ref Transaksi: {{ $latestPayment->ref }}</h2>
                <p class="text-xs text-emerald-100">
                    Metode: <b class="uppercase">{{ $latestPayment->method }}</b> &bull; Waktu Bayar: {{ $latestPayment->paid_at?->format('d M Y, H:i') }} WIB
                </p>
                @if ($order->points_earned > 0)
                    <p class="text-xs text-amber-300 font-bold pt-1">
                        🪙 +{{ number_format($order->points_earned, 0, ',', '.') }} Buana Loyalty Points telah ditambahkan ke akun Anda!
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
                    <p class="text-xs text-gray-500 mt-0.5">Mendukung QRIS, Virtual Account Bank, E-Wallet, serta Paylater khusus Mahasiswa/Pegawai UNWAHAS.</p>
                </div>

                {{-- A. QRIS Terpadu --}}
                <div class="space-y-2">
                    <div class="text-[11px] font-extrabold uppercase tracking-wider text-gray-400">QRIS Instan Nasional</div>
                    <label class="flex items-center justify-between p-3.5 rounded-2xl border-2 border-emerald-600 bg-emerald-50/40 cursor-pointer transition hover:bg-emerald-50" id="label_qris">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="method" value="qris" checked onchange="selectGatewayMethod('qris')" class="w-4 h-4 text-[#0D5A47]">
                            <div>
                                <div class="text-xs font-extrabold text-gray-900 flex items-center gap-2">
                                    <span>📱 QRIS Terpadu Buana Group</span>
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
                                    <div class="text-xs font-bold text-gray-900">🏦 Bank Syariah Indonesia (BSI VA)</div>
                                    <div class="text-[11px] text-gray-500">Verifikasi otomatis 24 jam tanpa unggah bukti transfer</div>
                                </div>
                            </div>
                            <span class="text-[11px] font-extrabold text-teal-700">BSI</span>
                        </label>

                        <label class="flex items-center justify-between p-3.5 rounded-2xl border border-gray-200 hover:border-emerald-500 cursor-pointer transition">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="method" value="va_jateng" onchange="selectGatewayMethod('va_jateng')" class="w-4 h-4 text-[#0D5A47]">
                                <div>
                                    <div class="text-xs font-bold text-gray-900">🏦 Bank Jateng / Syariah VA</div>
                                    <div class="text-[11px] text-gray-500">Mitra perbankan resmi lingkungan kampus Semarang</div>
                                </div>
                            </div>
                            <span class="text-[11px] font-extrabold text-blue-700">JATENG</span>
                        </label>

                        <label class="flex items-center justify-between p-3.5 rounded-2xl border border-gray-200 hover:border-emerald-500 cursor-pointer transition">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="method" value="va_mandiri" onchange="selectGatewayMethod('va_mandiri')" class="w-4 h-4 text-[#0D5A47]">
                                <div>
                                    <div class="text-xs font-bold text-gray-900">🏦 Bank Mandiri Virtual Account</div>
                                    <div class="text-[11px] text-gray-500">Livin' by Mandiri & ATM Bersama</div>
                                </div>
                            </div>
                            <span class="text-[11px] font-extrabold text-amber-700">MANDIRI</span>
                        </label>
                    </div>
                </div>

                {{-- C. E-Wallet --}}
                <div class="space-y-2">
                    <div class="text-[11px] font-extrabold uppercase tracking-wider text-gray-400">E-Wallet / Dompet Digital</div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <label class="flex items-center gap-3 p-3.5 rounded-2xl border border-gray-200 hover:border-emerald-500 cursor-pointer transition">
                            <input type="radio" name="method" value="gopay" onchange="selectGatewayMethod('gopay')" class="w-4 h-4 text-[#0D5A47]">
                            <div>
                                <div class="text-xs font-bold text-gray-900">🟢 GoPay</div>
                                <div class="text-[10px] text-gray-500">Auto-debit aplikasi Gojek</div>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-3.5 rounded-2xl border border-gray-200 hover:border-emerald-500 cursor-pointer transition">
                            <input type="radio" name="method" value="dana" onchange="selectGatewayMethod('dana')" class="w-4 h-4 text-[#0D5A47]">
                            <div>
                                <div class="text-xs font-bold text-gray-900">🔵 DANA / ShopeePay</div>
                                <div class="text-[10px] text-gray-500">Saldo dompet digital</div>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- D. Paylater Khusus Mahasiswa/Pegawai & Tempo B2B --}}
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-emerald-700">🎓 Fasilitas Khusus Civitas UNWAHAS</span>
                        @if ($user->isCivitas())
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800">NIM/NIP Terverifikasi</span>
                        @else
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">Khusus Civitas</span>
                        @endif
                    </div>

                    <label class="flex items-center justify-between p-3.5 rounded-2xl border {{ $user->isCivitas() ? 'border-emerald-300 bg-emerald-50/30 cursor-pointer hover:border-emerald-600' : 'border-gray-200 bg-gray-50 opacity-60 cursor-not-allowed' }} transition">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="method" value="paylater" onchange="selectGatewayMethod('paylater')" {{ $user->isCivitas() ? '' : 'disabled' }} class="w-4 h-4 text-[#0D5A47]">
                            <div>
                                <div class="text-xs font-extrabold text-gray-900">🎓 Paylater Civitas Mahasiswa / Pegawai</div>
                                <div class="text-[11px] text-gray-500">Tagihan akhir bulan terintegrasi NIM/NIP ({{ $user->campus_id ?? 'Khusus Civitas' }})</div>
                            </div>
                        </div>
                        <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-lg bg-emerald-100 text-[#0D5A47]">0% Bunga</span>
                    </label>

                    <label class="flex items-center justify-between p-3.5 rounded-2xl border {{ $user->isCivitas() ? 'border-emerald-300 bg-emerald-50/30 cursor-pointer hover:border-emerald-600' : 'border-gray-200 bg-gray-50 opacity-60 cursor-not-allowed' }} transition">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="method" value="tempo_b2b" onchange="selectGatewayMethod('tempo_b2b')" {{ $user->isCivitas() ? '' : 'disabled' }} class="w-4 h-4 text-[#0D5A47]">
                            <div>
                                <div class="text-xs font-extrabold text-gray-900">🏢 Invoice Tempo Fakultas / Unit (TOP 30 Hari)</div>
                                <div class="text-[11px] text-gray-500">Pembayaran kolektif SPK fakultas / biro kampus</div>
                            </div>
                        </div>
                        <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-lg bg-blue-100 text-blue-800">TOP 30H</span>
                    </label>
                </div>
            </div>

            {{-- INFO TERMINAL MIDTRANS SNAP --}}
            <div class="card bg-slate-900 text-white border-slate-800" id="gateway_preview_box">
                <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-400 block">Terminal Payment Gateway Midtrans</span>
                        <h3 class="text-sm font-extrabold" id="preview_title">QRIS Terpadu PT Buana Sejahtera</h3>
                    </div>
                    <span class="px-2.5 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-[10px] font-bold">
                        {{ $midtransIsProduction ? 'PRODUCTION' : 'SANDBOX MODE' }}
                    </span>
                </div>

                <div class="py-4 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 border border-emerald-400/30 flex items-center justify-center text-2xl shrink-0">
                        🔒
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
                    <div class="flex items-center gap-2">
                        <span class="text-xl">🪙</span>
                        <div>
                            <h3 class="text-xs font-extrabold text-amber-950">Buana Loyalty Points</h3>
                            <p class="text-[11px] text-amber-800">Saldo Anda: <b>{{ number_format($user->loyalty_points, 0, ',', '.') }} Poin</b> (Senilai Rp {{ number_format($user->loyalty_points, 0, ',', '.') }})</p>
                        </div>
                    </div>
                    <span class="px-2 py-0.5 rounded-full bg-amber-200/80 text-amber-950 text-[10px] font-extrabold">Cross-Selling</span>
                </div>

                @if ($order->points_redeemed > 0)
                    <div class="p-2.5 rounded-xl bg-white/90 border border-amber-200 text-xs text-emerald-800 font-bold">
                        ✅ Anda telah menukarkan {{ number_format($order->points_redeemed, 0, ',', '.') }} Poin (-Rp {{ number_format($order->points_redeemed, 0, ',', '.') }}) pada pesanan ini.
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
                    <span>🪙 Reward Poin Transaksi Ini:</span>
                    <span class="font-extrabold text-[#0D5A47]" id="earned_points_display">+{{ number_format($potentialEarnedPoints, 0, ',', '.') }} Poin</span>
                </div>

                <button type="submit" class="btn" style="padding: 14px; font-size: 0.95rem;">
                    ✅ Konfirmasi & Bayar Sekarang
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
            paylater: ['Paylater Civitas Akademika UNWAHAS', 'Tagihan dicatat ke akun NIM/NIP Civitas tanpa bunga (0%) dengan jatuh tempo akhir bulan.'],
            tempo_b2b: ['Invoice Tempo Institusi UNWAHAS (TOP 30 Hari)', 'Tagihan masuk ke rekapitulasi SPK Fakultas / Unit Kerja dengan tempo maksimal 30 hari.'],
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
