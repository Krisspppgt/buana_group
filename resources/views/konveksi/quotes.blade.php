@extends('layouts.app')
@section('title', 'Daftar Pengajuan Kuotasi - Buana Konveksi')

@section('content')
<div style="margin-bottom: 16px;">
    <a href="{{ route('konveksi.index') }}" style="color:var(--muted); text-decoration:none; font-size:0.88rem; display:inline-flex; align-items:center; gap:4px;">
        &larr; Kembali ke Katalog Konveksi
    </a>
    <div style="display:flex; justify-content:space-between; align-items:center; margin-top:6px;">
        <h1 style="margin:0; font-size:1.25rem;">📋 Pengajuan Kuotasi Konveksi</h1>
        <a href="{{ route('konveksi.index') }}" class="btn" style="margin:0; padding:6px 12px; font-size:0.8rem; width:auto;">
            + Ajukan Baru
        </a>
    </div>
</div>

@if (session('success'))
    <div style="background:#ecfdf5; border:1px solid #a7f3d0; color:#065f46; padding:12px; border-radius:12px; margin-bottom:16px; font-size:0.88rem;">
        {{ session('success') }}
    </div>
@endif

@if ($quotes->isEmpty())
    <div class="card" style="text-align: center; padding: 40px 20px;">
        <div style="font-size: 2.5rem; margin-bottom: 8px;">👕</div>
        <div style="font-weight: 700; font-size: 1rem; color: #0f172a;">Belum Ada Pengajuan Kuotasi</div>
        <p style="color: var(--muted); font-size: 0.88rem; margin: 4px 0 16px;">
            Kamu belum pernah mengajukan kuotasi jas almamater, PDH, atau kaos.
        </p>
        <a href="{{ route('konveksi.index') }}" class="btn" style="max-width: 200px; margin: 0 auto; text-decoration: none;">
            Ajukan Kuotasi
        </a>
    </div>
@else
    <div style="display: flex; flex-direction: column; gap: 12px;">
        @foreach ($quotes as $quote)
            <div class="card" style="padding: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                    <div>
                        <div style="font-weight: 800; font-size: 0.95rem; color: #0f172a;">
                            {{ $quote->item }}
                        </div>
                        <div style="font-size: 0.75rem; color: var(--muted);">
                            {{ $quote->created_at->format('d M Y, H:i') }} WIB &bull; {{ $quote->qty }} pcs
                        </div>
                    </div>
                    @php
                        $badgeBg = match ($quote->status) {
                            \App\Enums\QuoteStatus::Submitted => '#fef3c7',
                            \App\Enums\QuoteStatus::Offered => '#dbeafe',
                            \App\Enums\QuoteStatus::Approved => '#dcfce7',
                            \App\Enums\QuoteStatus::Rejected => '#fee2e2',
                        };
                        $badgeColor = match ($quote->status) {
                            \App\Enums\QuoteStatus::Submitted => '#92400e',
                            \App\Enums\QuoteStatus::Offered => '#1e40af',
                            \App\Enums\QuoteStatus::Approved => '#166534',
                            \App\Enums\QuoteStatus::Rejected => '#991b1b',
                        };
                    @endphp
                    <span class="badge" style="background: {{ $badgeBg }}; color: {{ $badgeColor }}; font-size: 0.75rem;">
                        {{ $quote->status->label() }}
                    </span>
                </div>

                <div style="font-size: 0.82rem; color: var(--muted); margin-bottom: 10px;">
                    Rincian: {{ $quote->sizes }}
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #f1f5f9; padding-top: 8px;">
                    <div>
                        <div style="font-size: 0.72rem; color: var(--muted);">Estimasi Harga</div>
                        <div style="font-weight: 800; font-size: 0.98rem; color: #0f766e;">
                            Rp {{ number_format($quote->final_price ?? $quote->est_price, 0, ',', '.') }}
                            @if ($quote->final_price)
                                <span style="font-size: 0.7rem; color: #16a34a;">(Final)</span>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('konveksi.quotes.show', $quote->id) }}" class="btn" style="margin: 0; padding: 7px 14px; font-size: 0.82rem; width: auto; text-decoration: none;">
                        Lihat Detail
                    </a>
                </div>
            </div>
        @endforeach

        <div style="margin-top: 10px;">
            {{ $quotes->links() }}
        </div>
    </div>
@endif
@endsection
