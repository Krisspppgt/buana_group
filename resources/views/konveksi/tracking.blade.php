@extends('layouts.app')
@section('title', 'Progres Produksi Konveksi - ' . $order->code)

@section('content')
<div style="margin-bottom: 16px;">
    <a href="{{ route('konveksi.quotes') }}" style="color:var(--muted); text-decoration:none; font-size:0.88rem; display:inline-flex; align-items:center; gap:4px;">
        &larr; Kembali ke Kuotasi
    </a>
    <div style="display:flex; justify-content:space-between; align-items:center; margin-top:6px;">
        <h1 style="margin:0; font-size:1.25rem;">🧵 Progres Produksi Transparan</h1>
        <span class="badge" style="background:#fce7f3; color:#9d174d; font-size:0.8rem;">
            {{ $order->code }}
        </span>
    </div>
</div>

{{-- 6 TAHAPAN PRODUKSI REAL-TIME --}}
<div class="card" style="padding: 18px; margin-bottom: 16px; border-top: 4px solid #db2777;">
    <div style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:var(--muted); font-weight:700;">
        Tahap Pengerjaan Workshop Konveksi
    </div>
    <div style="font-size:1.15rem; font-weight:800; color:#db2777; margin-top:2px;">
        {{ $latestStage->label() }}
    </div>
    <div style="font-size:0.82rem; color:var(--muted); margin-top:2px;">
        Pemesanan: {{ $order->ship_name }} &bull; {{ $order->items->sum('qty') }} pcs
    </div>

    {{-- Progress Steps Pipeline --}}
    @php
        $stages = [
            \App\Enums\ProductionStage::WaitingMaterial,
            \App\Enums\ProductionStage::Cutting,
            \App\Enums\ProductionStage::Sewing,
            \App\Enums\ProductionStage::Printing,
            \App\Enums\ProductionStage::Finishing,
            \App\Enums\ProductionStage::ReadyToShip,
        ];
        $currentValue = $latestStage->value;
    @endphp

    <div style="display: flex; flex-direction: column; gap: 14px; margin-top: 20px; padding-left: 6px;">
        @foreach ($stages as $stage)
            @php
                $isDone = $stage->value < $currentValue;
                $isCurrent = $stage->value === $currentValue;
                $isUpcoming = $stage->value > $currentValue;
            @endphp
            <div style="display: flex; gap: 12px; align-items: flex-start; opacity: {{ $isUpcoming ? '0.4' : '1' }};">
                <div style="
                    width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: bold; flex-shrink: 0;
                    @if ($isDone)
                        background: #10b981; color: #fff;
                    @elseif ($isCurrent)
                        background: #db2777; color: #fff; box-shadow: 0 0 0 4px rgba(219,39,119,0.2);
                    @else
                        background: #e2e8f0; color: #64748b;
                    @endif
                ">
                    @if ($isDone) ✓ @else {{ $stage->value }} @endif
                </div>
                <div>
                    <div style="font-weight: 700; font-size: 0.9rem; color: {{ $isCurrent ? '#db2777' : '#0f172a' }};">
                        {{ $stage->label() }}
                    </div>
                    <div style="font-size: 0.76rem; color: var(--muted);">
                        @if ($stage === \App\Enums\ProductionStage::WaitingMaterial)
                            Pengadaan gulungan kain & pencocokan palet warna
                        @elseif ($stage === \App\Enums\ProductionStage::Cutting)
                            Pemotongan pola sesuai rincian ukuran S/M/L/XL/XXL
                        @elseif ($stage === \App\Enums\ProductionStage::Sewing)
                            Proses penjahitan oleh tim penjahit berpengalaman
                        @elseif ($stage === \App\Enums\ProductionStage::Printing)
                            Aplikasi bordir komputer presisi / sablon DTF
                        @elseif ($stage === \App\Enums\ProductionStage::Finishing)
                            Quality Control, pembersihan sisa benang & setrika uap
                        @elseif ($stage === \App\Enums\ProductionStage::ReadyToShip)
                            Pengemasan plastik rapi siap diantarkan atau diambil
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- LOG CATATAN DARI WORKSHOP --}}
<div class="card" style="padding: 16px; margin-bottom: 20px;">
    <div style="font-weight: 700; font-size: 0.92rem; margin-bottom: 10px; color:#0f172a;">
        📝 Catatan Riwayat Pengerjaan
    </div>

    @if ($order->productionLogs->isEmpty())
        <div style="font-size: 0.84rem; color: var(--muted); padding: 8px 0;">
            Belum ada catatan log tambahan dari operator workshop.
        </div>
    @else
        <div style="display: flex; flex-direction: column; gap: 8px;">
            @foreach ($order->productionLogs as $log)
                <div style="background: #f8fafc; border: 1px solid #f1f5f9; padding: 10px; border-radius: 8px;">
                    <div style="display: flex; justify-content: space-between; font-size: 0.78rem; font-weight: 700; color: #db2777;">
                        <span>{{ $log->stage->label() }}</span>
                        <span style="color: var(--muted); font-weight: normal;">{{ $log->created_at->format('d M Y, H:i') }} WIB</span>
                    </div>
                    @if ($log->note)
                        <div style="font-size: 0.82rem; color: #334155; margin-top: 4px;">
                            {{ $log->note }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>

@auth
    @if (auth()->user()->isStaff())
        <div class="card" style="padding: 16px; margin-bottom: 20px; border: 1px solid #fbcfe8; background: #fdf2f8;">
            <div style="font-weight: 700; font-size: 0.9rem; color: #9d174d; margin-bottom: 8px;">
                🛠️ Panel Operator Workshop: Update Tahap Produksi
            </div>
            <form method="POST" action="{{ route('konveksi.tracking.stage', $order->code) }}" style="display: flex; flex-direction: column; gap: 10px;">
                @csrf
                <div>
                    <label style="margin: 0 0 4px; font-size: 0.8rem;">Pilih Tahapan Pengerjaan</label>
                    <select name="stage" style="padding: 8px 12px; font-size: 0.85rem;">
                        @foreach (\App\Enums\ProductionStage::cases() as $stg)
                            <option value="{{ $stg->value }}" @selected($latestStage === $stg)>
                                {{ $stg->value }}. {{ $stg->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="margin: 0 0 4px; font-size: 0.8rem;">Catatan Operator Workshop</label>
                    <input type="text" name="note" placeholder="Contoh: Pemotongan pola ukuran S-XXL telah selesai 100%" required maxlength="80" style="padding: 8px 12px; font-size: 0.85rem;">
                </div>
                <button type="submit" class="btn" style="background: #db2777; align-self: flex-start; padding: 8px 16px; font-size: 0.84rem;">
                    Simpan Log Produksi
                </button>
            </form>
        </div>
    @endif
@endauth

<div style="display: flex; gap: 10px; flex-wrap: wrap;">
    <a href="{{ route('payment.checkout', $order->code) }}" class="btn" style="background:#0D5A47; text-decoration:none; flex: 1;">
        💳 Payment Gateway & Tukar Poin (Rp {{ number_format($order->total, 0, ',', '.') }})
    </a>
    <a href="{{ route('konveksi.index') }}" class="btn" style="background:#db2777; text-decoration:none; flex: 1;">
        👕 Pesan Konveksi Baru
    </a>
</div>
@endsection
