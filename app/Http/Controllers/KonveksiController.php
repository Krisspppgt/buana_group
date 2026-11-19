<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\ProductionStage;
use App\Enums\QuoteStatus;
use App\Enums\Unit;
use App\Models\Order;
use App\Models\ProductionLog;
use App\Models\Quote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class KonveksiController extends Controller
{
    /**
     * Tampilan katalog bahan, portofolio produk, dan form pengajuan kuotasi.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $fabrics = [
            ['name' => 'American Drill 1919', 'type' => 'Kemeja PDH / PDL', 'desc' => 'Tebal, serat rapat, tidak mudah kusut, warna awet.', 'badge' => 'Paling Populer'],
            ['name' => 'Japan Drill Original', 'type' => 'Jas Almamater / PDH', 'desc' => 'Kandungan katun lebih tinggi, adem, permukaan halus & premium.', 'badge' => 'Premium'],
            ['name' => 'Taipan Tropical', 'type' => 'Kemeja Formal / Seragam', 'desc' => 'Sangat adem, lembut, tidak luntur, standar seragam instansi.', 'badge' => 'Eksklusif'],
            ['name' => 'Cotton Combed 24s & 30s', 'type' => 'Kaos Acara / Event', 'desc' => '100% katun murni, daya serap keringat tinggi, lembut di kulit.', 'badge' => 'Favorit Event'],
            ['name' => 'Fleece Cotton & Baby Terry', 'type' => 'Jaket / Hoodie / Varsity', 'desc' => 'Bagian dalam hangat lembut, tekstur luar tebal kokoh.', 'badge' => 'Hangat & Tebal'],
        ];

        $colors = [
            ['name' => 'Hijau Unwahas', 'hex' => '#0f766e'],
            ['name' => 'Navy / Biru Dongker', 'hex' => '#1e3a8a'],
            ['name' => 'Hitam Pekat', 'hex' => '#0f172a'],
            ['name' => 'Marun Elegan', 'hex' => '#881337'],
            ['name' => 'Abu-abu Misty', 'hex' => '#64748b'],
            ['name' => 'Putih Bersih', 'hex' => '#f8fafc'],
            ['name' => 'Khaki / Krem Pemda', 'hex' => '#d97706'],
        ];

        $portfolio = [
            ['title' => 'Jas Almamater Universitas Wahid Hasyim', 'material' => 'Japan Drill + Furing Peles', 'est' => 'Rp 125.000 - 150.000'],
            ['title' => 'Kemeja PDH BEM & Ormawa Kampus', 'material' => 'American Drill + Bordir Komputer', 'est' => 'Rp 95.000 - 120.000'],
            ['title' => 'Seragam PDL Lapangan & KKN', 'material' => 'Ripstop / American Drill', 'est' => 'Rp 110.000 - 135.000'],
            ['title' => 'Kaos Panitia & Seminar Nasional', 'material' => 'Cotton Combed 30s + Sablon DTF', 'est' => 'Rp 45.000 - 65.000'],
            ['title' => 'Jaket Angkatan Varsity / Bomber', 'material' => 'Fleece Tebal + Bordir Handuk', 'est' => 'Rp 145.000 - 185.000'],
        ];

        return view('konveksi.index', compact('fabrics', 'colors', 'portfolio', 'user'));
    }

    /**
     * Menyimpan pengajuan kuotasi & kalkulasi estimasi harga otomatis.
     */
    public function storeQuote(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'item' => ['required', 'string', 'max:50'],
            'material' => ['required', 'string', 'max:50'],
            'qty' => ['required', 'integer', 'min:12'], // Min order 12 pcs
            'size_s' => ['nullable', 'integer', 'min:0'],
            'size_m' => ['nullable', 'integer', 'min:0'],
            'size_l' => ['nullable', 'integer', 'min:0'],
            'size_xl' => ['nullable', 'integer', 'min:0'],
            'size_xxl' => ['nullable', 'integer', 'min:0'],
            'design' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:4096'],
            'notes' => ['nullable', 'string', 'max:150'],
        ]);

        $sizesSummary = sprintf(
            'S:%d, M:%d, L:%d, XL:%d, XXL:%d',
            (int) ($data['size_s'] ?? 0),
            (int) ($data['size_m'] ?? 0),
            (int) ($data['size_l'] ?? 0),
            (int) ($data['size_xl'] ?? 0),
            (int) ($data['size_xxl'] ?? 0)
        );

        // Upload mockup jika ada
        $designPath = null;
        if ($request->hasFile('design')) {
            $designPath = $request->file('design')->store('mockups', 'public');
        }

        // Hitung estimasi harga per pcs
        $qty = (int) $data['qty'];
        $basePrice = match (true) {
            str_contains(strtolower($data['item']), 'kaos') => 55000,
            str_contains(strtolower($data['item']), 'jas') => 135000,
            str_contains(strtolower($data['item']), 'jaket') || str_contains(strtolower($data['item']), 'bomber') => 160000,
            str_contains(strtolower($data['item']), 'pdl') => 120000,
            default => 105000, // PDH / Kemeja standar
        };

        // Diskon kuantitas massal
        if ($qty >= 100) {
            $basePrice = (int) ($basePrice * 0.85); // Diskon 15%
        } elseif ($qty >= 50) {
            $basePrice = (int) ($basePrice * 0.90); // Diskon 10%
        } elseif ($qty >= 24) {
            $basePrice = (int) ($basePrice * 0.95); // Diskon 5%
        }

        // Potongan civitas jika berlaku
        if ($user->isCivitas()) {
            $basePrice = (int) ($basePrice * 0.95); // Tambahan diskon 5% civitas
        }

        $estPriceTotal = $basePrice * $qty;

        $quote = Quote::create([
            'user_id' => $user->id,
            'item' => "{$data['item']} ({$data['material']})",
            'qty' => $qty,
            'sizes' => substr($sizesSummary.(! empty($data['notes']) ? " | {$data['notes']}" : ''), 0, 100),
            'design' => $designPath,
            'est_price' => $estPriceTotal,
            'status' => QuoteStatus::Submitted,
        ]);

        return redirect()->route('konveksi.quotes.show', $quote->id)
            ->with('success', 'Pengajuan kuotasi konveksi berhasil dikirim! Tim sales akan meninjau desain & spesifikasi.');
    }

    /**
     * Menampilkan daftar kuotasi milik pengguna.
     */
    public function quotes(Request $request): View
    {
        $user = $request->user();
        $quotes = Quote::where('user_id', $user->id)
            ->latest('id')
            ->paginate(10);

        return view('konveksi.quotes', compact('quotes'));
    }

    /**
     * Menampilkan detail satu kuotasi penawaran.
     */
    public function showQuote(Request $request, int $id): View
    {
        $user = $request->user();
        $quote = Quote::with('order')
            ->where('id', $id)
            ->when(! $user->isAdmin() && ! $user->isStaff(), fn ($q) => $q->where('user_id', $user->id))
            ->firstOrFail();

        return view('konveksi.quote_detail', compact('quote'));
    }

    /**
     * Menyetujui kuotasi konveksi dan menerbitkan Order Produksi beserta log tahap awal.
     */
    public function approveQuote(Request $request, int $id): RedirectResponse
    {
        $user = $request->user();
        $quote = Quote::with('user')
            ->where('id', $id)
            ->when(! $user->isAdmin() && ! $user->isStaff(), fn ($q) => $q->where('user_id', $user->id))
            ->firstOrFail();

        if ($quote->order_id && $quote->order) {
            return redirect()->route('konveksi.tracking', $quote->order->code);
        }

        $data = $request->validate([
            'ship_addr' => ['required', 'string', 'max:150'],
        ]);

        $order = DB::transaction(function () use ($quote, $data) {
            $agreedPrice = (int) ($quote->final_price ?? $quote->est_price);
            $customer = $quote->user;

            $order = Order::create([
                'code' => Order::newCode(),
                'user_id' => $quote->user_id,
                'unit' => Unit::Konveksi,
                'status' => OrderStatus::Processing,
                'subtotal' => $agreedPrice,
                'ship_fee' => 0,
                'total' => $agreedPrice,
                'ship_name' => $customer?->name ?? 'Pelanggan Konveksi',
                'ship_phone' => $customer?->phone ?? '081234567890',
                'ship_addr' => $data['ship_addr'],
                'note' => substr("Konveksi #{$quote->id}: {$quote->item} ({$quote->qty} pcs)", 0, 100),
                'send_at' => now()->addDays(14),
            ]);

            ProductionLog::create([
                'order_id' => $order->id,
                'stage' => ProductionStage::WaitingMaterial,
                'note' => 'Kuotasi disetujui pemesan. Persiapan gulungan kain & palet warna dimulai.',
            ]);

            $quote->update([
                'final_price' => $agreedPrice,
                'status' => QuoteStatus::Approved,
                'order_id' => $order->id,
            ]);

            return $order;
        });

        return redirect()->route('konveksi.tracking', $order->code)
            ->with('success', 'Kuotasi berhasil disetujui! Pesanan produksi telah diterbitkan dan masuk ke antrean workshop.');
    }

    /**
     * Menampilkan progres pengerjaan produksi konveksi transparan (real-time).
     */
    public function tracking(Request $request, string $code): View
    {
        $user = $request->user();
        $order = Order::with(['productionLogs' => fn ($q) => $q->latest('id'), 'items.product'])
            ->where('code', $code)
            ->where('unit', Unit::Konveksi)
            ->when(! $user->isAdmin() && ! $user->isStaff(), fn ($q) => $q->where('user_id', $user->id))
            ->firstOrFail();

        // Ambil tahap terakhir
        $latestStage = $order->productionLogs->first()?->stage ?? ProductionStage::WaitingMaterial;

        return view('konveksi.tracking', compact('order', 'latestStage'));
    }

    /**
     * Update tahapan produksi konveksi oleh Operator Workshop / Admin.
     */
    public function updateStage(Request $request, string $code): RedirectResponse
    {
        $user = $request->user();

        if (! $user->isStaff()) {
            abort(403, 'Hanya Operator Workshop atau Admin yang dapat memperbarui tahap produksi.');
        }

        $order = Order::where('code', $code)
            ->where('unit', Unit::Konveksi)
            ->firstOrFail();

        $data = $request->validate([
            'stage' => ['required', 'integer', 'in:1,2,3,4,5,6'],
            'note' => ['required', 'string', 'max:80'],
        ]);

        $stage = ProductionStage::from((int) $data['stage']);

        ProductionLog::create([
            'order_id' => $order->id,
            'stage' => $stage,
            'note' => $data['note'],
        ]);

        if ($stage === ProductionStage::ReadyToShip) {
            $order->update(['status' => OrderStatus::Shipped]);
        }

        return back()->with('success', "Tahap produksi berhasil diperbarui ke: {$stage->label()}.");
    }
}
