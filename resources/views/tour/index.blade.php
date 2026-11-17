@extends('layouts.app')
@section('title', 'Buana Tour & Travel - Wisata, Ziarah & Umrah')

@section('content')
<div style="margin-bottom: 16px;">
    <a href="{{ route('home') }}" style="color:var(--muted); text-decoration:none; font-size:0.88rem; display:inline-flex; align-items:center; gap:4px;">
        &larr; Kembali ke Beranda
    </a>
    <div style="display:flex; justify-content:space-between; align-items:center; margin-top:6px;">
        <h1 style="margin:0; font-size:1.25rem;">✈️ Buana Tour & Travel</h1>
        <a href="{{ route('tour.history') }}" style="color:var(--brand); text-decoration:none; font-size:0.85rem; font-weight:600;">
            Riwayat Booking
        </a>
    </div>
    <p class="sub" style="margin-top:2px;">Etalase paket wisata, study tour, ziarah wali, tabungan & cicilan Umrah.</p>
</div>

@if ($user->isCivitas())
    <div class="eco-banner" style="margin-bottom: 16px;">
        <span style="font-size: 1.25rem;">🎓</span>
        <div>
            <b>Tarif Spesial Civitas Yayasan Wahid Hasyim</b>
            <div style="font-size: 0.8rem; opacity: 0.9;">Dapatkan subsidi dan diskon khusus mahasiswa, santri, dosen & alumni.</div>
        </div>
    </div>
@endif

{{-- FILTER KATEGORI --}}
<div style="display: flex; gap: 8px; overflow-x: auto; padding-bottom: 8px; margin-bottom: 16px;">
    <a href="{{ route('tour.index') }}" class="tag {{ !$category ? 'tag-highlight' : '' }}" style="text-decoration:none; padding:6px 14px; font-size:0.82rem; border-radius:20px;">
        Semua Paket
    </a>
    <a href="{{ route('tour.index', ['category' => 'umrah']) }}" class="tag {{ $category === 'umrah' ? 'tag-highlight' : '' }}" style="text-decoration:none; padding:6px 14px; font-size:0.82rem; border-radius:20px;">
        🕋 Paket Umrah
    </a>
    <a href="{{ route('tour.index', ['category' => 'ziarah']) }}" class="tag {{ $category === 'ziarah' ? 'tag-highlight' : '' }}" style="text-decoration:none; padding:6px 14px; font-size:0.82rem; border-radius:20px;">
        🕌 Ziarah Wali
    </a>
    <a href="{{ route('tour.index', ['category' => 'study_tour']) }}" class="tag {{ $category === 'study_tour' ? 'tag-highlight' : '' }}" style="text-decoration:none; padding:6px 14px; font-size:0.82rem; border-radius:20px;">
        🎓 Study Tour
    </a>
</div>

{{-- DAFTAR PAKET PERJALANAN --}}
<div style="display: flex; flex-direction: column; gap: 14px;">
    @foreach ($packages as $pkg)
        @php
            $userPrice = $pkg->priceFor($user);
            $isDiscounted = $userPrice < $pkg->price;
        @endphp
        <div class="card" style="padding: 16px; border-left: 4px solid #16a34a;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px;">
                <div>
                    <span style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700; color: #16a34a; letter-spacing: 0.05em;">
                        {{ strtoupper($pkg->category) }} &bull; {{ $pkg->duration }}
                    </span>
                    <h3 style="margin: 4px 0 6px; font-size: 1.05rem; color: #0f172a; line-height: 1.35;">
                        {{ $pkg->title }}
                    </h3>
                </div>
            </div>

            <p style="font-size: 0.82rem; color: var(--muted); margin: 4px 0 12px; line-height: 1.4;">
                {{ Str::limit($pkg->itinerary, 130) }}
            </p>

            <div style="background: #f8fafc; border: 1px solid #f1f5f9; border-radius: 8px; padding: 8px 12px; margin-bottom: 12px; display: flex; justify-content: space-between; font-size: 0.78rem;">
                <div>
                    📅 Keberangkatan: <b>{{ $pkg->departure_date->format('d M Y') }}</b>
                </div>
                <div>
                    👥 Kuota: <b>{{ $pkg->booked }}/{{ $pkg->quota }} pax</b>
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: flex-end; border-top: 1px dashed #e2e8f0; padding-top: 10px;">
                <div>
                    @if ($isDiscounted)
                        <div style="font-size: 0.75rem; text-decoration: line-through; color: #94a3b8;">
                            Rp {{ number_format($pkg->price, 0, ',', '.') }}
                        </div>
                        <div style="font-size: 1.15rem; font-weight: 800; color: #16a34a;">
                            Rp {{ number_format($userPrice, 0, ',', '.') }}
                        </div>
                        <span style="font-size: 0.68rem; background: #dcfce7; color: #166534; padding: 2px 6px; border-radius: 4px; font-weight: 700;">
                            Harga Civitas Unwahas
                        </span>
                    @else
                        <div style="font-size: 1.15rem; font-weight: 800; color: #16a34a;">
                            Rp {{ number_format($pkg->price, 0, ',', '.') }}
                        </div>
                        <div style="font-size: 0.72rem; color: var(--muted);">/ orang</div>
                    @endif

                    @if ($pkg->allow_installment)
                        <div style="font-size: 0.74rem; color: #0284c7; font-weight: 600; margin-top: 2px;">
                            ✨ Tersedia Skema Cicilan (DP Rp {{ number_format($pkg->min_dp, 0, ',', '.') }})
                        </div>
                    @endif
                </div>

                <a href="{{ route('tour.package.show', $pkg->id) }}" class="btn" style="margin: 0; padding: 8px 16px; font-size: 0.85rem; width: auto; background: #16a34a; text-decoration: none;">
                    Pilih Paket &rarr;
                </a>
            </div>
        </div>
    @endforeach
</div>
@endsection
