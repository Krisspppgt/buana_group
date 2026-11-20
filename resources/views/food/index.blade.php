@extends('layouts.app')
@section('title', 'Buana Food Services - Katering & Snack Box')

@section('content')
<div class="bg-gradient-to-r from-amber-800 via-orange-800 to-amber-900 rounded-2xl p-6 sm:p-8 text-white shadow-lg mb-6 relative overflow-hidden">
    <div class="absolute -right-8 -bottom-10 w-52 h-52 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
    <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-amber-200 font-semibold mb-2">
                <a href="{{ route('home') }}" class="hover:text-white">&larr; Beranda</a>
                <span>&bull;</span>
                <span class="uppercase tracking-wider">Unit 2 &bull; Katering & Konsumsi Kampus</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Buana Food Services</h1>
            <p class="text-xs sm:text-sm text-amber-100 mt-1 max-w-xl">
                Snack box, nasi kotak, katering prasmanan, kalkulator anggaran otomatis & penjadwalan pengiriman bertahap sesuai rundown acara.
            </p>
        </div>
    </div>
</div>

@if ($user->isCivitas())
    <div class="eco-banner" style="margin-bottom: 16px;">
        <div>
            <b>Diskon Civitas Unwahas Aktif</b>
            <div style="font-size: 0.8rem; opacity: 0.9;">Dapatkan harga khusus mahasiswa, dosen & panitia ormawa kampus.</div>
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

{{-- FITUR 1: INTERACTIVE BUDGET CALCULATOR --}}
<div class="card" style="background: linear-gradient(135deg, #fffbeb, #fef3c7); border: 1px solid #fde68a; margin-bottom: 18px; padding: 16px;">
    <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
        <div style="font-weight:700; font-size:0.95rem; color:#92400e;">Budget Calculator Acara</div>
    </div>
    <p style="font-size:0.82rem; color:#b45309; margin:0 0 10px;">
        Bingung menyesuaikan menu dengan dana panitia? Masukkan anggaran & jumlah orang di bawah ini:
    </p>

    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px;">
        <div>
            <label style="margin:0 0 4px; font-size:0.78rem; color:#92400e;">Total Anggaran Konsumsi (Rp):</label>
            <input type="number" id="calc_budget" placeholder="Contoh: 1000000" oninput="runBudgetCalc()" style="padding:8px; font-size:0.9rem; border-color:#fcd34d;">
        </div>
        <div>
            <label style="margin:0 0 4px; font-size:0.78rem; color:#92400e;">Jumlah Orang / Porsi:</label>
            <input type="number" id="calc_pax" placeholder="Contoh: 40" oninput="runBudgetCalc()" style="padding:8px; font-size:0.9rem; border-color:#fcd34d;">
        </div>
    </div>

    <div id="calc_result" style="display:none; margin-top:12px; background:#fff; border:1px solid #fcd34d; border-radius:8px; padding:10px; font-size:0.84rem; color:#78350f;">
        <!-- Hasil saran kalkulator -->
    </div>
</div>

