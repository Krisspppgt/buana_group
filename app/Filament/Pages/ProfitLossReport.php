<?php

namespace App\Filament\Pages;

use App\Enums\OrderStatus;
use App\Enums\Unit;
use App\Models\Expense;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ticket;
use App\Models\TourBooking;
use App\Services\XlsxExportService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;
use UnitEnum;

class ProfitLossReport extends Page
{
    protected string $view = 'filament.pages.profit-loss-report';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBarSquare;

    protected static ?string $navigationLabel = 'Laporan Laba & Rugi';

    protected static ?string $title = 'Laporan Keuntungan & Kerugian (Laba/Rugi)';

    protected static string|UnitEnum|null $navigationGroup = 'Laporan & Keuangan';

    protected static ?int $navigationSort = 3;

    public string $startDate = '';

    public string $endDate = '';

    public string $unitFilter = 'all';

    public function mount(): void
    {
        $this->startDate = now()->startOfMonth()->toDateString();
        $this->endDate = now()->endOfMonth()->toDateString();
    }

    public function setPreset(string $preset): void
    {
        match ($preset) {
            'today' => [
                $this->startDate = now()->toDateString(),
                $this->endDate = now()->toDateString(),
            ],
            'this_month' => [
                $this->startDate = now()->startOfMonth()->toDateString(),
                $this->endDate = now()->endOfMonth()->toDateString(),
            ],
            'last_30_days' => [
                $this->startDate = now()->subDays(29)->toDateString(),
                $this->endDate = now()->toDateString(),
            ],
            'this_year' => [
                $this->startDate = now()->startOfYear()->toDateString(),
                $this->endDate = now()->endOfYear()->toDateString(),
            ],
            default => null,
        };
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportExcel')
                ->label('Export Excel (.xlsx)')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(fn (): StreamedResponse => $this->exportExcel()),
        ];
    }

    /**
     * Menghitung ringkasan Laba & Rugi berdasarkan periode dan filter unit usaha.
     *
     * @return array{
     *     start: Carbon,
     *     end: Carbon,
     *     order_revenue: int,
     *     tour_revenue: int,
     *     ticket_revenue: int,
     *     total_revenue: int,
     *     total_cogs: int,
     *     gross_profit: int,
     *     gross_margin: float,
     *     expenses_by_category: array<string, int>,
     *     total_expenses: int,
     *     net_profit: int,
     *     unit_breakdown: list<array{unit: string, revenue: int, cogs: int, expenses: int, net: int}>,
     * }
     */
    public function getReportData(): array
    {
        $start = Carbon::parse($this->startDate ?: now()->startOfMonth()->toDateString())->startOfDay();
        $end = Carbon::parse($this->endDate ?: now()->endOfMonth()->toDateString())->endOfDay();
        $selectedUnit = $this->unitFilter !== 'all' ? Unit::tryFrom((int) $this->unitFilter) : null;

        $orderQuery = Order::query()
            ->where('status', '!=', OrderStatus::Cancelled)
            ->whereBetween('created_at', [$start, $end]);

        if ($selectedUnit) {
            $orderQuery->where('unit', $selectedUnit);
        }

        $orderRevenue = (int) $orderQuery->sum('total');

        $cogsQuery = OrderItem::query()
            ->whereHas('order', function ($query) use ($start, $end, $selectedUnit): void {
                $query->where('status', '!=', OrderStatus::Cancelled)
                    ->whereBetween('created_at', [$start, $end]);

                if ($selectedUnit) {
                    $query->where('unit', $selectedUnit);
                }
            });

        $totalCogs = (int) $cogsQuery->selectRaw('COALESCE(SUM(qty * cost_price), 0) as total_cogs')->value('total_cogs');

        $tourRevenue = 0;
        if (! $selectedUnit || $selectedUnit === Unit::Tour) {
            $tourRevenue = (int) TourBooking::query()
                ->where('status', '!=', 9)
                ->whereBetween('created_at', [$start, $end])
                ->sum('paid_amount');
        }

        $ticketRevenue = 0;
        if (! $selectedUnit || $selectedUnit === Unit::Ticketing) {
            $ticketRevenue = (int) Ticket::query()
                ->where('status', '!=', 2)
                ->whereBetween('created_at', [$start, $end])
                ->sum('price');
        }

        $totalRevenue = $orderRevenue + $tourRevenue + $ticketRevenue;
        $grossProfit = $totalRevenue - $totalCogs;
        $grossMargin = $totalRevenue > 0 ? round(($grossProfit / $totalRevenue) * 100, 1) : 0.0;

        $expenseQuery = Expense::query()
            ->whereBetween('incurred_at', [$start->toDateString(), $end->toDateString()]);

        if ($selectedUnit) {
            $expenseQuery->where('unit', $selectedUnit);
        }

        $expenseRows = $expenseQuery
            ->selectRaw('category, COALESCE(SUM(amount), 0) as total')
            ->groupBy('category')
            ->pluck('total', 'category')
            ->map(fn ($v): int => (int) $v)
            ->all();

        $totalExpenses = array_sum($expenseRows);
        $netProfit = $grossProfit - $totalExpenses;

        $unitBreakdown = [];
        $unitsToShow = $selectedUnit ? [$selectedUnit] : Unit::cases();

        foreach ($unitsToShow as $unit) {
            $uRevenue = match ($unit) {
                Unit::Tour => (int) TourBooking::where('status', '!=', 9)->whereBetween('created_at', [$start, $end])->sum('paid_amount'),
                Unit::Ticketing => (int) Ticket::where('status', '!=', 2)->whereBetween('created_at', [$start, $end])->sum('price'),
                default => (int) Order::where('status', '!=', OrderStatus::Cancelled)->where('unit', $unit)->whereBetween('created_at', [$start, $end])->sum('total'),
            };

            $uCogs = in_array($unit, [Unit::Air, Unit::Food, Unit::Konveksi], true)
                ? (int) OrderItem::whereHas('order', fn ($q) => $q->where('status', '!=', OrderStatus::Cancelled)->where('unit', $unit)->whereBetween('created_at', [$start, $end]))
                    ->selectRaw('COALESCE(SUM(qty * cost_price), 0) as total_cogs')
                    ->value('total_cogs')
                : 0;

            $uExpenses = (int) Expense::where('unit', $unit)
                ->whereBetween('incurred_at', [$start->toDateString(), $end->toDateString()])
                ->sum('amount');

            $unitBreakdown[] = [
                'unit' => $unit->label(),
                'revenue' => $uRevenue,
                'cogs' => $uCogs,
                'expenses' => $uExpenses,
                'net' => $uRevenue - $uCogs - $uExpenses,
            ];
        }

        return [
            'start' => $start,
            'end' => $end,
            'order_revenue' => $orderRevenue,
            'tour_revenue' => $tourRevenue,
            'ticket_revenue' => $ticketRevenue,
            'total_revenue' => $totalRevenue,
            'total_cogs' => $totalCogs,
            'gross_profit' => $grossProfit,
            'gross_margin' => $grossMargin,
            'expenses_by_category' => $expenseRows,
            'total_expenses' => $totalExpenses,
            'net_profit' => $netProfit,
            'unit_breakdown' => $unitBreakdown,
        ];
    }

    public function exportExcel(): StreamedResponse
    {
        $payload = $this->buildExportPayload();

        return app(XlsxExportService::class)->download(
            filename: 'laporan-laba-rugi-'.now()->format('Y-m-d').'.xlsx',
            sheetTitle: 'Laba & Rugi',
            reportTitle: 'Laporan Keuntungan & Kerugian (Laba / Rugi)',
            metadata: $payload['metadata'],
            headers: $payload['headers'],
            rows: $payload['rows'],
        );
    }

    /**
     * @return array{
     *     metadata: array<string, string>,
     *     headers: list<string>,
     *     rows: list<list<string|int|float|null>>,
     * }
     */
    private function buildExportPayload(): array
    {
        $data = $this->getReportData();
        $unitLabel = $this->unitFilter !== 'all'
            ? (Unit::tryFrom((int) $this->unitFilter)?->label() ?? 'Semua Unit Usaha')
            : 'Semua Unit Usaha';

        $rows = [
            ['I. PENDAPATAN USAHA', 'Penjualan Produk (Air, Food, Konveksi)', $data['order_revenue'], 'Order non-batal'],
            ['I. PENDAPATAN USAHA', 'Pendapatan Tour & Travel', $data['tour_revenue'], 'Pembayaran masuk'],
            ['I. PENDAPATAN USAHA', 'Pendapatan Ticketing', $data['ticket_revenue'], 'Tiket non-batal'],
            ['I. PENDAPATAN USAHA', 'TOTAL PENDAPATAN USAHA', $data['total_revenue'], ''],
            [],
            ['II. HPP & LABA KOTOR', 'Harga Pokok Penjualan (HPP)', -$data['total_cogs'], 'Modal produk terjual'],
            ['II. HPP & LABA KOTOR', 'LABA KOTOR (GROSS PROFIT)', $data['gross_profit'], 'Margin '.$data['gross_margin'].'%'],
            [],
        ];

        if (empty($data['expenses_by_category'])) {
            $rows[] = ['III. BIAYA & KERUGIAN', 'Belum ada catatan biaya operasional', 0, '-'];
        } else {
            foreach ($data['expenses_by_category'] as $cat => $amount) {
                $label = Expense::categories()[$cat] ?? ucfirst($cat);
                $rows[] = ['III. BIAYA & KERUGIAN', $label, -$amount, ''];
            }
        }

        $rows[] = ['III. BIAYA & KERUGIAN', 'TOTAL BIAYA OPERASIONAL & KERUGIAN', -$data['total_expenses'], ''];
        $rows[] = [];

        $netLabel = $data['net_profit'] >= 0 ? 'LABA BERSIH (NET PROFIT)' : 'RUGI BERSIH (NET LOSS)';
        $rows[] = ['IV. HASIL AKHIR', $netLabel, $data['net_profit'], 'Laba Kotor - Biaya Operasional'];
        $rows[] = [];
        $rows[] = ['V. RINGKASAN PER UNIT USAHA', 'Omzet (Rp)', 'HPP (Rp)', 'Net (Rp)'];

        foreach ($data['unit_breakdown'] as $unitRow) {
            $rows[] = [
                $unitRow['unit'],
                $unitRow['revenue'],
                $unitRow['cogs'],
                $unitRow['net'],
            ];
        }

        return [
            'metadata' => [
                'Periode Laporan' => $data['start']->format('d/m/Y').' s/d '.$data['end']->format('d/m/Y'),
                'Filter Unit Usaha' => $unitLabel,
            ],
            'headers' => ['Seksi', 'Komponen Keuangan', 'Nominal (Rp)', 'Keterangan'],
            'rows' => $rows,
        ];
    }
}
