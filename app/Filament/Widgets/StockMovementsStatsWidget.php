<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\StockMovementsReport;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StockMovementsStatsWidget extends StatsOverviewWidget
{
    protected static bool $isDiscovered = false;

    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $summary = app(StockMovementsReport::class)->getSummary();

        return [
            Stat::make('Stok Masuk (30 Hari)', '+'.number_format($summary['total_in'], 0, ',', '.'))
                ->description('Restok supplier & pembatalan order')
                ->descriptionIcon('heroicon-m-arrow-down-tray')
                ->color('success'),

            Stat::make('Stok Keluar (30 Hari)', '-'.number_format($summary['total_out'], 0, ',', '.'))
                ->description('Penjualan, rusak, & pengambilan')
                ->descriptionIcon('heroicon-m-arrow-up-tray')
                ->color('danger'),

            Stat::make('Penyesuaian Opname (30 Hari)', number_format($summary['total_adjust'], 0, ',', '.').'x')
                ->description('Aktivitas koreksi saldo stok')
                ->descriptionIcon('heroicon-m-adjustments-horizontal')
                ->color('warning'),

            Stat::make('Produk Stok Menipis (≤ 20)', $summary['low_stock_count'].' Produk')
                ->description(
                    $summary['low_stock_count'] > 0
                        ? 'Perlu segera dijadwalkan restok'
                        : 'Seluruh stok produk aman'
                )
                ->descriptionIcon($summary['low_stock_count'] > 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-badge')
                ->color($summary['low_stock_count'] > 0 ? 'danger' : 'success'),
        ];
    }
}
