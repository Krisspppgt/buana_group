<?php

namespace App\Filament\Pages;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\Unit;
use App\Filament\Widgets\B2bReceivablesStatsWidget;
use App\Models\Payment;
use App\Services\XlsxExportService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use UnitEnum;

class B2bReceivablesReport extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'filament.pages.b2b-receivables-report';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentCurrencyDollar;

    protected static ?string $navigationLabel = 'Laporan Piutang B2B';

    protected static ?string $title = 'Laporan Piutang Metode B2B (Tempo)';

    protected static string|UnitEnum|null $navigationGroup = 'Laporan & Keuangan';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        $count = Payment::query()
            ->where('method', 'tempo_b2b')
            ->where('status', PaymentStatus::Pending)
            ->whereHas('order', fn (Builder $q) => $q->where('status', '!=', OrderStatus::Cancelled))
            ->count();

        return $count > 0 ? (string) $count : null;
    }

    /**
     * Ringkasan piutang B2B untuk kartu KPI di bagian atas laporan.
     *
     * @return array{active_amount: int, active_count: int, overdue_amount: int, overdue_count: int, settled_amount: int}
     */
    public function getSummary(): array
    {
        $baseQuery = Payment::query()
            ->where('method', 'tempo_b2b')
            ->whereHas('order', fn (Builder $q) => $q->where('status', '!=', OrderStatus::Cancelled));

        $pendingPayments = (clone $baseQuery)
            ->where('status', PaymentStatus::Pending)
            ->get();

        $overduePayments = $pendingPayments->filter(function (Payment $payment): bool {
            $dueDate = $payment->due_date ?? $payment->created_at?->copy()->addDays(30);

            return $dueDate !== null && $dueDate->isPast();
        });

        $settledAmount = (int) (clone $baseQuery)
            ->where('status', PaymentStatus::Success)
            ->sum('amount');

        return [
            'active_amount' => (int) $pendingPayments->sum('amount'),
            'active_count' => $pendingPayments->count(),
            'overdue_amount' => (int) $overduePayments->sum('amount'),
            'overdue_count' => $overduePayments->count(),
            'settled_amount' => $settledAmount,
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            B2bReceivablesStatsWidget::class,
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
            filename: 'laporan-piutang-b2b-'.now()->format('Y-m-d').'.xlsx',
            sheetTitle: 'Piutang B2B',
            reportTitle: 'Laporan Piutang Usaha B2B (Metode Tempo)',
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
            $query = Payment::query()
                ->with(['order.user'])
                ->where('method', 'tempo_b2b')
                ->whereHas('order', fn (Builder $q) => $q->where('status', '!=', OrderStatus::Cancelled));
        }

        $records = (clone $query)
            ->with(['order.user'])
            ->latest('created_at')
            ->get();

        $summary = $this->getSummary();

        $headers = [
            'No',
            'Ref Invoice',
            'Kode Order',
            'Mitra / Pemesan',
            'Instansi / Perusahaan',
            'Unit Usaha',
            'Nominal Tagihan (Rp)',
            'Tanggal Terbit',
            'Jatuh Tempo',
            'Keterangan Aging',
            'Status Pembayaran',
            'Tanggal Lunas',
        ];

        $rows = [];
        $totalNominal = 0;

        foreach ($records as $index => $payment) {
            $dueDate = $payment->due_date ?? $payment->created_at?->copy()->addDays(30);
            $totalNominal += (int) $payment->amount;

            $rows[] = [
                $index + 1,
                $payment->ref,
                $payment->order?->code ?? '-',
                $payment->order?->user?->name ?? '-',
                $payment->order?->user?->company_name ?: '-',
                $payment->order?->unit?->label() ?? '-',
                (int) $payment->amount,
                $payment->created_at?->format('d/m/Y H:i') ?? '-',
                $dueDate?->format('d/m/Y') ?? '-',
                $this->formatAgingNote($payment, $dueDate),
                $payment->status->label(),
                $payment->paid_at?->format('d/m/Y H:i') ?? '-',
            ];
        }

        return [
            'metadata' => [
                'Total Piutang Aktif (Rp)' => number_format($summary['active_amount'], 0, ',', '.'),
                'Melewati Jatuh Tempo (Rp)' => number_format($summary['overdue_amount'], 0, ',', '.'),
                'Sudah Lunas (Rp)' => number_format($summary['settled_amount'], 0, ',', '.'),
                'Jumlah Data Diekspor' => $records->count().' invoice',
            ],
            'headers' => $headers,
            'rows' => $rows,
            'totalRow' => [
                '',
                'TOTAL',
                '',
                '',
                '',
                $records->count().' Invoice',
                $totalNominal,
                '',
                '',
                '',
                '',
                '',
            ],
        ];
    }

    private function formatAgingNote(Payment $payment, ?Carbon $dueDate): string
    {
        if ($payment->status === PaymentStatus::Success) {
            return 'Lunas';
        }

        if (! $dueDate) {
            return '-';
        }

        if ($dueDate->isPast()) {
            $days = (int) max(1, $dueDate->diffInDays(now()));

            return "Terlambat {$days} hari";
        }

        $days = (int) now()->diffInDays($dueDate);

        return "Sisa {$days} hari";
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Payment::query()
                    ->with(['order.user'])
                    ->where('method', 'tempo_b2b')
                    ->whereHas('order', fn (Builder $q) => $q->where('status', '!=', OrderStatus::Cancelled))
            )
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('ref')
                    ->label('Ref Invoice')
                    ->searchable()
                    ->copyable()
                    ->weight('bold'),

                TextColumn::make('order.code')
                    ->label('Kode Order')
                    ->searchable()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('order.user.name')
                    ->label('Mitra / Pemesan')
                    ->searchable()
                    ->description(fn (Payment $record): string => $record->order?->user?->company_name ?: ($record->order?->user?->email ?? '-')),

                TextColumn::make('order.unit')
                    ->label('Unit Usaha')
                    ->badge()
                    ->formatStateUsing(fn (?Unit $state): string => $state?->label() ?? '-'),

                TextColumn::make('amount')
                    ->label('Nominal Tagihan')
                    ->money('IDR', locale: 'id')
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('created_at')
                    ->label('Tgl Terbit')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('due_date')
                    ->label('Jatuh Tempo')
                    ->state(fn (Payment $record): ?Carbon => $record->due_date ?? $record->created_at?->copy()->addDays(30))
                    ->date('d M Y')
                    ->description(function (Payment $record): string {
                        if ($record->status === PaymentStatus::Success) {
                            return 'Lunas '.($record->paid_at?->format('d M Y') ?? '');
                        }

                        $dueDate = $record->due_date ?? $record->created_at?->copy()->addDays(30);
                        if (! $dueDate) {
                            return '-';
                        }

                        if ($dueDate->isPast()) {
                            $days = (int) max(1, $dueDate->diffInDays(now()));

                            return "Terlambat {$days} hari";
                        }

                        $days = (int) now()->diffInDays($dueDate);

                        return "Sisa {$days} hari";
                    })
                    ->color(function (Payment $record): string {
                        if ($record->status === PaymentStatus::Success) {
                            return 'success';
                        }

                        $dueDate = $record->due_date ?? $record->created_at?->copy()->addDays(30);

                        return ($dueDate && $dueDate->isPast()) ? 'danger' : 'warning';
                    }),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (PaymentStatus $state): string => $state->label())
                    ->color(fn (PaymentStatus $state): string => match ($state) {
                        PaymentStatus::Pending => 'warning',
                        PaymentStatus::Success => 'success',
                        PaymentStatus::Failed => 'danger',
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Pembayaran')
                    ->options([
                        PaymentStatus::Pending->value => 'Belum Lunas (Pending)',
                        PaymentStatus::Success->value => 'Sudah Lunas',
                        PaymentStatus::Failed->value => 'Gagal / Batal',
                    ]),

                SelectFilter::make('aging')
                    ->label('Status Jatuh Tempo')
                    ->options([
                        'overdue' => 'Sudah Jatuh Tempo (Overdue)',
                        'upcoming' => 'Belum Jatuh Tempo',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return match ($data['value'] ?? null) {
                            'overdue' => $query
                                ->where('status', PaymentStatus::Pending)
                                ->where(function (Builder $sub): void {
                                    $sub->where('due_date', '<', now())
                                        ->orWhere(function (Builder $fallback): void {
                                            $fallback->whereNull('due_date')
                                                ->where('created_at', '<', now()->subDays(30));
                                        });
                                }),
                            'upcoming' => $query
                                ->where('status', PaymentStatus::Pending)
                                ->where(function (Builder $sub): void {
                                    $sub->where('due_date', '>=', now())
                                        ->orWhere(function (Builder $fallback): void {
                                            $fallback->whereNull('due_date')
                                                ->where('created_at', '>=', now()->subDays(30));
                                        });
                                }),
                            default => $query,
                        };
                    }),

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

                        return $query->whereHas('order', fn (Builder $q) => $q->where('unit', $data['value']));
                    }),
            ])
            ->recordActions([
                Action::make('settle')
                    ->label('Tandai Lunas')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Pelunasan Piutang B2B')
                    ->modalDescription(fn (Payment $record): string => "Tandai invoice {$record->ref} senilai Rp ".number_format($record->amount, 0, ',', '.').' sebagai lunas?')
                    ->visible(fn (Payment $record): bool => $record->status === PaymentStatus::Pending)
                    ->action(function (Payment $record): void {
                        DB::transaction(function () use ($record): void {
                            $record->update([
                                'status' => PaymentStatus::Success,
                                'paid_at' => now(),
                            ]);

                            $order = $record->order;
                            if ($order) {
                                if ($order->status === OrderStatus::Pending) {
                                    $order->update(['status' => OrderStatus::Paid]);
                                }
                                $order->awardLoyaltyPoints('Reward Pelunasan Tempo');
                            }
                        });

                        Notification::make()
                            ->title('Piutang B2B Dilunasi')
                            ->body("Invoice {$record->ref} telah ditandai lunas dan limit tempo mitra dipulihkan.")
                            ->success()
                            ->send();
                    }),
            ]);
    }
}
