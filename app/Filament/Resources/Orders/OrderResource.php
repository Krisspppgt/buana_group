<?php

namespace App\Filament\Resources\Orders;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\ProductionStage;
use App\Enums\Unit;
use App\Filament\Resources\Orders\Pages\ManageOrders;
use App\Models\Order;
use App\Models\Payment;
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
use Illuminate\Support\Facades\DB;
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
            OrderStatus::WaitingConfirmation,
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
                        OrderStatus::WaitingConfirmation->value => OrderStatus::WaitingConfirmation->label(),
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

                TextInput::make('discount_code')
                    ->label('Kode Voucher Diskon')
                    ->maxLength(32)
                    ->disabled()
                    ->dehydrated(false),

                TextInput::make('discount_amount')
                    ->label('Potongan Diskon (Rp)')
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0)
                    ->disabled()
                    ->dehydrated(false),

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
                    ->weight('bold')
                    ->description(fn (Order $record): ?string => $record->discount_amount > 0
                        ? "Voucher {$record->discount_code} (-Rp ".number_format($record->discount_amount, 0, ',', '.').')'
                        : null),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (OrderStatus $state): string => $state->label())
                    ->color(fn (OrderStatus $state): string => match ($state) {
                        OrderStatus::WaitingConfirmation => 'warning',
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
                        OrderStatus::WaitingConfirmation->value => OrderStatus::WaitingConfirmation->label(),
                        OrderStatus::Pending->value => OrderStatus::Pending->label(),
                        OrderStatus::Paid->value => OrderStatus::Paid->label(),
                        OrderStatus::Processing->value => OrderStatus::Processing->label(),
                        OrderStatus::Shipped->value => OrderStatus::Shipped->label(),
                        OrderStatus::Done->value => OrderStatus::Done->label(),
                        OrderStatus::Cancelled->value => OrderStatus::Cancelled->label(),
                    ]),
            ])
            ->recordActions([
                Action::make('confirmOrder')
                    ->label('Konfirmasi & Buka Bayar')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn (Order $record): bool => $record->status === OrderStatus::WaitingConfirmation)
                    ->schema(fn (Order $record): array => [
                        TextInput::make('subtotal')
                            ->label('Subtotal Final (Rp)')
                            ->numeric()
                            ->prefix('Rp')
                            ->default($record->subtotal)
                            ->required()
                            ->minValue(0),
                        TextInput::make('ship_fee')
                            ->label('Biaya Pengiriman Final (Rp)')
                            ->numeric()
                            ->prefix('Rp')
                            ->default($record->ship_fee)
                            ->required()
                            ->minValue(0),
                        Textarea::make('admin_note')
                            ->label('Catatan Admin untuk Pemesan (Opsional)')
                            ->placeholder('Contoh: Kapasitas vendor tersedia, jadwal kirim pukul 08.30 WIB.')
                            ->maxLength(255),
                    ])
                    ->action(function (Order $record, array $data): void {
                        $subtotal = (int) $data['subtotal'];
                        $shipFee = (int) $data['ship_fee'];
                        $total = max(0, ($subtotal + $shipFee) - (int) $record->points_redeemed);

                        $record->update([
                            'subtotal' => $subtotal,
                            'ship_fee' => $shipFee,
                            'total' => $total,
                            'status' => OrderStatus::Pending,
                            'confirmed_at' => now(),
                            'admin_note' => $data['admin_note'] ?? null,
                        ]);

                        Notification::make()
                            ->title('Pesanan Dikonfirmasi')
                            ->body("Pesanan {$record->code} siap dibayar oleh pelanggan (Total: Rp ".number_format($total, 0, ',', '.').').')
                            ->success()
                            ->send();
                    }),

                Action::make('rejectOrder')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Order $record): bool => $record->status === OrderStatus::WaitingConfirmation)
                    ->schema([
                        Textarea::make('admin_note')
                            ->label('Alasan Penolakan')
                            ->placeholder('Contoh: Kapasitas vendor penuh pada tanggal acara tersebut.')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->action(function (Order $record, array $data): void {
                        $record->update([
                            'status' => OrderStatus::Cancelled,
                            'admin_note' => $data['admin_note'],
                        ]);

                        Notification::make()
                            ->title('Pesanan Ditolak')
                            ->body("Pesanan {$record->code} telah dibatalkan.")
                            ->warning()
                            ->send();
                    }),

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

                Action::make('settleTempo')
                    ->label('Lunasi Tempo')
                    ->icon('heroicon-o-banknotes')
                    ->color('warning')
                    ->visible(fn (Order $record): bool => $record->payments()
                        ->where('method', 'tempo_b2b')
                        ->where('status', PaymentStatus::Pending)
                        ->exists())
                    ->requiresConfirmation()
                    ->action(function (Order $record): void {
                        DB::transaction(function () use ($record): void {
                            $pendingTempo = $record->payments()
                                ->where('method', 'tempo_b2b')
                                ->where('status', PaymentStatus::Pending)
                                ->latest('id')
                                ->first();

                            if (! $pendingTempo) {
                                return;
                            }

                            $pendingTempo->update([
                                'status' => PaymentStatus::Success,
                                'paid_at' => now(),
                            ]);

                            $record->awardLoyaltyPoints('Reward Pelunasan Tempo');
                        });

                        Notification::make()
                            ->title('Tagihan Tempo Dilunasi')
                            ->body("Invoice Tempo order {$record->code} telah ditandai lunas dan limit tempo akun dipulihkan.")
                            ->success()
                            ->send();
                    }),

                Action::make('markPaid')
                    ->label('Konfirmasi Lunas')
                    ->icon('heroicon-o-credit-card')
                    ->color('info')
                    ->visible(fn (Order $record): bool => $record->status !== OrderStatus::Cancelled
                        && $record->payments()->where('status', PaymentStatus::Success)->doesntExist()
                        && $record->payments()->where('method', 'tempo_b2b')->where('status', PaymentStatus::Pending)->doesntExist())
                    ->requiresConfirmation()
                    ->action(function (Order $record): void {
                        DB::transaction(function () use ($record): void {
                            $pendingPayment = $record->payments()
                                ->where('status', PaymentStatus::Pending)
                                ->latest('id')
                                ->first();

                            if ($pendingPayment) {
                                $pendingPayment->update([
                                    'status' => PaymentStatus::Success,
                                    'paid_at' => now(),
                                ]);
                            } else {
                                Payment::create([
                                    'order_id' => $record->id,
                                    'ref' => 'MANUAL-'.$record->code,
                                    'method' => 'manual',
                                    'amount' => $record->total,
                                    'status' => PaymentStatus::Success,
                                    'paid_at' => now(),
                                ]);
                            }

                            if ($record->status === OrderStatus::Pending) {
                                $record->status = OrderStatus::Processing;
                                $record->save();
                            }

                            $record->awardLoyaltyPoints();
                        });

                        Notification::make()
                            ->title('Pembayaran Dikonfirmasi Lunas')
                            ->body("Pembayaran order {$record->code} telah ditandai lunas dan poin loyalitas telah diberikan.")
                            ->success()
                            ->send();
                    }),

                Action::make('invoice')
                    ->label('Invoice')
                    ->icon('heroicon-o-document-text')
                    ->color('gray')
                    ->url(fn (Order $record): string => route('payment.invoice', $record->code))
                    ->openUrlInNewTab(),

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
