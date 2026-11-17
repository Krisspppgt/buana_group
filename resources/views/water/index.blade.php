@extends('layouts.app')
@section('title', 'Pemesanan Air Mineral Unwahas - Buana Group')

@section('content')
<div style="margin-bottom: 16px;">
    <a href="{{ route('home') }}" style="color:var(--muted); text-decoration:none; font-size:0.88rem; display:inline-flex; align-items:center; gap:4px;">
        &larr; Kembali ke Beranda
    </a>
    <div style="display:flex; justify-content:space-between; align-items:center; margin-top:6px;">
        <h1 style="margin:0; font-size:1.25rem;">💧 Air Mineral Unwahas</h1>
        <a href="{{ route('water.history') }}" style="color:var(--brand); text-decoration:none; font-size:0.85rem; font-weight:600;">
            Riwayat Pesanan
        </a>
    </div>
    <p class="sub" style="margin-top:2px;">Pesan galon, botol, dan gelas untuk kebutuhan kantor, kampus, & rumah tangga.</p>
</div>

@if ($user->isCivitas())
    <div class="eco-banner" style="margin-bottom: 16px;">
        <span style="font-size: 1.25rem;">🎓</span>
        <div>
            <b>Tarif Khusus Civitas Unwahas Aktif</b>
            <div style="font-size: 0.8rem; opacity: 0.9;">Kamu mendapatkan potongan harga civitas untuk setiap produk di bawah ini.</div>
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

