@extends('layouts.app')
@section('title', 'Buana Tour & Travel - Wisata, Ziarah & Umrah')

@section('content')
<div class="bg-gradient-to-r from-emerald-900 via-teal-800 to-emerald-800 rounded-3xl p-6 sm:p-8 text-white shadow-lg mb-6 relative overflow-hidden">
    <div class="absolute -right-8 -bottom-10 w-52 h-52 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
    <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-emerald-200 font-semibold mb-2">
                <a href="{{ route('home') }}" class="hover:text-white">&larr; Beranda</a>
                <span>&bull;</span>
                <span class="uppercase tracking-wider">Unit 3 &bull; Perjalanan & Ziarah</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">✈️ Buana Tour & Travel</h1>
            <p class="text-xs sm:text-sm text-emerald-100 mt-1 max-w-xl">
                Etalase paket wisata, study tour KKL fakultas, ziarah wali, serta program tabungan & skema cicilan Umrah civitas UNWAHAS.
            </p>
        </div>
        <a href="{{ route('tour.history') }}" class="self-start sm:self-center px-4 py-2.5 rounded-xl bg-white/15 hover:bg-white/25 border border-white/25 text-white text-xs font-bold backdrop-blur-xs transition shrink-0">
            📋 Riwayat Booking & Cicilan
        </a>
    </div>
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
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
