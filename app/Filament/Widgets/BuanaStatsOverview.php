<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\QuoteStatus;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Quote;
use App\Models\Ticket;
use App\Models\TourBooking;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BuanaStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $orderRevenue = (int) Order::where('status', '!=', OrderStatus::Cancelled)->sum('total');
        $pendingB2bReceivables = (int) Payment::query()
            ->where('method', 'tempo_b2b')
            ->where('status', PaymentStatus::Pending)
            ->whereHas('order', fn ($query) => $query->where('status', '!=', OrderStatus::Cancelled))
            ->sum('amount');
        $realizedOrderRevenue = max(0, $orderRevenue - $pendingB2bReceivables);

        $tourPaidRevenue = (int) TourBooking::where('status', '!=', 9)->sum('paid_amount');
        $tourTotalContract = (int) TourBooking::where('status', '!=', 9)->sum('total_amount');
        $ticketRevenue = (int) Ticket::where('status', '!=', 2)->sum('price');

        $totalRealizedRevenue = $realizedOrderRevenue + $tourPaidRevenue + $ticketRevenue;
        $outstandingInstallment = max(0, $tourTotalContract - $tourPaidRevenue);

        $totalOrders = Order::count();
        $activeOrders = Order::whereIn('status', [
            OrderStatus::Pending,
            OrderStatus::Paid,
            OrderStatus::Processing,
            OrderStatus::Shipped,
        ])->count();

        $activeProducts = Product::where('is_active', true)->count();
        $lowStockProducts = Product::where('is_active', true)->where('stock', '<=', 20)->count();

        $pendingQuotes = Quote::whereIn('status', [QuoteStatus::Submitted, QuoteStatus::Offered])->count();
        $activeToursAndTickets = TourBooking::whereIn('status', [0, 1, 2])->count() + Ticket::where('status', 0)->count();

        $revenueDescriptions = [];
        if ($pendingB2bReceivables > 0) {
            $revenueDescriptions[] = 'Piutang B2B: Rp '.number_format($pendingB2bReceivables, 0, ',', '.');
        }
        if ($outstandingInstallment > 0) {
            $revenueDescriptions[] = 'Piutang Tour: Rp '.number_format($outstandingInstallment, 0, ',', '.');
        }

        return [
            Stat::make('Total Omzet Masuk', 'Rp '.number_format($totalRealizedRevenue, 0, ',', '.'))
                ->description(
                    count($revenueDescriptions) > 0
                        ? implode(' | ', $revenueDescriptions)
                        : 'Seluruh tagihan terkendali'
                )
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->chart([12, 18, 15, 25, 22, 30, 38]),

            Stat::make('Pesanan Unit Usaha', $totalOrders.' Pesanan')
                ->description($activeOrders.' pesanan sedang aktif diproses/dikirim')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary')
                ->chart([5, 8, 12, 10, 14, 16, 20]),

            Stat::make('Status Inventaris Gudang', $activeProducts.' Produk Aktif')
                ->description(
                    $lowStockProducts > 0
                        ? $lowStockProducts.' item stok menipis (≤ 20)'
                        : 'Stok seluruh gudang aman'
                )
                ->descriptionIcon($lowStockProducts > 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-badge')
                ->color($lowStockProducts > 0 ? 'warning' : 'success'),

            Stat::make('Kuotasi & Booking Aktif', $pendingQuotes.' Kuotasi Konveksi')
                ->description($activeToursAndTickets.' Rombongan Tour & E-Ticket aktif')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color('info'),
        ];
    }
}
