<?php

namespace App\Filament\Resources\Orders;

use App\Enums\OrderStatus;
use App\Enums\ProductionStage;
use App\Enums\Unit;
use App\Filament\Resources\Orders\Pages\ManageOrders;
use App\Models\Order;
use App\Models\ProductionLog;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    protected static ?string $navigationLabel = 'Pesanan & Logistik';

    protected static ?string $modelLabel = 'Pesanan';

    protected static ?string $pluralModelLabel = 'Pesanan & Logistik';

    protected static string|UnitEnum|null $navigationGroup = 'Operasional & Transaksi';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'code';

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::whereIn('status', [
            OrderStatus::Pending,
            OrderStatus::Paid,
            OrderStatus::Processing,
            OrderStatus::Shipped,
        ])->count();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Kode Pesanan')
                    ->default(fn (): string => Order::newCode())
                    ->required()
                    ->maxLength(16),

                Select::make('user_id')
                    ->label('Pelanggan / Pemesan')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('unit')
                    ->label('Unit Usaha')
                    ->options([
                        Unit::Air->value => Unit::Air->label(),
                        Unit::Food->value => Unit::Food->label(),
                        Unit::Konveksi->value => Unit::Konveksi->label(),
                        Unit::Tour->value => Unit::Tour->label(),
                        Unit::Ticketing->value => Unit::Ticketing->label(),
                    ])
                    ->required(),

                Select::make('status')
                    ->label('Status Pesanan')
                    ->options([
                        OrderStatus::Pending->value => OrderStatus::Pending->label(),
                        OrderStatus::Paid->value => OrderStatus::Paid->label(),
                        OrderStatus::Processing->value => OrderStatus::Processing->label(),
                        OrderStatus::Shipped->value => OrderStatus::Shipped->label(),
                        OrderStatus::Done->value => OrderStatus::Done->label(),
                        OrderStatus::Cancelled->value => OrderStatus::Cancelled->label(),
                    ])
                    ->default(OrderStatus::Processing->value)
                    ->required(),

                TextInput::make('subtotal')
                    ->label('Subtotal (Rp)')
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0)
                    ->required(),

                TextInput::make('ship_fee')
                    ->label('Biaya Kirim (Rp)')
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0)
                    ->required(),

                TextInput::make('total')
                    ->label('Total Tagihan (Rp)')
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0)
                    ->required(),

                DateTimePicker::make('send_at')
                    ->label('Jadwal Pengiriman'),

                TextInput::make('ship_name')
                    ->label('Nama Penerima')
                    ->required()
                    ->maxLength(40),

                TextInput::make('ship_phone')
                    ->label('No. HP Penerima')
                    ->required()
                    ->maxLength(15),

                Textarea::make('ship_addr')
                    ->label('Alamat Pengiriman / Lokasi Gedung')
                    ->required()
                    ->maxLength(150)
                    ->columnSpanFull(),

                Textarea::make('note')
                    ->label('Catatan / Rundown / Langganan')
                    ->maxLength(100)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('code')
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('code')
                    ->label('Kode Order')
                    ->searchable()
                    ->sortable()
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
                    ->label('Pemesan / Penerima')
                    ->searchable()
                    ->description(fn (Order $record): string => "{$record->ship_name} • {$record->ship_addr}"),

                TextColumn::make('note')
                    ->label('Keterangan / Rundown')
                    ->limit(38)
                    ->placeholder('-'),

                TextColumn::make('total')
                    ->label('Total Tagihan')
                    ->money('IDR', locale: 'id')
                    ->sortable()
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

                TextColumn::make('send_at')
                    ->label('Jadwal Kirim')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('unit')
                    ->label('Unit Usaha')
                    ->options([
                        Unit::Air->value => Unit::Air->label(),
                        Unit::Food->value => Unit::Food->label(),
                        Unit::Konveksi->value => Unit::Konveksi->label(),
                    ]),
                SelectFilter::make('status')
                    ->label('Status Pesanan')
                    ->options([
                        OrderStatus::Pending->value => OrderStatus::Pending->label(),
                        OrderStatus::Paid->value => OrderStatus::Paid->label(),
                        OrderStatus::Processing->value => OrderStatus::Processing->label(),
                        OrderStatus::Shipped->value => OrderStatus::Shipped->label(),
                        OrderStatus::Done->value => OrderStatus::Done->label(),
                        OrderStatus::Cancelled->value => OrderStatus::Cancelled->label(),
                    ]),
            ])
            ->recordActions([
                Action::make('updateProduction')
                    ->label('Update Produksi')
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->color('info')
                    ->visible(fn (Order $record): bool => $record->unit === Unit::Konveksi)
                    ->schema([
                        Select::make('stage')
                            ->label('Tahap Pengerjaan Konveksi')
                            ->options([
                                ProductionStage::WaitingMaterial->value => ProductionStage::WaitingMaterial->label(),
                                ProductionStage::Cutting->value => ProductionStage::Cutting->label(),
                                ProductionStage::Sewing->value => ProductionStage::Sewing->label(),
                                ProductionStage::Printing->value => ProductionStage::Printing->label(),
                                ProductionStage::Finishing->value => ProductionStage::Finishing->label(),
                                ProductionStage::ReadyToShip->value => ProductionStage::ReadyToShip->label(),
                            ])
                            ->required(),
                        TextInput::make('note')
                            ->label('Catatan Progres Produksi')
                            ->placeholder('Contoh: Proses bordir logo Unwahas selesai 100%')
                            ->maxLength(80)
                            ->required(),
                    ])
                    ->action(function (Order $record, array $data): void {
                        $stage = ProductionStage::from((int) $data['stage']);

                        ProductionLog::create([
                            'order_id' => $record->id,
                            'stage' => $stage,
                            'note' => $data['note'],
                        ]);

                        if ($stage === ProductionStage::ReadyToShip) {
                            $record->update(['status' => OrderStatus::Shipped]);
                        }

                        Notification::make()
                            ->title('Progres Produksi Diperbarui')
                            ->body("Tahap produksi order {$record->code} diperbarui ke: {$stage->label()}.")
                            ->success()
                            ->send();
                    }),

                Action::make('markDone')
                    ->label('Selesaikan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Order $record): bool => ! in_array($record->status, [OrderStatus::Done, OrderStatus::Cancelled], true))
                    ->requiresConfirmation()
                    ->action(function (Order $record): void {
                        $record->update(['status' => OrderStatus::Done]);

                        Notification::make()
                            ->title('Pesanan Selesai')
                            ->body("Pesanan {$record->code} telah ditandai selesai.")
                            ->success()
                            ->send();
                    }),

                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageOrders::route('/'),
        ];
    }
}