<form method="POST" action="{{ route('water.store') }}" id="orderForm">
    @csrf

    {{-- 1. PILIH TIPE PESANAN --}}
    <div class="card" style="margin-bottom: 16px; padding: 16px;">
        <div style="font-weight: 700; font-size: 0.95rem; margin-bottom: 10px; color:#0f172a;">
            1. Pilih Jenis Kebutuhan
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px;">
            <label style="margin:0; cursor:pointer;">
                <input type="radio" name="order_type" value="regular" checked onchange="updateOrderMode()" style="display:none;" id="type_regular">
                <div class="type-btn active" id="btn_regular">
                    <div style="font-size:1.2rem;">📦</div>
                    <div style="font-weight:700; font-size:0.82rem; margin-top:4px;">Insidental</div>
                    <div style="font-size:0.7rem; color:var(--muted);">Pesan Langsung</div>
                </div>
            </label>

            <label style="margin:0; cursor:pointer;">
                <input type="radio" name="order_type" value="subscription" onchange="updateOrderMode()" style="display:none;" id="type_sub">
                <div class="type-btn" id="btn_sub">
                    <div style="font-size:1.2rem;">🔄</div>
                    <div style="font-weight:700; font-size:0.82rem; margin-top:4px;">Langganan</div>
                    <div style="font-size:0.7rem; color:var(--muted);">Harian / Mingguan</div>
                </div>
            </label>

            <label style="margin:0; cursor:pointer;">
                <input type="radio" name="order_type" value="bulk" onchange="updateOrderMode()" style="display:none;" id="type_bulk">
                <div class="type-btn" id="btn_bulk">
                    <div style="font-size:1.2rem;">🏢</div>
                    <div style="font-weight:700; font-size:0.82rem; margin-top:4px;">B2B Bulk</div>
                    <div style="font-size:0.7rem; color:var(--muted);">Acara Kampus</div>
                </div>
            </label>
        </div>

        {{-- Opsi Khusus Langganan --}}
        <div id="subscription_options" style="display:none; margin-top:14px; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:12px;">
            <div style="font-size:0.85rem; font-weight:700; color:#166534; margin-bottom:6px;">
                📅 Pilih Frekuensi Pengiriman Rutin:
            </div>
            <select name="subscription_cycle" style="width:100%; padding:10px; border:1px solid #86efac; border-radius:8px; font-size:0.9rem; background:#fff;">
                <option value="Setiap Hari (Senin - Sabtu)">Setiap Hari (Senin - Sabtu) - Kantor / Fakultas</option>
                <option value="2x Seminggu (Senin & Kamis)">2x Seminggu (Senin & Kamis)</option>
                <option value="1x Seminggu (Setiap Senin)" selected>1x Seminggu (Setiap Senin) - Rumah Tangga</option>
                <option value="2 Minggu Sekali">2 Minggu Sekali</option>
            </select>
            <div style="font-size:0.75rem; color:#15803d; margin-top:4px;">
                Armada kurir akan otomatis mengantarkan galon/botol pengganti sesuai jadwal.
            </div>
        </div>

        {{-- Opsi Khusus B2B Bulk Order --}}
        <div id="bulk_options" style="display:none; margin-top:14px; background:#eff6ff; border:1px solid #bfdbfe; border-radius:10px; padding:12px;">
            <div style="font-size:0.85rem; font-weight:700; color:#1e40af; margin-bottom:6px;">
                🎓 Kebutuhan Acara Kampus / Instansi:
            </div>
            <div style="display:grid; grid-template-columns: 1fr; gap:8px;">
                <div>
                    <label style="margin:0 0 4px; font-size:0.8rem; color:#1e3a8a;">Tanggal Pelaksanaan Acara:</label>
                    <input type="date" name="event_date" style="width:100%; padding:9px; border:1px solid #93c5fd; border-radius:8px; font-size:0.9rem;">
                </div>
            </div>
            <div style="font-size:0.75rem; color:#1d4ed8; margin-top:6px;">
                ⚡ <b>Harga Grosir Otomatis:</b> Berlaku diskon harga bulk khusus pemesanan jumlah banyak.
            </div>
        </div>
    </div>

    {{-- 2. KATALOG PRODUK --}}
    <div style="font-weight: 700; font-size: 0.95rem; margin-bottom: 10px; color:#0f172a;">
        2. Pilih Produk Air Minum
    </div>

    <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 20px;">
        @foreach ($products as $p)
            @php
                $userPrice = $p->priceFor($user, 1);
                $isDiscounted = $userPrice < $p->price;
            @endphp
            <div class="card" style="padding: 14px; display: flex; flex-direction: column; gap: 10px;">
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
                                Harga Civitas
                            </span>
                        @else
                            <div style="font-size: 1rem; font-weight: 800; color: #0f766e;">
                                Rp {{ number_format($p->price, 0, ',', '.') }}
                            </div>
                            <div style="font-size: 0.72rem; color: var(--muted);">/ {{ $p->uom }}</div>
                        @endif
                    </div>
                </div>

                {{-- Bulk info bar --}}
                @if ($p->price_bulk && $p->bulk_min)
                    <div style="font-size: 0.73rem; background: #f8fafc; border: 1px dashed #cbd5e1; padding: 4px 8px; border-radius: 6px; color: #475569;">
                        🏷️ Pesan &ge; {{ $p->bulk_min }} {{ $p->uom }}: <b>Rp {{ number_format($p->price_bulk, 0, ',', '.') }}</b>/{{ $p->uom }} (Hemat Grosir)
                    </div>
                @endif

                {{-- Counter QTY --}}
                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #f1f5f9; padding-top: 10px; margin-top: 2px;">
                    <div style="font-size: 0.8rem; font-weight: 600; color: #475569;">
                        Jumlah Pesan:
                    </div>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <button type="button" class="btn-qty" onclick="changeQty('p_{{ $p->id }}', -1)">-</button>
                        <input type="number"
                               name="products[{{ $p->id }}]"
                               id="p_{{ $p->id }}"
                               value="{{ old('products.' . $p->id, 0) }}"
                               min="0"
                               max="{{ $p->stock }}"
                               data-price-normal="{{ $p->price }}"
                               data-price-civitas="{{ $p->price_civitas ?? $p->price }}"
                               data-price-bulk="{{ $p->price_bulk ?? $p->price }}"
                               data-bulk-min="{{ $p->bulk_min ?? 9999 }}"
                               data-is-civitas="{{ $user->isCivitas() ? '1' : '0' }}"
                               oninput="calculateTotal()"
                               style="width: 50px; text-align: center; padding: 6px; border: 1px solid #cbd5e1; border-radius: 8px; font-weight: 700; margin-top:0;">
                        <button type="button" class="btn-qty" onclick="changeQty('p_{{ $p->id }}', 1)">+</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- 3. ALAMAT PENGIRIMAN --}}
    <div class="card" style="margin-bottom: 20px;">
        <div style="font-weight: 700; font-size: 0.95rem; margin-bottom: 12px; color:#0f172a;">
            3. Lokasi & Penerima
        </div>

        <label for="ship_name">Nama Pemesan / Lembaga / Ruangan *</label>
        <input type="text" name="ship_name" id="ship_name" required value="{{ old('ship_name', $savedAddress?->receiver ?? $user->name) }}" placeholder="Contoh: BEM FKIP / Ibu Rahma">

        <label for="ship_phone">Nomor WhatsApp Penerima *</label>
        <input type="tel" name="ship_phone" id="ship_phone" required value="{{ old('ship_phone', $savedAddress?->phone ?? $user->phone) }}" placeholder="081234567890">

        <label for="ship_addr">Alamat Lengkap / Gedung Kampus *</label>
        <input type="text" name="ship_addr" id="ship_addr" required value="{{ old('ship_addr', $savedAddress?->detail ?? '') }}" placeholder="Contoh: Gedung A Lantai 2 R. Dekanat / Jl. Menoreh No. 12">

        <label for="note">Catatan Tambahan untuk Kurir (Opsional)</label>
        <input type="text" name="note" id="note" value="{{ old('note') }}" placeholder="Contoh: Tolong titipkan di meja resepsionis">
    </div>

    {{-- 4. RINGKASAN & TOMBOL SUBMIT --}}
    <div class="card" style="background: #ffffff; border-top: 3px solid var(--brand); padding: 18px;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 6px; font-size: 0.9rem;">
            <span style="color: var(--muted);">Total Item:</span>
            <span id="summary_items" style="font-weight: 600;">0 produk</span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 6px; font-size: 0.9rem;">
            <span style="color: var(--muted);">Ongkos Kirim Armada:</span>
            <span style="font-weight: 700; color: #16a34a;">GRATIS (Kampus & Sekitar)</span>
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px dashed #e2e8f0; padding-top: 10px; margin-top: 8px;">
            <span style="font-weight: 700; font-size: 1rem; color: #0f172a;">Total Tagihan:</span>
            <span id="summary_total" style="font-weight: 800; font-size: 1.25rem; color: #0f766e;">Rp 0</span>
        </div>

        <button type="submit" id="btn_submit" class="btn" style="margin-top: 14px; padding: 14px; font-size: 1rem;">
            🚀 Konfirmasi & Pesan Sekarang
        </button>
    </div>
