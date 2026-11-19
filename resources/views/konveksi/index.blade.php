@extends('layouts.app')
@section('title', 'Buana Konveksi - Jas Almamater, PDH & Kaos')

@section('content')
<div class="bg-gradient-to-r from-pink-900 via-rose-800 to-purple-900 rounded-2xl p-6 sm:p-8 text-white shadow-lg mb-6 relative overflow-hidden">
    <div class="absolute -right-8 -bottom-10 w-52 h-52 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
    <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-pink-200 font-semibold mb-2">
                <a href="{{ route('home') }}" class="hover:text-white">&larr; Beranda</a>
                <span>&bull;</span>
                <span class="uppercase tracking-wider">Unit 5 &bull; Produksi Garmen & Seragam</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Buana Konveksi</h1>
            <p class="text-xs sm:text-sm text-pink-100 mt-1 max-w-xl">
                Katalog bahan kain pilihan, portofolio jas almamater & PDH/PDL, kalkulasi kuotasi otomatis, dan pelacakan progres jahit transparan.
            </p>
        </div>
        <a href="{{ route('konveksi.quotes') }}" class="self-start sm:self-center px-4 py-2.5 rounded-xl bg-white/15 hover:bg-white/25 border border-white/25 text-white text-xs font-bold backdrop-blur-xs transition shrink-0">
            Riwayat Kuotasi
        </a>
    </div>
</div>

@if ($user->isCivitas())
    <div class="eco-banner" style="margin-bottom: 16px;">
        <div>
            <b>Diskon Civitas Unwahas Aktif</b>
            <div style="font-size: 0.8rem; opacity: 0.9;">Potongan harga khusus pemesanan seragam ormawa, BEM, fakultas & angkatan.</div>
        </div>
    </div>
@endif

