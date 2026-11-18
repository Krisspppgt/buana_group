<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Enums\Unit;
use App\Models\Order;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestOrdersWidget extends TableWidget
{
    protected static ?int $sort = 3;

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Pesanan & Transaksi Terbaru';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Order::query()->with('user')->latest('id')->limit(8))
            ->columns([
                TextColumn::make('code')
                    ->label('Kode Order')
                    ->searchable()
                    ->weight('bold')
                    ->copyable(),

                TextColumn::make('unit')
                    ->label('Unit Usaha')
                    ->badge()
                    ->formatStateUsing(fn (Unit $state): string => $state->label())
                    ->color(fn (Unit $state): string => match ($state) {
                        Unit::Air => 'info',
                        Unit::Food => 'warning',
                        Unit::Konveksi => 'danger',
                        Unit::Tour => 'success',
                        Unit::Ticketing => 'primary',
                    }),

                TextColumn::make('user.name')
                    ->label('Pemesan')
                    ->description(fn (Order $record): string => $record->ship_addr ?? '-'),

                TextColumn::make('total')
                    ->label('Total Tagihan')
                    ->money('IDR', locale: 'id')
                    ->weight('bold'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (OrderStatus $state): string => $state->label())
                    ->color(fn (OrderStatus $state): string => match ($state) {
                        OrderStatus::Pending => 'warning',
                        OrderStatus::Paid => 'info',
                        OrderStatus::Processing => 'primary',
                        OrderStatus::Shipped => 'info',
                        OrderStatus::Done => 'success',
                        OrderStatus::Cancelled => 'danger',
                    }),

                TextColumn::make('created_at')
                    ->label('Waktu Pesan')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->paginated(false);
    }
}
