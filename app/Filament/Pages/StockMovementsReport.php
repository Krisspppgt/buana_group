<?php

namespace App\Filament\Pages;

use App\Enums\StockMovementType;
use App\Enums\Unit;
use App\Filament\Widgets\StockMovementsStatsWidget;
use App\Models\Product;
use App\Models\StockMovement;
use App\Services\XlsxExportService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\StreamedResponse;
use UnitEnum;

class StockMovementsReport extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'filament.pages.stock-movements-report';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowsRightLeft;

    protected static ?string $navigationLabel = 'Laporan Mutasi Stok';

    protected static ?string $title = 'Laporan Stok Masuk & Keluar';

    protected static string|UnitEnum|null $navigationGroup = 'Laporan & Keuangan';

    protected static ?int $navigationSort = 2;

    /**
     * Ringkasan mutasi stok 30 hari terakhir dan status stok menipis.
     *
     * @return array{total_in: int, total_out: int, total_adjust: int, low_stock_count: int}
     */
    public function getSummary(): array
    {
        $since = now()->subDays(30);

        return [
            'total_in' => (int) StockMovement::where('type', StockMovementType::In)->where('created_at', '>=', $since)->sum('qty'),
            'total_out' => (int) StockMovement::where('type', StockMovementType::Out)->where('created_at', '>=', $since)->sum('qty'),
            'total_adjust' => (int) StockMovement::where('type', StockMovementType::Adjustment)->where('created_at', '>=', $since)->count(),
            'low_stock_count' => (int) Product::where('is_active', true)->where('stock', '<=', 20)->count(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            StockMovementsStatsWidget::class,
        ];
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

    public function exportExcel(): StreamedResponse
    {
        $payload = $this->buildExportPayload();

        return app(XlsxExportService::class)->download(
            filename: 'laporan-mutasi-stok-'.now()->format('Y-m-d').'.xlsx',
            sheetTitle: 'Mutasi Stok',
            reportTitle: 'Laporan Mutasi Stok (Masuk & Keluar)',
            metadata: $payload['metadata'],
            headers: $payload['headers'],
            rows: $payload['rows'],
            totalRow: $payload['totalRow'],
        );
    }

    /**
     * @return array{
     *     metadata: array<string, string>,
     *     headers: list<string>,
     *     rows: list<list<string|int|float|null>>,
     *     totalRow: list<string|int|float|null>,
     * }
     */
    private function buildExportPayload(): array
    {
        try {
            $query = $this->getFilteredTableQuery();
        } catch (\Throwable) {
            $query = StockMovement::query()->with(['product', 'order', 'user']);
        }

        $records = (clone $query)
            ->with(['product', 'order', 'user'])
            ->latest('created_at')
            ->get();

        $totalIn = (int) $records->where('type', StockMovementType::In)->sum('qty');
        $totalOut = (int) $records->where('type', StockMovementType::Out)->sum('qty');

        $headers = [
            'No',
            'Waktu',
            'Nama Produk',
            'Unit Usaha',
            'Satuan',
            'Jenis Mutasi',
            'Alasan',
            'Masuk (+)',
            'Keluar (-)',
            'Stok Sebelum',
            'Stok Sesudah',
            'Ref Order',
            'Petugas',
            'Catatan',
        ];

        $rows = [];
        foreach ($records as $index => $row) {
            $qtyIn = $row->type === StockMovementType::In ? (int) $row->qty : 0;
            $qtyOut = $row->type === StockMovementType::Out ? (int) $row->qty : 0;

            $rows[] = [
                $index + 1,
                $row->created_at?->format('d/m/Y H:i') ?? '-',
                $row->product?->name ?? '-',
                $row->product?->unit?->label() ?? '-',
                $row->product?->uom ?? '-',
                $row->type->label(),
                self::reasonLabel($row->reason),
                $qtyIn,
                $qtyOut,
                (int) $row->stock_before,
                (int) $row->stock_after,
                $row->order?->code ?? '-',
                $row->user?->name ?? 'Sistem',
                $row->note ?: '-',
            ];
        }

        return [
            'metadata' => [
                'Total Baris Mutasi' => $records->count().' transaksi',
                'Total Stok Masuk' => '+'.number_format($totalIn, 0, ',', '.'),
                'Total Stok Keluar' => '-'.number_format($totalOut, 0, ',', '.'),
            ],
            'headers' => $headers,
            'rows' => $rows,
            'totalRow' => [
                '',
                'TOTAL MUTASI',
                '',
                '',
                '',
                '',
                '',
                $totalIn,
                $totalOut,
                '',
                '',
                '',
                '',
                '',
            ],
        ];
    }

    public static function reasonLabel(string $reason): string
    {
        return match ($reason) {
            'order' => 'Penjualan Order',
            'order_cancel' => 'Pembatalan Order',
            'restock' => 'Restok Supplier',
            'damaged' => 'Barang Rusak / Expired',
            'manual_out' => 'Pengambilan Internal',
            'opname' => 'Stok Opname',
            default => ucfirst($reason),
        };
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(StockMovement::query()->with(['product', 'order', 'user']))
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('product.name')
                    ->label('Produk')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn (StockMovement $record): string => $record->product?->unit?->label() ?? '-'),

                TextColumn::make('type')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn (StockMovementType $state): string => $state->label())
                    ->color(fn (StockMovementType $state): string => $state->color()),

                TextColumn::make('reason')
                    ->label('Keterangan')
                    ->formatStateUsing(fn (string $state): string => self::reasonLabel($state))
                    ->description(fn (StockMovement $record): ?string => $record->note),

                TextColumn::make('qty')
                    ->label('Jumlah')
                    ->formatStateUsing(function (StockMovement $record): string {
                        $prefix = match ($record->type) {
                            StockMovementType::In => '+',
                            StockMovementType::Out => '-',
                            StockMovementType::Adjustment => '',
                        };

                        return "{$prefix}{$record->qty} ".($record->product?->uom ?? '');
                    })
                    ->weight('bold')
                    ->color(fn (StockMovement $record): string => $record->type->color()),

                TextColumn::make('stock_after')
                    ->label('Saldo Stok')
                    ->formatStateUsing(fn (StockMovement $record): string => "{$record->stock_before} → {$record->stock_after}"),

                TextColumn::make('order.code')
                    ->label('Ref Order')
                    ->badge()
                    ->color('gray')
                    ->placeholder('-'),

                TextColumn::make('user.name')
                    ->label('Oleh')
                    ->placeholder('Sistem'),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Jenis Mutasi')
                    ->options([
                        StockMovementType::In->value => StockMovementType::In->label(),
                        StockMovementType::Out->value => StockMovementType::Out->label(),
                        StockMovementType::Adjustment->value => StockMovementType::Adjustment->label(),
                    ]),

                SelectFilter::make('reason')
                    ->label('Alasan')
                    ->options([
                        'order' => 'Penjualan Order',
                        'order_cancel' => 'Pembatalan Order',
                        'restock' => 'Restok Supplier',
                        'damaged' => 'Barang Rusak / Expired',
                        'manual_out' => 'Pengambilan Internal',
                        'opname' => 'Stok Opname',
                    ]),

                SelectFilter::make('unit')
                    ->label('Unit Usaha')
                    ->options([
                        Unit::Air->value => Unit::Air->label(),
                        Unit::Food->value => Unit::Food->label(),
                        Unit::Konveksi->value => Unit::Konveksi->label(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (empty($data['value'])) {
                            return $query;
                        }

                        return $query->whereHas('product', fn (Builder $q) => $q->where('unit', $data['value']));
                    }),

                Filter::make('created_at')
                    ->schema([
                        DatePicker::make('from')->label('Dari Tanggal'),
                        DatePicker::make('until')->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'] ?? null, fn (Builder $q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['until'] ?? null, fn (Builder $q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
            ]);
    }
}
