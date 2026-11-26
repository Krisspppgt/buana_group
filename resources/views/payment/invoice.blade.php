@extends('layouts.app')

@section('title', 'Invoice #' . $order->code . ' - PT Buana Sejahtera Semarang')

@section('content')
@php
    $isPaid = ($latestPayment && $latestPayment->status === \App\Enums\PaymentStatus::Success)
        || in_array($order->status, [\App\Enums\OrderStatus::Paid, \App\Enums\OrderStatus::Done], true);
    $isTempoPending = $latestPayment
        && $latestPayment->method === 'tempo_b2b'
        && $latestPayment->status === \App\Enums\PaymentStatus::Pending;
@endphp

<style>
    @media print {
        header, nav, footer, .no-print {
            display: none !important;
        }
        body {
            background: #ffffff !important;
        }
        .invoice-sheet {
            box-shadow: none !important;
            border: none !important;
            padding: 0 !important;
        }
    }
</style>

<div class="max-w-3xl mx-auto py-4 space-y-4">
    {{-- Action Bar --}}
    <div class="no-print flex flex-wrap items-center justify-between gap-3">
        <a href="{{ route('payment.checkout', $order->code) }}" class="text-sm font-bold text-[#0D5A47] hover:underline">
            &larr; Kembali ke Detail Pembayaran
        </a>
        <div class="flex flex-wrap items-center gap-2">
            @if ($isTempoPending)
                <a href="{{ route('payment.checkout', $order->code) }}" class="px-4 py-2.5 bg-amber-400 hover:bg-amber-300 text-amber-950 text-xs font-bold rounded-xl shadow-sm transition">
                    💳 Lunasi Tagihan Tempo via Midtrans
                </a>
            @endif
            <button type="button" onclick="window.print()" class="px-4 py-2.5 bg-[#0D5A47] hover:bg-[#084032] text-white text-xs font-bold rounded-xl shadow-sm cursor-pointer">
                🖨️ Cetak / Simpan PDF
            </button>
        </div>
    </div>

    {{-- Invoice Sheet --}}
    <div class="invoice-sheet bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8 space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 border-b border-gray-200 pb-6">
            <div>
                <div class="text-xs font-bold uppercase tracking-wider text-[#0D5A47]">PT Buana Sejahtera Semarang</div>
                <h1 class="text-2xl font-black text-gray-900 mt-1">INVOICE</h1>
                <p class="text-xs text-gray-500 mt-1">
                    Unit Usaha: <b class="text-gray-800">{{ $order->unit->label() }}</b>
                </p>
            </div>
            <div class="sm:text-right space-y-1">
                <div class="text-sm font-extrabold text-gray-900">INV-{{ $order->code }}</div>
                <div class="text-xs text-gray-500">Tanggal Order: {{ $order->created_at?->format('d M Y, H:i') }} WIB</div>
                @if ($isPaid)
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-extrabold">
                        LUNAS
                    </span>
                @elseif ($isTempoPending)
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-extrabold">
                        TEMPO B2B (MENUNGGU PELUNASAN)
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-extrabold">
                        {{ strtoupper($order->status->label()) }}
                    </span>
                @endif
            </div>
        </div>

        {{-- Customer & Payment Details --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
            <div class="space-y-1">
                <div class="font-bold uppercase tracking-wider text-gray-400">Ditagihkan Kepada</div>
                <div class="text-sm font-bold text-gray-900">{{ $order->ship_name ?: ($order->user?->name ?? 'Pelanggan Buana') }}</div>
                @if ($order->user?->email)
                    <div class="text-gray-600">{{ $order->user->email }}</div>
                @endif
                @if ($order->ship_phone || $order->user?->phone)
                    <div class="text-gray-600">{{ $order->ship_phone ?: $order->user?->phone }}</div>
                @endif
                @if ($order->ship_addr)
                    <div class="text-gray-600">{{ $order->ship_addr }}</div>
                @endif
            </div>

            <div class="space-y-1 sm:text-right">
                <div class="font-bold uppercase tracking-wider text-gray-400">Informasi Pembayaran</div>
                <div class="text-gray-700">Kode Pesanan: <b class="text-gray-900">#{{ $order->code }}</b></div>
                @if ($latestPayment)
                    <div class="text-gray-700">Ref Transaksi: <b class="text-gray-900">{{ $latestPayment->ref }}</b></div>
                    <div class="text-gray-700">Metode: <b class="uppercase text-gray-900">{{ str_replace('_', ' ', $latestPayment->method) }}</b></div>
                    @if ($latestPayment->paid_at)
                        <div class="text-gray-700">Waktu Bayar: <b class="text-gray-900">{{ $latestPayment->paid_at->format('d M Y, H:i') }} WIB</b></div>
                    @endif
                @else
                    <div class="text-gray-500">Status Pesanan: {{ $order->status->label() }}</div>
                @endif
            </div>
        </div>

        {{-- Items Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-y border-gray-200 bg-gray-50 text-gray-600 uppercase tracking-wider">
                        <th class="py-3 px-3 font-bold">Item / Layanan</th>
                        <th class="py-3 px-3 font-bold text-center">Qty</th>
                        <th class="py-3 px-3 font-bold text-right">Harga Satuan</th>
                        <th class="py-3 px-3 font-bold text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($order->items as $item)
                        <tr>
                            <td class="py-3 px-3 font-semibold text-gray-900">
                                {{ $item->product?->name ?? 'Produk / Layanan' }}
                            </td>
                            <td class="py-3 px-3 text-center text-gray-700">{{ $item->qty }}</td>
                            <td class="py-3 px-3 text-right text-gray-700">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="py-3 px-3 text-right font-bold text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="py-3 px-3 font-semibold text-gray-900">Pesanan {{ $order->unit->label() }} (#{{ $order->code }})</td>
                            <td class="py-3 px-3 text-center text-gray-700">1</td>
                            <td class="py-3 px-3 text-right text-gray-700">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                            <td class="py-3 px-3 text-right font-bold text-gray-900">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Totals --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 border-t border-gray-200 pt-5">
            <div class="text-xs space-y-1.5">
                @if ($order->points_earned > 0)
                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 font-bold">
                        🪙 Reward: +{{ number_format($order->points_earned, 0, ',', '.') }} Buana Loyalty Points
                    </div>
                @elseif (! $isPaid)
                    <div class="text-gray-500">
                        Estimasi reward: +{{ number_format(max(0, (int) floor($order->total * 0.01)), 0, ',', '.') }} Buana Points setelah lunas.
                    </div>
                @endif
                @if ($order->note)
                    <div class="text-gray-500">Catatan: {{ $order->note }}</div>
                @endif
            </div>

            <div class="w-full sm:w-64 space-y-1.5 text-xs">
                <div class="flex justify-between text-gray-600">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Ongkos Kirim</span>
                    <span>Rp {{ number_format($order->ship_fee, 0, ',', '.') }}</span>
                </div>
                @if ($order->discount_amount > 0)
                    <div class="flex justify-between text-emerald-700 font-semibold">
                        <span>Potongan Voucher ({{ $order->discount_code }})</span>
                        <span>-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                    </div>
                @endif
                @if ($order->points_redeemed > 0)
                    <div class="flex justify-between text-emerald-700 font-semibold">
                        <span>Potongan Buana Points</span>
                        <span>-Rp {{ number_format($order->points_redeemed, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="flex justify-between items-center border-t border-gray-200 pt-2 text-sm font-black text-gray-900">
                    <span>Total Tagihan</span>
                    <span class="text-[#0D5A47]">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="border-t border-dashed border-gray-200 pt-4 text-center text-[11px] text-gray-400">
            Dokumen ini diterbitkan secara elektronik oleh sistem PT Buana Sejahtera Semarang dan sah tanpa tanda tangan basah.
        </div>
    </div>
</div>
@endsection
