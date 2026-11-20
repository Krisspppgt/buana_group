<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\Unit;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\TourBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class OrderHistoryController extends Controller
{
    /**
     * Menampilkan 1 halaman Riwayat Pesanan Terpadu lintas 5 unit usaha Buana Group.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $unitFilter = (string) $request->query('unit', 'all');
        $statusFilter = (string) $request->query('status', 'all');

        $records = new Collection;

        // 1. Unit Air Mineral, Food Services, & Konveksi (tabel orders)
        if (in_array($unitFilter, ['all', 'air', 'food', 'konveksi'], true)) {
            $unitEnum = match ($unitFilter) {
                'air' => Unit::Air,
                'food' => Unit::Food,
                'konveksi' => Unit::Konveksi,
                default => null,
            };

            $orders = Order::with(['items.product', 'payments'])
                ->where('user_id', $user->id)
                ->when($unitEnum !== null, fn ($q) => $q->where('unit', $unitEnum))
                ->latest('id')
                ->get();

            foreach ($orders as $order) {
                $records->push($this->mapOrder($order));
            }
        }

        // 2. Unit Tour & Travel (tabel tour_bookings)
        if (in_array($unitFilter, ['all', 'tour'], true)) {
            $bookings = TourBooking::with('tourPackage')
                ->where('user_id', $user->id)
                ->latest('id')
                ->get();

            foreach ($bookings as $booking) {
                $records->push($this->mapTourBooking($booking));
            }
        }

        // 3. Unit Ticketing (tabel tickets)
        if (in_array($unitFilter, ['all', 'ticketing'], true)) {
            $tickets = Ticket::where('user_id', $user->id)
                ->latest('id')
                ->get();

            foreach ($tickets as $ticket) {
                $records->push($this->mapTicket($ticket));
            }
        }

        $sorted = $records->sortByDesc('created_at')->values();

        $counts = [
            'all' => $sorted->count(),
            'waiting' => $sorted->where('status_group', 'waiting')->count(),
            'unpaid' => $sorted->where('status_group', 'unpaid')->count(),
            'active' => $sorted->where('status_group', 'active')->count(),
            'done' => $sorted->where('status_group', 'done')->count(),
        ];

        $items = $statusFilter === 'all'
            ? $sorted
            : $sorted->where('status_group', $statusFilter)->values();

        return view('order.history', compact('items', 'unitFilter', 'statusFilter', 'counts'));
    }

    /**
     * @return array<string, mixed>
     */
    private function mapOrder(Order $order): array
    {
        $paidPayment = $order->payments->firstWhere('status', PaymentStatus::Success);
        $pendingTempo = $order->payments
            ->where('method', 'tempo_b2b')
            ->firstWhere('status', PaymentStatus::Pending);

        $firstItem = $order->items->first();
        $itemSummary = $firstItem
            ? ($firstItem->product?->name ?? 'Produk Buana').($order->items->count() > 1 ? ' +'.($order->items->count() - 1).' lainnya' : " ({$firstItem->qty} ".($firstItem->product?->uom ?? 'unit').')')
            : ($order->note ?: 'Pesanan '.$order->unit->label());

        $statusGroup = match ($order->status) {
            OrderStatus::WaitingConfirmation => 'waiting',
            OrderStatus::Pending => 'unpaid',
            OrderStatus::Done, OrderStatus::Cancelled => 'done',
            default => 'active',
        };

        $badgeColor = match ($order->status) {
            OrderStatus::WaitingConfirmation => 'amber',
            OrderStatus::Pending => 'orange',
            OrderStatus::Done => 'emerald',
            OrderStatus::Cancelled => 'red',
            default => 'blue',
        };

        $primaryUrl = match ($order->status) {
            OrderStatus::WaitingConfirmation => route('order.waiting-confirmation', $order->code),
            OrderStatus::Pending => route('payment.checkout', $order->code),
            default => match ($order->unit) {
                Unit::Air => route('water.show', $order->code),
                Unit::Food => route('food.show', $order->code),
                Unit::Konveksi => route('konveksi.tracking', $order->code),
                default => route('payment.checkout', $order->code),
            },
        };

        $primaryLabel = match ($order->status) {
            OrderStatus::WaitingConfirmation => 'Cek Konfirmasi Admin',
            OrderStatus::Pending => 'Bayar Sekarang',
            default => 'Detail & Lacak',
        };

        $invoiceUrl = ($paidPayment || $pendingTempo || $order->status === OrderStatus::Done)
            ? route('payment.invoice', $order->code)
            : null;

        return [
            'code' => $order->code,
            'unit_key' => match ($order->unit) {
                Unit::Air => 'air',
                Unit::Food => 'food',
                Unit::Konveksi => 'konveksi',
                default => 'all',
            },
            'unit_label' => $order->unit->label(),
            'title' => $itemSummary,
            'subtitle' => $order->ship_addr,
            'amount' => (int) $order->total,
            'status_label' => $order->status->label(),
            'status_group' => $statusGroup,
            'badge_color' => $badgeColor,
            'primary_url' => $primaryUrl,
            'primary_label' => $primaryLabel,
            'invoice_url' => $invoiceUrl,
            'created_at' => $order->created_at,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function mapTourBooking(TourBooking $booking): array
    {
        $statusGroup = match ((int) $booking->status) {
            0 => 'waiting',
            1 => 'unpaid',
            2, 3, 9 => 'done',
            default => 'active',
        };

        $badgeColor = match ((int) $booking->status) {
            0 => 'amber',
            1 => 'blue',
            2, 3 => 'emerald',
            9 => 'red',
            default => 'blue',
        };

        return [
            'code' => $booking->code,
            'unit_key' => 'tour',
            'unit_label' => 'Buana Tour & Travel',
            'title' => $booking->tourPackage?->title ?? 'Paket Tour & Umrah',
            'subtitle' => "{$booking->total_pax} Jamaah / Peserta • {$booking->coordinator_name}",
            'amount' => (int) $booking->total_amount,
            'status_label' => $booking->status_label,
            'status_group' => $statusGroup,
            'badge_color' => $badgeColor,
            'primary_url' => route('tour.bookings.show', $booking->code),
            'primary_label' => 'Detail & Cicilan',
            'invoice_url' => null,
            'created_at' => $booking->created_at,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function mapTicket(Ticket $ticket): array
    {
        $statusGroup = match ((int) $ticket->status) {
            1, 2 => 'done',
            default => 'active',
        };

        $badgeColor = match ((int) $ticket->status) {
            0 => 'emerald',
            1 => 'blue',
            2 => 'red',
            default => 'blue',
        };

        return [
            'code' => $ticket->code,
            'unit_key' => 'ticketing',
            'unit_label' => 'Buana Ticketing',
            'title' => $ticket->title,
            'subtitle' => "{$ticket->passenger_name}".($ticket->seat_info ? " • {$ticket->seat_info}" : ''),
            'amount' => (int) $ticket->price,
            'status_label' => $ticket->status_label,
            'status_group' => $statusGroup,
            'badge_color' => $badgeColor,
            'primary_url' => route('ticketing.show', $ticket->code),
            'primary_label' => 'E-Boarding Pass',
            'invoice_url' => null,
            'created_at' => $ticket->created_at,
        ];
    }
}