</form>

<style>
    .type-btn {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 10px 4px;
        text-align: center;
        background: #fff;
        transition: all .15s ease;
    }
    .type-btn.active {
        border-color: #0f766e;
        background: #f0fdfa;
        box-shadow: 0 0 0 1px #0f766e;
    }
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
    .btn-qty:hover { background: #e2e8f0; }
</style>

<script>
    function updateOrderMode() {
        const isReg = document.getElementById('type_regular').checked;
        const isSub = document.getElementById('type_sub').checked;
        const isBulk = document.getElementById('type_bulk').checked;

        document.getElementById('btn_regular').className = 'type-btn ' + (isReg ? 'active' : '');
        document.getElementById('btn_sub').className = 'type-btn ' + (isSub ? 'active' : '');
        document.getElementById('btn_bulk').className = 'type-btn ' + (isBulk ? 'active' : '');

        document.getElementById('subscription_options').style.display = isSub ? 'block' : 'none';
        document.getElementById('bulk_options').style.display = isBulk ? 'block' : 'none';

        calculateTotal();
    }

    function changeQty(inputId, delta) {
        const input = document.getElementById(inputId);
        let val = parseInt(input.value) || 0;
        val = Math.max(0, val + delta);
        input.value = val;
        calculateTotal();
    }

    function calculateTotal() {
        const inputs = document.querySelectorAll('input[id^="p_"]');
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

        document.getElementById('summary_items').innerText = totalItems + ' produk';
        document.getElementById('summary_total').innerText = 'Rp ' + total.toLocaleString('id-ID');
    }

    document.addEventListener('DOMContentLoaded', () => {
        calculateTotal();
    });
</script>
@endsection