<form method="POST" action="{{ route('food.store') }}" id="foodOrderForm" class="unit-grid-2col">
    @csrf

    <div>
    {{-- FITUR 2: PREFERENSI DIET & INFORMASI ACARA --}}
    <div class="card" style="margin-bottom: 18px;">
        <div style="font-weight: 700; font-size: 0.95rem; margin-bottom: 12px; color:#0f172a;">
            1. Detail Acara & Preferensi Diet
        </div>

        <label for="event_name">Nama Kegiatan / Acara *</label>
        <input type="text" name="event_name" id="event_name" required value="{{ old('event_name') }}" placeholder="Contoh: Seminar Nasional FTIK / Rapat Senat">

        <label for="send_at">Tanggal & Waktu Acara Dimulai *</label>
        <input type="datetime-local" name="send_at" id="send_at" required value="{{ old('send_at') }}">

        <label for="diet_pref">Preferensi Diet / Khusus *</label>
        <select name="diet_pref" id="diet_pref" style="width:100%; padding:11px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.92rem; background:#fff;">
            <option value="Reguler (Standar Halal)" selected>Reguler (Menu Masakan Nusantara)</option>
            <option value="Vegetarian">Vegetarian (Bebas Daging, Nabati & Tahu/Tempe)</option>
            <option value="Rendah Gula (Diabetes-friendly)">Rendah Gula (Gula Rendah Kalori & Porsi Serat)</option>
            <option value="Bebas MSG / Sehat Alami">Bebas MSG (Rempah Alami Non-Pengawet)</option>
        </select>
    </div>

    {{-- FITUR 3: MANAJEMEN RUNDOWN ACARA (PENGIRIMAN BERTAHAP) --}}
    <div class="card" style="margin-bottom: 18px; border-left: 4px solid #0f766e;">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <div style="font-weight: 700; font-size: 0.95rem; color:#0f172a;">
                2. Jadwal Pengiriman Bertahap Sesuai Rundown
            </div>
        </div>
        <p style="font-size:0.8rem; color:var(--muted); margin:4px 0 12px;">
            Armada katering Buana dapat mengantar bertahap agar makanan tetap hangat & tepat waktu.
        </p>

        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:8px;">
            <div>
                <label style="margin:0 0 4px; font-size:0.75rem;">Snack Panitia:</label>
                <input type="text" name="rundown_panitia" value="{{ old('rundown_panitia') }}" placeholder="Pkl 07:30" style="padding:8px; font-size:0.85rem;">
            </div>
            <div>
                <label style="margin:0 0 4px; font-size:0.75rem;">Snack Peserta:</label>
                <input type="text" name="rundown_peserta" value="{{ old('rundown_peserta') }}" placeholder="Pkl 09:30" style="padding:8px; font-size:0.85rem;">
            </div>
            <div>
                <label style="margin:0 0 4px; font-size:0.75rem;">Makan Siang / VIP:</label>
                <input type="text" name="rundown_vip" value="{{ old('rundown_vip') }}" placeholder="Pkl 12:00" style="padding:8px; font-size:0.85rem;">
            </div>
        </div>
    </div>

    {{-- FITUR 4: KATALOG MENU KATERING --}}
    <div style="font-weight: 700; font-size: 0.95rem; margin-bottom: 10px; color:#0f172a;">
        3. Pilih Paket Makanan & Katering
    </div>

    <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 20px;">
        @foreach ($products as $p)
            @php
                $userPrice = $p->priceFor($user, 1);
                $isDiscounted = $userPrice < $p->price;
            @endphp
            <div class="card" style="padding: 14px; display: flex; flex-direction: column; gap: 8px;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px;">
                    <div>
                        <div style="font-weight: 700; font-size: 0.95rem; color:#0f172a;">
                            {{ $p->name }}
                        </div>
                        <div style="font-size: 0.8rem; color: var(--muted); margin-top: 2px;">
                            {{ $p->info }}
                        </div>
                    </div>
                    <div style="text-align: right; flex-shrink: 0;">
                        @if ($isDiscounted)
                            <div style="font-size: 0.75rem; text-decoration: line-through; color: #94a3b8;">
                                Rp {{ number_format($p->price, 0, ',', '.') }}
                            </div>
                            <div style="font-size: 1rem; font-weight: 800; color: #0f766e;">
                                Rp {{ number_format($userPrice, 0, ',', '.') }}
                            </div>
                            <span style="font-size: 0.68rem; background: #ccfbf1; color: #115e59; padding: 2px 6px; border-radius: 4px; font-weight: 700;">
                                Civitas
                            </span>
                        @else
                            <div style="font-size: 1rem; font-weight: 800; color: #0f766e;">
                                Rp {{ number_format($p->price, 0, ',', '.') }}
                            </div>
                            <div style="font-size: 0.72rem; color: var(--muted);">/ {{ $p->uom }}</div>
                        @endif
                    </div>
                </div>

                @if ($p->price_bulk && $p->bulk_min)
                    <div style="font-size: 0.73rem; background: #f8fafc; border: 1px dashed #cbd5e1; padding: 5px 8px; border-radius: 6px; color: #475569;">
                        Grosir &ge; {{ $p->bulk_min }} {{ $p->uom }}: <b>Rp {{ number_format($p->price_bulk, 0, ',', '.') }}</b>/{{ $p->uom }} (Diskon Partai Besar)
                    </div>
                @endif

                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #f1f5f9; padding-top: 10px; margin-top: 2px;">
                    <div style="font-size: 0.8rem; font-weight: 600; color: #475569;">
                        Jumlah Porsi / Box:
                    </div>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <button type="button" class="btn-qty" onclick="changeQty('fp_{{ $p->id }}', -5)">-</button>
                        <input type="number"
                               name="products[{{ $p->id }}]"
                               id="fp_{{ $p->id }}"
                               value="{{ old('products.' . $p->id, 0) }}"
                               min="0"
                               max="{{ $p->stock }}"
                               data-price-normal="{{ $p->price }}"
                               data-price-civitas="{{ $p->price_civitas ?? $p->price }}"
                               data-price-bulk="{{ $p->price_bulk ?? $p->price }}"
                               data-bulk-min="{{ $p->bulk_min ?? 9999 }}"
                               data-is-civitas="{{ $user->isCivitas() ? '1' : '0' }}"
                               oninput="calculateFoodTotal()"
                               style="width: 55px; text-align: center; padding: 6px; border: 1px solid #cbd5e1; border-radius: 8px; font-weight: 700; margin-top:0;">
                        <button type="button" class="btn-qty" onclick="changeQty('fp_{{ $p->id }}', 5)">+</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    </div>

    <div class="unit-sticky-col">
    {{-- 5. LOKASI PENGANTARAN --}}
    <div class="card" style="margin-bottom: 20px;">
        <div style="font-weight: 700; font-size: 0.95rem; margin-bottom: 12px; color:#0f172a;">
            4. Lokasi & PIC Pengantaran Acara
        </div>

        <label for="ship_name">Nama PIC / Penanggung Jawab Acara *</label>
        <input type="text" name="ship_name" id="ship_name" required value="{{ old('ship_name', $savedAddress?->receiver ?? $user->name) }}" placeholder="Contoh: Panitia Div. Konsumsi BEM">

        <label for="ship_phone">No. WhatsApp PIC Acara *</label>
        <input type="tel" name="ship_phone" id="ship_phone" required value="{{ old('ship_phone', $savedAddress?->phone ?? $user->phone) }}" placeholder="081234567890">

        <label for="ship_addr">Lokasi Gedung / Ruangan Acara *</label>
        <input type="text" name="ship_addr" id="ship_addr" required value="{{ old('ship_addr', $savedAddress?->detail ?? '') }}" placeholder="Contoh: Gedung Auditorium Menoreh / Ruang Sidang Utama Lt. 3">

        <label for="note">Catatan Tambahan untuk Tim Dapur</label>
        <input type="text" name="note" id="note" value="{{ old('note') }}" placeholder="Contoh: Tolong sertakan taplak meja prasmanan & tissue tambahan">
    </div>

    {{-- 6. RINGKASAN & SUBMIT --}}
    <div class="card" style="background: #ffffff; border-top: 3px solid var(--brand); padding: 18px;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 6px; font-size: 0.9rem;">
            <span style="color: var(--muted);">Total Porsi Dipesan:</span>
            <span id="food_summary_items" style="font-weight: 600;">0 porsi</span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 6px; font-size: 0.9rem;">
            <span style="color: var(--muted);">Biaya Kirim & Setting Acara:</span>
            <span style="font-weight: 700; color: #16a34a;">GRATIS</span>
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px dashed #e2e8f0; padding-top: 10px; margin-top: 8px;">
            <span style="font-weight: 700; font-size: 1rem; color: #0f172a;">Total Tagihan:</span>
            <span id="food_summary_total" style="font-weight: 800; font-size: 1.25rem; color: #0f766e;">Rp 0</span>
        </div>

        <button type="submit" class="btn" style="margin-top: 14px; padding: 14px; font-size: 0.95rem;">
            Jadwalkan Katering Sekarang
        </button>
    </div>
    </div>
