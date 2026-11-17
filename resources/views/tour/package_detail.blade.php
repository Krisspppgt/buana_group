@extends('layouts.app')
@section('title', $package->title . ' - Buana Tour & Travel')

@section('content')
<div style="margin-bottom: 16px;">
    <a href="{{ route('tour.index') }}" style="color:var(--muted); text-decoration:none; font-size:0.88rem; display:inline-flex; align-items:center; gap:4px;">
        &larr; Kembali ke Katalog Paket
    </a>
    <div style="margin-top:6px;">
        <span class="badge" style="background:#dcfce7; color:#166534; font-size:0.75rem; text-transform:uppercase;">
            {{ $package->category }} &bull; {{ $package->duration }}
        </span>
        <h1 style="margin:4px 0 2px; font-size:1.25rem; line-height:1.35;">{{ $package->title }}</h1>
    </div>
</div>

{{-- INFORMASI PAKET --}}
<div class="card" style="padding: 16px; margin-bottom: 16px;">
    <div style="font-size: 0.82rem; color: #475569; line-height: 1.5; margin-bottom: 14px;">
        <b>Itinerary & Rangkaian Perjalanan:</b><br>
        {{ $package->itinerary }}
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; font-size: 0.8rem; background: #f8fafc; padding: 10px; border-radius: 8px;">
        <div>📅 Keberangkatan: <b>{{ $package->departure_date->format('d M Y') }}</b></div>
        <div>⏱️ Durasi: <b>{{ $package->duration }}</b></div>
        <div>👥 Kuota Tersedia: <b>{{ $package->quota - $package->booked }} kursi</b></div>
        <div>🏨 Standar: <b>Hotel & Transport AC</b></div>
    </div>
</div>

