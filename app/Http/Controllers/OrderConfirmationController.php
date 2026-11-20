<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderConfirmationController extends Controller
{
    /**
     * Menampilkan halaman Menunggu Konfirmasi Admin (atau status siap bayar setelah disetujui).
     */
    public function show(Request $request, string $code): View
    {
        $user = $request->user();

        $order = Order::with(['items.product', 'payments', 'user'])
            ->where('code', $code)
            ->when(! $user->isAdmin() && ! $user->isStaff(), fn ($q) => $q->where('user_id', $user->id))
            ->firstOrFail();

        return view('order.waiting-confirmation', compact('order', 'user'));
    }

    /**
     * Membatalkan pesanan borongan selama masih berstatus Menunggu Konfirmasi Admin.
     */
    public function cancel(Request $request, string $code): RedirectResponse
    {
        $user = $request->user();

        $order = Order::where('code', $code)
            ->when(! $user->isAdmin() && ! $user->isStaff(), fn ($q) => $q->where('user_id', $user->id))
            ->firstOrFail();

        if ($order->status !== OrderStatus::WaitingConfirmation) {
            return back()->withErrors([
                'order' => 'Pesanan hanya dapat dibatalkan saat masih menunggu konfirmasi admin.',
            ]);
        }

        $order->update([
            'status' => OrderStatus::Cancelled,
            'admin_note' => 'Dibatalkan oleh pemesan sebelum konfirmasi admin.',
        ]);

        return back()->with('success', "Pesanan #{$order->code} berhasil dibatalkan.");
    }
}