</form>

<style>
    .btn-qty {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        background: #f8fafc;
        color: #0f172a;
        font-weight: 800;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        padding: 0;
        margin: 0;
    }
</style>

<script>
    function runBudgetCalc() {
        const budget = parseInt(document.getElementById('calc_budget').value) || 0;
        const pax = parseInt(document.getElementById('calc_pax').value) || 0;
        const resultBox = document.getElementById('calc_result');

        if (budget > 0 && pax > 0) {
            const perPerson = Math.floor(budget / pax);
            let recommendation = '';

            if (perPerson >= 45000) {
                recommendation = '🎉 <b>Rekomendasi Terbaik:</b> Paket Katering Prasmanan Lengkap (Pilihan 5 lauk + dessert es buah)';
            } else if (perPerson >= 25000) {
                recommendation = '🍱 <b>Rekomendasi Terbaik:</b> Nasi Kotak Ayam Bakar Menoreh / Nasi Rendang Unwahas';
            } else if (perPerson >= 16000) {
                recommendation = '🧁 <b>Rekomendasi Terbaik:</b> Paket Snack Box Premium VIP (4 macam kue + jus)';
            } else if (perPerson >= 10000) {
                recommendation = '📦 <b>Rekomendasi Terbaik:</b> Paket Snack Box Regular (3 macam kue + air minum)';
            } else {
                recommendation = '⚠️ Alokasi di bawah Rp 10.000/orang. Disarankan menambah anggaran agar mencukupi paket snack box.';
            }

            resultBox.innerHTML = `
                <div>Alokasi: <b>Rp ${perPerson.toLocaleString('id-ID')}</b> / orang untuk <b>${pax}</b> orang.</div>
                <div style="margin-top:4px;">${recommendation}</div>
            `;
            resultBox.style.display = 'block';
        } else {
            resultBox.style.display = 'none';
        }
    }

    function changeQty(inputId, delta) {
        const input = document.getElementById(inputId);
        let val = parseInt(input.value) || 0;
        val = Math.max(0, val + delta);
        input.value = val;
        calculateFoodTotal();
    }

    function calculateFoodTotal() {
        const inputs = document.querySelectorAll('input[id^="fp_"]');
        let total = 0;
        let totalItems = 0;

        inputs.forEach(input => {
            const qty = parseInt(input.value) || 0;
            if (qty > 0) {
                totalItems += qty;
                const normal = parseInt(input.dataset.priceNormal);
                const civitas = parseInt(input.dataset.priceCivitas);
                const bulk = parseInt(input.dataset.priceBulk);
                const bulkMin = parseInt(input.dataset.bulkMin);
                const isCivitas = input.dataset.isCivitas === '1';

                let unitPrice = normal;
                if (isCivitas && civitas < unitPrice) {
                    unitPrice = civitas;
                }
                if (qty >= bulkMin && bulk < unitPrice) {
                    unitPrice = bulk;
                }

                total += (unitPrice * qty);
            }
        });

        document.getElementById('food_summary_items').innerText = totalItems + ' porsi';
        document.getElementById('food_summary_total').innerText = 'Rp ' + total.toLocaleString('id-ID');
    }

    document.addEventListener('DOMContentLoaded', () => {
        calculateFoodTotal();
    });
</script>
@endsection
