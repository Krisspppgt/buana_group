<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Enums\Unit;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\TourBooking;
use Filament\Widgets\ChartWidget;

class RevenueByUnitChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected static bool $isLazy = false;

    protected ?string $heading = 'Omzet per Unit Usaha PT Buana Sejahtera (Rp)';

    protected ?string $description = 'Perbandingan kontribusi pendapatan dari 5 unit bisnis utama';

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $airRevenue = (int) Order::where('unit', Unit::Air)
            ->where('status', '!=', OrderStatus::Cancelled)
            ->sum('total');

        $foodRevenue = (int) Order::where('unit', Unit::Food)
            ->where('status', '!=', OrderStatus::Cancelled)
            ->sum('total');

        $konveksiRevenue = (int) Order::where('unit', Unit::Konveksi)
            ->where('status', '!=', OrderStatus::Cancelled)
            ->sum('total');

        $tourRevenue = (int) TourBooking::where('status', '!=', 9)
            ->sum('paid_amount');

        $ticketRevenue = (int) Ticket::where('status', '!=', 2)
            ->sum('price');

        return [
            'datasets' => [
                [
                    'label' => 'Realisasi Omzet (Rp)',
                    'data' => [
                        $airRevenue,
                        $foodRevenue,
                        $konveksiRevenue,
                        $tourRevenue,
                        $ticketRevenue,
                    ],
                    'backgroundColor' => [
                        'rgba(14, 165, 233, 0.85)',  // Air Mineral
                        'rgba(245, 158, 11, 0.85)',  // Food Services
                        'rgba(236, 72, 153, 0.85)',  // Konveksi
                        'rgba(16, 185, 129, 0.85)',  // Tour & Travel
                        'rgba(139, 92, 246, 0.85)',  // Ticketing
                    ],
                    'borderColor' => [
                        '#0284c7',
                        '#d97706',
                        '#db2777',
                        '#059669',
                        '#7c3aed',
                    ],
                    'borderWidth' => 1,
                    'borderRadius' => 8,
                ],
            ],
            'labels' => [
                'Air Mineral Unwahas',
                'Buana Food Services',
                'Buana Konveksi',
                'Buana Tour & Travel',
                'Buana Ticketing',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
