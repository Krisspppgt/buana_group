<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\B2bReceivablesReport;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class B2bReceivablesStatsWidget extends StatsOverviewWidget
{
    protected static bool $isDiscovered = false;

    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $summary = app(B2bReceivablesReport::class)->getSummary();

        return [
            Stat::make('Total Piutang B2B Aktif', 'Rp '.number_format($summary['active_amount'], 0, ',', '.'))
                ->description($summary['active_count'].' invoice belum lunas')
                ->descriptionIcon('heroicon-m-clock')
                ->color($summary['active_amount'] > 0 ? 'warning' : 'success'),

            Stat::make('Melewati Jatuh Tempo', 'Rp '.number_format($summary['overdue_amount'], 0, ',', '.'))
                ->description(
                    $summary['overdue_count'] > 0
                        ? $summary['overdue_count'].' invoice perlu penagihan'
                        : 'Tidak ada tagihan terlambat'
                )
                ->descriptionIcon($summary['overdue_count'] > 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-circle')
                ->color($summary['overdue_count'] > 0 ? 'danger' : 'success'),

            Stat::make('Piutang B2B Sudah Lunas', 'Rp '.number_format($summary['settled_amount'], 0, ',', '.'))
                ->description('Akumulasi pembayaran tempo masuk')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Ketentuan Kredit B2B', 'TOP 30 Hari')
                ->description('Limit kredit otomatis pulih saat lunas')
                ->descriptionIcon('heroicon-m-shield-check')
                ->color('info'),
        ];
    }
}
