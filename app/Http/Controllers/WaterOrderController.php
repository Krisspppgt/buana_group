<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\Unit;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WaterOrderController extends Controller
{
    /**
     * Menampilkan katalog Air Mineral Unwahas, form pemesanan, dan opsi langganan/bulk.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $products = Product::where('unit', Unit::Air)
            ->where('is_active', true)
            ->get();

        $savedAddress = $user->addresses()->where('is_main', true)->first()
            ?? $user->addresses()->latest('id')->first();

        return view('water.index', compact('products', 'user', 'savedAddress'));
    }

    /**
     * Menyimpan pesanan air mineral baru (insidental, langganan, atau bulk).
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'order_type' => ['required', 'in:regular,subscription,bulk'],
            'products' => ['required', 'array'],
            'products.*' => ['nullable', 'integer', 'min:0'],
            'ship_name' => ['required', 'string', 'max:40'],
            'ship_phone' => ['required', 'regex:/^08\d{8,12}$/'],
            'ship_addr' => ['required', 'string', 'max:150'],
            'note' => ['nullable', 'string', 'max:100'],
            'subscription_cycle' => ['nullable', 'string', 'max:30'],
            'event_date' => ['nullable', 'date'],
        ]);

        // Filter produk yang qty > 0
        $selectedItems = array_filter($data['products'], fn ($qty) => (int) $qty > 0);

        if (empty($selectedItems)) {
            return back()->withErrors(['products' => 'Pilih minimal satu produk dengan jumlah lebih dari 0.'])->withInput();
        }

        $order = DB::transaction(function () use ($user, $data, $selectedItems) {
            $productModels = Product::whereIn('id', array_keys($selectedItems))->get()->keyBy('id');

            $subtotal = 0;
            $itemsData = [];
            $isBulkOrder = $data['order_type'] === 'bulk';

            foreach ($selectedItems as $productId => $qty) {
                $product = $productModels->get($productId);
                if (! $product) {
                    continue;
                }

                $qty = (int) $qty;
                if ($qty >= ($product->bulk_min ?: 10)) {
                    $isBulkOrder = true;
                }

                $unitPrice = $product->priceFor($user, $qty);
                $subtotal += ($unitPrice * $qty);

                $itemsData[] = [
                    'product_id' => $product->id,
                    'qty' => $qty,
                    'price' => $unitPrice,
                ];
            }

            // Keterangan khusus jika langganan atau B2B Bulk Order
            $notePrefix = '';
            if ($data['order_type'] === 'subscription') {
                $cycle = $data['subscription_cycle'] ?? 'Mingguan';
                $notePrefix = "[Langganan Rutin: {$cycle}] ";
            } elseif ($data['order_type'] === 'bulk' || $isBulkOrder) {
                $eventDate = $data['event_date'] ? " (Tgl Acara: {$data['event_date']})" : '';
                $notePrefix = "[B2B Bulk Order Acara Kampus{$eventDate}] ";
            }

            $order = Order::create([
                'code' => Order::newCode(),
                'user_id' => $user->id,
                'unit' => Unit::Air,
                'status' => $isBulkOrder ? OrderStatus::WaitingConfirmation : OrderStatus::Pending,
                'subtotal' => $subtotal,
                'ship_fee' => 0, // Bebas ongkir area kampus & mitra
                'total' => $subtotal,
                'ship_name' => $data['ship_name'],
                'ship_phone' => $data['ship_phone'],
                'ship_addr' => $data['ship_addr'],
                'note' => substr($notePrefix.($data['note'] ?? ''), 0, 100),
                'send_at' => now(),
            ]);

            foreach ($itemsData as $item) {
                $order->items()->create($item);
            }

            return $order;
        });

        if ($order->status === OrderStatus::WaitingConfirmation) {
            return redirect()->route('order.waiting-confirmation', $order->code)
                ->with('info', 'Pesanan borongan Air Mineral berhasil diajukan! Silakan tunggu konfirmasi admin sebelum melakukan pembayaran.');
        }

        return redirect()->route('payment.checkout', $order->code)
            ->with('success', 'Pesanan Air Mineral berhasil dibuat! Silakan selesaikan pembayaran.');
    }

    /**
     * Menampilkan detail pesanan & simulasi pelacakan logistik armada live.
     */
    public function show(Request $request, string $code): View
    {
        $user = $request->user();

        $order = Order::with(['items.product', 'user'])
            ->where('code', $code)
            ->when(! $user->isAdmin() && ! $user->isStaff(), fn ($q) => $q->where('user_id', $user->id))
            ->firstOrFail();

        return view('water.track', compact('order'));
    }

    /**
     * Update status pengiriman armada logistik oleh Kurir/Admin atau konfirmasi terima oleh Pelanggan.
     */
    public function updateStatus(Request $request, string $code): RedirectResponse
    {
        $user = $request->user();

        $order = Order::where('code', $code)
            ->where('unit', Unit::Air)
            ->firstOrFail();

        if (! $user->isStaff() && $order->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah status pesanan ini.');
        }

        $data = $request->validate([
            'status' => ['required', 'integer', 'in:2,3,4'],
        ]);

        $newStatus = OrderStatus::from((int) $data['status']);

        if (! $user->isStaff() && $newStatus !== OrderStatus::Done) {
            abort(403, 'Pelanggan hanya dapat melakukan konfirmasi pesanan selesai diterima.');
        }

        $order->update(['status' => $newStatus]);

        return back()->with('success', "Status pengiriman armada berhasil diperbarui ke: {$newStatus->label()}.");
    }

    /**
     * Menampilkan riwayat pesanan air mineral pengguna.
     */
    public function history(Request $request): View
    {
        $user = $request->user();
        $orders = Order::with('items.product')
            ->where('user_id', $user->id)
            ->where('unit', Unit::Air)
            ->latest()
            ->paginate(10);

        return view('water.history', compact('orders'));
    }
}