{{-- FORM BOOKING & PENDAFTARAN ROMBONGAN --}}
<form method="POST" action="{{ route('tour.booking.store') }}" enctype="multipart/form-data" id="tourBookingForm">
    @csrf
    <input type="hidden" name="tour_package_id" value="{{ $package->id }}">

    <div class="card" style="margin-bottom: 20px; border-top: 3px solid #16a34a; padding: 18px;">
        <div style="font-weight: 700; font-size: 1rem; color:#0f172a; margin-bottom: 4px;">
            👥 Formulir Pendaftaran Rombongan / Peserta
        </div>
        <p style="font-size:0.82rem; color:var(--muted); margin:0 0 14px;">
            Koordinator dapat mendaftarkan rombongan jamaah/santri/keluarga dalam satu formulir.
        </p>

        <label for="coordinator_name">Nama Koordinator / Pemesan *</label>
        <input type="text" name="coordinator_name" id="coordinator_name" required value="{{ old('coordinator_name', $user->name) }}" placeholder="Contoh: Ustadz M. Fauzan / Ibu Fatimah">

        <label for="coordinator_phone">No. WhatsApp Koordinator *</label>
        <input type="tel" name="coordinator_phone" id="coordinator_phone" required value="{{ old('coordinator_phone', $user->phone) }}" placeholder="081234567890">

        <label for="total_pax">Jumlah Peserta Rombongan (Pax) *</label>
        <input type="number" name="total_pax" id="total_pax" required min="1" max="100" value="{{ old('total_pax', 1) }}" oninput="updateTourCalculation()" placeholder="1">

        {{-- FITUR MANAJEMEN GRUP: INPUT IDENTITAS MASSAL --}}
        <div style="margin-top: 14px; padding-top: 12px; border-top: 1px dashed #e2e8f0;">
            <div style="font-weight: 700; font-size: 0.88rem; color: #0f172a; margin-bottom: 4px;">
                📋 Data Identitas Anggota Rombongan:
            </div>
            <p style="font-size: 0.78rem; color: var(--muted); margin: 0 0 8px;">
                Masukkan data NIK KTP / No. Paspor peserta, atau upload file dokumen manifest rombongan:
            </p>

            <div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 10px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 6px;">
                    <input type="text" name="member_names[]" placeholder="Nama Peserta 1" value="{{ old('coordinator_name', $user->name) }}" style="padding:8px; font-size:0.85rem;">
                    <input type="text" name="member_ids[]" placeholder="NIK KTP / No. Paspor" style="padding:8px; font-size:0.85rem;">
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 6px;">
                    <input type="text" name="member_names[]" placeholder="Nama Peserta 2 (jika ada)" style="padding:8px; font-size:0.85rem;">
                    <input type="text" name="member_ids[]" placeholder="NIK KTP / No. Paspor" style="padding:8px; font-size:0.85rem;">
                </div>
            </div>

            <label for="manifest_file" style="font-size: 0.8rem;">Atau Upload File Manifest / Foto KTP Massal (PDF/Excel/Foto):</label>
            <input type="file" name="manifest_file" id="manifest_file" accept=".pdf,.xlsx,.csv,.jpg,.jpeg,.png" style="padding:8px; border:1px solid #cbd5e1; border-radius:8px; width:100%; font-size:0.85rem; background:#f8fafc;">
        </div>

        {{-- FITUR SKEMA CICILAN / TABUNGAN UMRAH --}}
        <div style="margin-top: 16px; padding-top: 14px; border-top: 1px solid #e2e8f0;">
            <div style="font-weight: 700; font-size: 0.88rem; color: #0f172a; margin-bottom: 8px;">
                💳 Pilih Skema Pembayaran
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 12px;">
                <label style="margin:0; cursor:pointer;">
                    <input type="radio" name="payment_scheme" value="cash" checked onchange="toggleScheme()" style="display:none;" id="scheme_cash">
                    <div class="scheme-btn active" id="btn_cash">
                        <div style="font-weight: 700; font-size: 0.85rem;">💰 Bayar Penuh</div>
                        <div style="font-size: 0.72rem; color: var(--muted);">Lunas Sekaligus</div>
                    </div>
                </label>

                @if ($package->allow_installment)
                    <label style="margin:0; cursor:pointer;">
                        <input type="radio" name="payment_scheme" value="cicilan" onchange="toggleScheme()" style="display:none;" id="scheme_cicilan">
                        <div class="scheme-btn" id="btn_cicilan">
                            <div style="font-weight: 700; font-size: 0.85rem;">🗓️ Skema Cicilan</div>
                            <div style="font-size: 0.72rem; color: #16a34a; font-weight:600;">Tabungan Berkala</div>
                        </div>
                    </label>
                @endif
            </div>

            {{-- Opsi Tenor Cicilan --}}
            <div id="installment_box" style="display: none; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 12px; margin-top: 8px;">
                <div style="font-weight: 700; font-size: 0.82rem; color: #166534; margin-bottom: 6px;">
                    Pilih Tenor Cicilan Tabungan Umrah / Perjalanan:
                </div>
                <select name="installment_tenor" id="installment_tenor" onchange="updateTourCalculation()" style="width:100%; padding:9px; border:1px solid #86efac; border-radius:8px; font-size:0.88rem; background:#fff;">
                    <option value="3">3 Bulan Angsuran</option>
                    <option value="6" selected>6 Bulan Angsuran</option>
                    <option value="12">12 Bulan Angsuran (1 Tahun)</option>
                </select>

                <div style="margin-top: 8px; font-size: 0.78rem; color: #15803d; line-height: 1.4;">
                    <div>• Uang Muka (DP) Awal: <b>Rp {{ number_format($package->min_dp, 0, ',', '.') }}</b> / pax</div>
                    <div>• Estimasi Angsuran: <b id="calc_monthly">Rp 0</b> / bulan per orang</div>
                </div>
            </div>
        </div>

        {{-- RINGKASAN TOTAL --}}
        <div style="margin-top: 16px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px;">
            <div style="display: flex; justify-content: space-between; font-size: 0.85rem; margin-bottom: 4px;">
                <span style="color: var(--muted);">Harga per Pax:</span>
                <span style="font-weight: 600;">Rp {{ number_format($package->priceFor($user), 0, ',', '.') }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 0.85rem; margin-bottom: 6px;">
                <span style="color: var(--muted);">Total Peserta:</span>
                <span id="calc_pax_label" style="font-weight: 600;">1 pax</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px dashed #cbd5e1; padding-top: 8px; margin-top: 4px;">
                <span style="font-weight: 700; font-size: 0.95rem;">Total Tagihan Paket:</span>
                <span id="calc_total" style="font-weight: 800; font-size: 1.15rem; color: #16a34a;">Rp 0</span>
            </div>
        </div>

        <button type="submit" class="btn" style="margin-top: 14px; padding: 14px; font-size: 1rem; background: #16a34a;">
            🚀 Daftarkan Rombongan Sekarang
        </button>
    </div>
</form>

<style>
    .scheme-btn {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px;
        text-align: center;
        background: #fff;
        transition: all .15s ease;
    }
    .scheme-btn.active {
        border-color: #16a34a;
        background: #f0fdf4;
        box-shadow: 0 0 0 1px #16a34a;
    }
</style>

<script>
    const unitPrice = {{ $package->priceFor($user) }};
    const minDp = {{ $package->min_dp }};

    function toggleScheme() {
        const isCash = document.getElementById('scheme_cash').checked;
        const cicilanRadio = document.getElementById('scheme_cicilan');
        const isCicilan = cicilanRadio ? cicilanRadio.checked : false;

        document.getElementById('btn_cash').className = 'scheme-btn ' + (isCash ? 'active' : '');
        if (document.getElementById('btn_cicilan')) {
            document.getElementById('btn_cicilan').className = 'scheme-btn ' + (isCicilan ? 'active' : '');
        }

        const box = document.getElementById('installment_box');
        if (box) {
            box.style.display = isCicilan ? 'block' : 'none';
        }

        updateTourCalculation();
    }

    function updateTourCalculation() {
        const pax = Math.max(1, parseInt(document.getElementById('total_pax').value) || 1);
        const total = unitPrice * pax;

        document.getElementById('calc_pax_label').innerText = pax + ' pax';
        document.getElementById('calc_total').innerText = 'Rp ' + total.toLocaleString('id-ID');

        const tenorSelect = document.getElementById('installment_tenor');
        if (tenorSelect) {
            const tenor = parseInt(tenorSelect.value) || 6;
            const remaining = Math.max(0, unitPrice - minDp);
            const monthly = Math.ceil(remaining / tenor);
            document.getElementById('calc_monthly').innerText = 'Rp ' + monthly.toLocaleString('id-ID');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        updateTourCalculation();
    });
</script>
@endsection
