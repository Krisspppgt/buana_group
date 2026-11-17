@extends('layouts.app')
@section('title', 'Riwayat Katering - Buana Food Services')

@section('content')
<div style="margin-bottom: 16px;">
    <a href="{{ route('food.index') }}" style="color:var(--muted); text-decoration:none; font-size:0.88rem; display:inline-flex; align-items:center; gap:4px;">
        &larr; Kembali ke Katalog
    </a>
    <div style="display:flex; justify-content:space-between; align-items:center; margin-top:6px;">
        <h1 style="margin:0; font-size:1.25rem;">📋 Riwayat Katering & Acara</h1>
        <a href="{{ route('food.index') }}" class="btn" style="margin:0; padding:6px 12px; font-size:0.8rem; width:auto; background:#d97706;">
            + Pesan Baru
        </a>
    </div>
</div>

@if ($orders->isEmpty())
    <div class="card" style="text-align: center; padding: 40px 20px;">
        <div style="font-size: 2.5rem; margin-bottom: 8px;">🍱</div>
        <div style="font-weight: 700; font-size: 1rem; color: #0f172a;">Belum Ada Riwayat Katering</div>
        <p style="color: var(--muted); font-size: 0.88rem; margin: 4px 0 16px;">
            Kamu belum pernah memesan snack box atau katering dari Buana Food Services.
        </p>
        <a href="{{ route('food.index') }}" class="btn" style="max-width: 200px; margin: 0 auto; text-decoration: none; background:#d97706;">
            Pesan Katering
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
                            Jadwal: {{ $order->send_at?->format('d M Y, H:i') ?? '-' }} WIB
                        </div>
                    </div>
                    <span class="badge" style="background: #fef3c7; color: #92400e; font-size: 0.75rem;">
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
                        <div style="font-weight: 800; font-size: 1rem; color: #d97706;">
                            Rp {{ number_format($order->total, 0, ',', '.') }}
                        </div>
                    </div>
                    <a href="{{ route('food.show', $order->code) }}" class="btn" style="margin: 0; padding: 7px 14px; font-size: 0.82rem; width: auto; text-decoration: none; background: #d97706;">
                        Lihat Status
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