@if ($errors->any())
    <div style="background:#fef2f2; border:1px solid #fecaca; color:#b91c1c; padding:12px; border-radius:10px; margin-bottom:16px; font-size:0.88rem;">
        @foreach ($errors->all() as $error)
            <div>• {{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="unit-grid-2col">
<div>
{{-- 1. KATALOG BAHAN KAIN & WARNA --}}
<div class="card" style="margin-bottom: 18px; padding: 16px;">
    <div style="font-weight: 700; font-size: 0.95rem; color:#0f172a; margin-bottom: 10px;">
        Pilihan Bahan Kain Terbaik
    </div>
    <div style="display: flex; flex-direction: column; gap: 8px;">
        @foreach ($fabrics as $f)
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 10px 12px;">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <b style="font-size:0.88rem; color:#0f172a;">{{ $f['name'] }}</b>
                    <span style="font-size:0.7rem; background:#ccfbf1; color:#0f766e; padding:2px 8px; border-radius:6px; font-weight:700;">
                        {{ $f['badge'] }}
                    </span>
                </div>
                <div style="font-size:0.75rem; color:#0f766e; font-weight:600; margin-top:2px;">Peruntukan: {{ $f['type'] }}</div>
                <div style="font-size:0.8rem; color:var(--muted); margin-top:2px;">{{ $f['desc'] }}</div>
            </div>
        @endforeach
    </div>

    {{-- Palet Warna --}}
    <div style="margin-top: 14px; padding-top: 12px; border-top: 1px dashed #e2e8f0;">
        <div style="font-weight: 700; font-size: 0.85rem; color:#334155; margin-bottom: 8px;">
            Palet Warna Populer:
        </div>
        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
            @foreach ($colors as $c)
                <div style="display:flex; align-items:center; gap:6px; background:#fff; border:1px solid #cbd5e1; border-radius:8px; padding:4px 8px; font-size:0.75rem;">
                    <div style="width:14px; height:14px; border-radius:4px; background:{{ $c['hex'] }}; border:1px solid rgba(0,0,0,0.15);"></div>
                    <span>{{ $c['name'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- 2. PORTOFOLIO PRODUK --}}
<div class="card" style="margin-bottom: 18px; padding: 16px;">
    <div style="font-weight: 700; font-size: 0.95rem; color:#0f172a; margin-bottom: 10px;">
        Portofolio Produk Konveksi
    </div>
    <div style="display: flex; flex-direction: column; gap: 8px;">
        @foreach ($portfolio as $item)
            <div style="display:flex; justify-content:space-between; align-items:center; padding:8px 0; border-bottom:1px solid #f1f5f9;">
                <div>
                    <div style="font-weight:700; font-size:0.86rem; color:#0f172a;">{{ $item['title'] }}</div>
                    <div style="font-size:0.75rem; color:var(--muted);">Bahan: {{ $item['material'] }}</div>
                </div>
                <div style="font-size:0.8rem; font-weight:700; color:#0f766e; text-align:right;">
                    {{ $item['est'] }}
                </div>
            </div>
        @endforeach
    </div>
</div>
</div>

{{-- 3. FORM SISTEM KUOTASI & UPLOAD DESAIN --}}
<form method="POST" action="{{ route('konveksi.quotes.store') }}" enctype="multipart/form-data" id="quoteForm" class="unit-sticky-col">
    @csrf

    <div class="card" style="margin-bottom: 20px; border-top: 3px solid #0f766e; padding: 18px;">
        <div style="font-weight: 700; font-size: 1rem; color:#0f172a; margin-bottom: 4px;">
            Formulir Kuotasi & Upload Desain Mockup
        </div>
        <p style="font-size:0.82rem; color:var(--muted); margin:0 0 14px;">
            Dapatkan estimasi harga otomatis instan dan konsultasi pengerjaan dari tim produksi.
        </p>

        <label for="item">Jenis Pakaian / Seragam *</label>
        <select name="item" id="item" required onchange="calculateQuoteEstimate()" style="width:100%; padding:11px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.92rem; background:#fff;">
            <option value="Kemeja PDH Organisasi" selected>Kemeja PDH Organisasi / Ormawa</option>
            <option value="Jas Almamater Kampus">Jas Almamater Kampus</option>
            <option value="Seragam PDL Lapangan & KKN">Seragam PDL Lapangan & KKN</option>
            <option value="Kaos Panitia & Seminar (Pendek/Panjang)">Kaos Panitia & Seminar</option>
            <option value="Jaket Bomber / Varsity Angkatan">Jaket Bomber / Varsity Angkatan</option>
        </select>

        <label for="material">Pilihan Jenis Bahan Kain *</label>
        <select name="material" id="material" required style="width:100%; padding:11px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.92rem; background:#fff;">
            <option value="American Drill 1919">American Drill 1919 (Standar PDH Terbaik)</option>
            <option value="Japan Drill Original">Japan Drill Original (Adem & Lembut)</option>
            <option value="Taipan Tropical">Taipan Tropical (Eksklusif)</option>
            <option value="Cotton Combed 30s">Cotton Combed 30s (Kaos Adem)</option>
            <option value="Cotton Combed 24s">Cotton Combed 24s (Kaos Tebal)</option>
            <option value="Fleece Cotton">Fleece Cotton (Jaket Tebal Hangat)</option>
        </select>

        <label for="qty">Total Kuantitas Pesanan (Min. 12 pcs) *</label>
        <input type="number" name="qty" id="qty" required min="12" value="{{ old('qty', 24) }}" oninput="calculateQuoteEstimate()" placeholder="Contoh: 30">

        <div style="font-size: 0.82rem; font-weight: 600; color: #334155; margin-top: 14px; margin-bottom: 6px;">
            Rincian Pembagian Ukuran (Size Breakdown):
        </div>
        <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 6px;">
            <div>
                <label style="margin:0 0 2px; font-size:0.75rem; text-align:center;">Size S</label>
                <input type="number" name="size_s" min="0" value="{{ old('size_s', 4) }}" style="padding:8px 4px; text-align:center; font-size:0.85rem;">
            </div>
            <div>
                <label style="margin:0 0 2px; font-size:0.75rem; text-align:center;">Size M</label>
                <input type="number" name="size_m" min="0" value="{{ old('size_m', 8) }}" style="padding:8px 4px; text-align:center; font-size:0.85rem;">
            </div>
            <div>
                <label style="margin:0 0 2px; font-size:0.75rem; text-align:center;">Size L</label>
                <input type="number" name="size_l" min="0" value="{{ old('size_l', 8) }}" style="padding:8px 4px; text-align:center; font-size:0.85rem;">
            </div>
            <div>
                <label style="margin:0 0 2px; font-size:0.75rem; text-align:center;">Size XL</label>
                <input type="number" name="size_xl" min="0" value="{{ old('size_xl', 3) }}" style="padding:8px 4px; text-align:center; font-size:0.85rem;">
            </div>
            <div>
                <label style="margin:0 0 2px; font-size:0.75rem; text-align:center;">Size XXL</label>
                <input type="number" name="size_xxl" min="0" value="{{ old('size_xxl', 1) }}" style="padding:8px 4px; text-align:center; font-size:0.85rem;">
            </div>
        </div>

        <label for="design">Upload Desain Mockup / Logo (Opsional)</label>
        <input type="file" name="design" id="design" accept="image/png,image/jpeg,image/jpg" style="padding:8px; border:1px solid #cbd5e1; border-radius:8px; width:100%; font-size:0.85rem; background:#f8fafc;">
        <div style="font-size:0.72rem; color:var(--muted); margin-top:2px;">Format JPG/PNG, maksimal 4MB. Desain dapat disusulkan via WhatsApp.</div>

        <label for="notes">Catatan Spesifikasi Bordir / Sablon</label>
        <input type="text" name="notes" id="notes" value="{{ old('notes') }}" placeholder="Contoh: Bordir logo Unwahas di dada kiri, nama di dada kanan">

        {{-- BOX ESTIMASI INSTAN --}}
        <div style="margin-top:16px; background:#f0fdfa; border:1px solid #a7f3d0; border-radius:10px; padding:12px;">
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <span style="font-size:0.85rem; color:#065f46; font-weight:600;">Perkiraan Harga Satuan:</span>
                <span id="est_per_unit" style="font-weight:700; color:#0f766e;">Rp 0 / pcs</span>
            </div>
            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:6px; border-top:1px dashed #bbf7d0; padding-top:6px;">
                <span style="font-size:0.95rem; color:#0f172a; font-weight:700;">Total Estimasi Kuotasi:</span>
                <span id="est_total" style="font-weight:800; font-size:1.15rem; color:#0f766e;">Rp 0</span>
            </div>
            <div style="font-size:0.72rem; color:#0f766e; margin-top:4px;">
                *Harga final akan diverifikasi oleh tim produksi berdasarkan kerumitan bordir/sablon.
            </div>
        </div>

        <button type="submit" class="btn" style="margin-top: 14px; padding: 14px; font-size: 0.95rem;">
            Ajukan Kuotasi & Cek Penawaran
        </button>
    </div>
</form>
</div>

<script>
    function calculateQuoteEstimate() {
        const item = document.getElementById('item').value.toLowerCase();
        const qty = parseInt(document.getElementById('qty').value) || 0;
        const isCivitas = {{ $user->isCivitas() ? 'true' : 'false' }};

        let basePrice = 105000;
        if (item.includes('kaos')) {
            basePrice = 55000;
        } else if (item.includes('jas')) {
            basePrice = 135000;
        } else if (item.includes('jaket') || item.includes('bomber')) {
            basePrice = 160000;
        } else if (item.includes('pdl')) {
            basePrice = 120000;
        }

        if (qty >= 100) {
            basePrice = Math.floor(basePrice * 0.85);
        } else if (qty >= 50) {
            basePrice = Math.floor(basePrice * 0.90);
        } else if (qty >= 24) {
            basePrice = Math.floor(basePrice * 0.95);
        }

        if (isCivitas) {
            basePrice = Math.floor(basePrice * 0.95);
        }

        const total = basePrice * qty;

        document.getElementById('est_per_unit').innerText = 'Rp ' + basePrice.toLocaleString('id-ID') + ' / pcs';
        document.getElementById('est_total').innerText = 'Rp ' + total.toLocaleString('id-ID');
    }

    document.addEventListener('DOMContentLoaded', () => {
        calculateQuoteEstimate();
    });
</script>
@endsection
