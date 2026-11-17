@extends('layouts.app')
@section('title', 'Riwayat Pesanan Air Mineral - Buana Group')

@section('content')
<div style="margin-bottom: 16px;">
    <a href="{{ route('water.index') }}" style="color:var(--muted); text-decoration:none; font-size:0.88rem; display:inline-flex; align-items:center; gap:4px;">
        &larr; Kembali ke Katalog
    </a>
    <div style="display:flex; justify-content:space-between; align-items:center; margin-top:6px;">
        <h1 style="margin:0; font-size:1.25rem;">📋 Riwayat Pesanan Air Mineral</h1>
        <a href="{{ route('water.index') }}" class="btn" style="margin:0; padding:6px 12px; font-size:0.8rem; width:auto;">
            + Pesan Baru
        </a>
    </div>
</div>

@if ($orders->isEmpty())
    <div class="card" style="text-align: center; padding: 40px 20px;">
        <div style="font-size: 2.5rem; margin-bottom: 8px;">💧</div>
        <div style="font-weight: 700; font-size: 1rem; color: #0f172a;">Belum Ada Riwayat Pesanan</div>
        <p style="color: var(--muted); font-size: 0.88rem; margin: 4px 0 16px;">
            Kamu belum pernah memesan Air Mineral Unwahas. Yuk pesan sekarang!
        </p>
        <a href="{{ route('water.index') }}" class="btn" style="max-width: 200px; margin: 0 auto; text-decoration: none;">
            Mulai Pesan
        </a>
    </div>
@else
    <div style="display: flex; flex-direction: column; gap: 12px;">
        @foreach ($orders as $order)
            <div class="card" style="padding: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                    <div>
                        <div style="font-weight: 800; font-size: 0.95rem; color: #0f172a;">
                            {{ $order->code }}
                        </div>
                        <div style="font-size: 0.75rem; color: var(--muted);">
                            {{ $order->created_at->format('d M Y, H:i') }} WIB
                        </div>
                    </div>
                    <span class="badge" style="background: #ccfbf1; color: #115e59; font-size: 0.75rem;">
                        {{ $order->status->label() }}
                    </span>
                </div>

                <div style="font-size: 0.85rem; color: #334155; margin-bottom: 8px; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px;">
                    @foreach ($order->items as $item)
                        <div>• {{ $item->product?->name }} ({{ $item->qty }} {{ $item->product?->uom }})</div>
                    @endforeach
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 0.72rem; color: var(--muted);">Total Tagihan</div>
                        <div style="font-weight: 800; font-size: 1rem; color: #0f766e;">
                            Rp {{ number_format($order->total, 0, ',', '.') }}
                        </div>
                    </div>
                    <a href="{{ route('water.show', $order->code) }}" class="btn" style="margin: 0; padding: 7px 14px; font-size: 0.82rem; width: auto; text-decoration: none; background: #0f766e;">
                        🚚 Lacak Pengiriman
                    </a>
                </div>
            </div>
        @endforeach

        <div style="margin-top: 10px;">
            {{ $orders->links() }}
        </div>
    </div>
@endif
@endsection
