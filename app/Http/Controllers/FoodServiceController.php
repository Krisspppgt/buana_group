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

class FoodServiceController extends Controller
{
    /**
     * Menampilkan katalog Buana Food Services, budget calculator, dan form rundown.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $products = Product::where('unit', Unit::Food)
            ->where('is_active', true)
            ->get();

        $savedAddress = $user->addresses()->where('is_main', true)->first()
            ?? $user->addresses()->latest('id')->first();

        return view('food.index', compact('products', 'user', 'savedAddress'));
    }

    /**
     * Menyimpan pesanan katering / snack box dengan jadwal rundown acara.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'products' => ['required', 'array'],
            'products.*' => ['nullable', 'integer', 'min:0'],
            'event_name' => ['required', 'string', 'max:50'],
            'send_at' => ['required', 'date'],
            'diet_pref' => ['required', 'string', 'max:30'],
            'rundown_panitia' => ['nullable', 'string', 'max:20'],
            'rundown_peserta' => ['nullable', 'string', 'max:20'],
            'rundown_vip' => ['nullable', 'string', 'max:20'],
            'ship_name' => ['required', 'string', 'max:40'],
            'ship_phone' => ['required', 'regex:/^08\d{8,12}$/'],
            'ship_addr' => ['required', 'string', 'max:150'],
            'note' => ['nullable', 'string', 'max:100'],
        ]);

        $selectedItems = array_filter($data['products'], fn ($qty) => (int) $qty > 0);

        if (empty($selectedItems)) {
            return back()->withErrors(['products' => 'Pilih minimal satu paket menu dengan jumlah lebih dari 0.'])->withInput();
        }

        $order = DB::transaction(function () use ($user, $data, $selectedItems) {
            $productModels = Product::whereIn('id', array_keys($selectedItems))->get()->keyBy('id');

            $subtotal = 0;
            $itemsData = [];
            $totalQty = 0;
            $isBulkOrder = false;

            foreach ($selectedItems as $productId => $qty) {
                $product = $productModels->get($productId);
                if (! $product) {
                    continue;
                }

                $qty = (int) $qty;
                $totalQty += $qty;
                if ($qty >= ($product->bulk_min ?: 30)) {
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

            if ($totalQty >= 30) {
                $isBulkOrder = true;
            }

            // Gabungkan catatan rundown acara dan preferensi diet
            $rundownNote = "[Acara: {$data['event_name']} | Diet: {$data['diet_pref']}]";
            if (! empty($data['rundown_panitia']) || ! empty($data['rundown_peserta']) || ! empty($data['rundown_vip'])) {
                $rundownNote .= " [Rundown: Pnt={$data['rundown_panitia']}, Psr={$data['rundown_peserta']}, VIP={$data['rundown_vip']}]";
            }
            if (! empty($data['note'])) {
                $rundownNote .= " {$data['note']}";
            }

            $order = Order::create([
                'code' => Order::newCode(),
                'user_id' => $user->id,
                'unit' => Unit::Food,
                'status' => $isBulkOrder ? OrderStatus::WaitingConfirmation : OrderStatus::Pending,
                'subtotal' => $subtotal,
                'ship_fee' => 0,
                'total' => $subtotal,
                'ship_name' => $data['ship_name'],
                'ship_phone' => $data['ship_phone'],
                'ship_addr' => $data['ship_addr'],
                'note' => substr($rundownNote, 0, 100),
                'send_at' => $data['send_at'],
            ]);

            foreach ($itemsData as $item) {
                $order->items()->create($item);
            }

            return $order;
        });

        if ($order->status === OrderStatus::WaitingConfirmation) {
            return redirect()->route('order.waiting-confirmation', $order->code)
                ->with('info', 'Pesanan katering borongan berhasil diajukan! Menunggu pemeriksaan kapasitas vendor & konfirmasi admin sebelum pembayaran.');
        }

        return redirect()->route('payment.checkout', $order->code)
            ->with('success', 'Pesanan katering berhasil dibuat! Silakan selesaikan pembayaran.');
    }

    /**
     * Menampilkan detail pesanan makanan & status pengiriman rundown.
     */
    public function show(Request $request, string $code): View
    {
        $user = $request->user();

        $order = Order::with(['items.product', 'user'])
            ->where('code', $code)
            ->when(! $user->isAdmin() && ! $user->isStaff(), fn ($q) => $q->where('user_id', $user->id))
            ->firstOrFail();

        return view('food.show', compact('order'));
    }

    /**
     * Update status tahapan pengantaran rundown katering.
     */
    public function updateStatus(Request $request, string $code): RedirectResponse
    {
        $user = $request->user();

        $order = Order::where('code', $code)
            ->where('unit', Unit::Food)
            ->firstOrFail();

        if (! $user->isStaff() && $order->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk memperbarui status katering ini.');
        }

        $data = $request->validate([
            'status' => ['required', 'integer', 'in:2,3,4'],
        ]);

        $newStatus = OrderStatus::from((int) $data['status']);

        $order->update(['status' => $newStatus]);

        return back()->with('success', "Status pengantaran katering berhasil diperbarui ke: {$newStatus->label()}.");
    }
}
