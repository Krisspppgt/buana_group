<?php

namespace App\Filament\Resources\Quotes;

use App\Enums\OrderStatus;
use App\Enums\ProductionStage;
use App\Enums\QuoteStatus;
use App\Enums\Unit;
use App\Filament\Resources\Quotes\Pages\ManageQuotes;
use App\Models\Order;
use App\Models\ProductionLog;
use App\Models\Quote;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
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

class QuoteResource extends Resource
{
    protected static ?string $model = Quote::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedScissors;

    protected static ?string $navigationLabel = 'Kuotasi Konveksi';

    protected static ?string $modelLabel = 'Kuotasi Konveksi';

    protected static ?string $pluralModelLabel = 'Kuotasi Konveksi';

    protected static string|UnitEnum|null $navigationGroup = 'Operasional & Transaksi';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'item';

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::whereIn('status', [QuoteStatus::Submitted, QuoteStatus::Offered])->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Pemesan')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('item')
                    ->label('Item & Jenis Kain')
                    ->required()
                    ->maxLength(50),

                TextInput::make('qty')
                    ->label('Jumlah (Pcs)')
                    ->numeric()
                    ->minValue(12)
                    ->required(),

                TextInput::make('sizes')
                    ->label('Rincian Ukuran & Catatan')
                    ->required()
                    ->maxLength(100),

                TextInput::make('est_price')
                    ->label('Estimasi Harga Sistem (Rp)')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),

                TextInput::make('final_price')
                    ->label('Harga Final Disetujui (Rp)')
                    ->numeric()
                    ->prefix('Rp'),

                Select::make('status')
                    ->label('Status Kuotasi')
                    ->options([
                        QuoteStatus::Submitted->value => QuoteStatus::Submitted->label(),
                        QuoteStatus::Offered->value => QuoteStatus::Offered->label(),
                        QuoteStatus::Approved->value => QuoteStatus::Approved->label(),
                        QuoteStatus::Rejected->value => QuoteStatus::Rejected->label(),
                    ])
                    ->default(QuoteStatus::Submitted->value)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('item')
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->prefix('#')
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->description(fn (Quote $record): string => $record->user?->phone ?? '-'),

                TextColumn::make('item')
                    ->label('Spesifikasi Pakaian')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn (Quote $record): string => "Ukuran: {$record->sizes}"),

                TextColumn::make('qty')
                    ->label('Jumlah')
                    ->suffix(' pcs')
                    ->sortable(),

                TextColumn::make('est_price')
                    ->label('Estimasi')
                    ->money('IDR', locale: 'id'),

                TextColumn::make('final_price')
                    ->label('Harga Final')
                    ->money('IDR', locale: 'id')
                    ->placeholder('Belum ditentukan')
                    ->weight('bold'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (QuoteStatus $state): string => $state->label())
                    ->color(fn (QuoteStatus $state): string => match ($state) {
                        QuoteStatus::Submitted => 'warning',
                        QuoteStatus::Offered => 'info',
                        QuoteStatus::Approved => 'success',
                        QuoteStatus::Rejected => 'danger',
                    }),

                TextColumn::make('order.code')
                    ->label('Order Produksi')
                    ->badge()
                    ->color('success')
                    ->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Kuotasi')
                    ->options([
                        QuoteStatus::Submitted->value => QuoteStatus::Submitted->label(),
                        QuoteStatus::Offered->value => QuoteStatus::Offered->label(),
                        QuoteStatus::Approved->value => QuoteStatus::Approved->label(),
                        QuoteStatus::Rejected->value => QuoteStatus::Rejected->label(),
                    ]),
            ])
            ->recordActions([
                Action::make('approveAndCreateOrder')
                    ->label('Setujui & Produksi')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn (Quote $record): bool => $record->order_id === null && $record->status !== QuoteStatus::Rejected)
                    ->schema([
                        TextInput::make('final_price')
                            ->label('Harga Final Disepakati (Rp)')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(fn (Quote $record): int => (int) ($record->final_price ?? $record->est_price))
                            ->required(),
                        TextInput::make('ship_addr')
                            ->label('Alamat Pengiriman / Fakultas')
                            ->default('Kampus UNWAHAS Menoreh Tengah X No. 22')
                            ->required()
                            ->maxLength(150),
                        TextInput::make('initial_note')
                            ->label('Catatan Awal Produksi')
                            ->default('Kuotasi disetujui, menunggu persiapan bahan kain & benang.')
                            ->required()
                            ->maxLength(80),
                    ])
                    ->action(function (Quote $record, array $data): void {
                        DB::transaction(function () use ($record, $data): void {
                            $finalPrice = (int) $data['final_price'];
                            $user = $record->user;

                            $order = Order::create([
                                'code' => Order::newCode(),
                                'user_id' => $record->user_id,
                                'unit' => Unit::Konveksi,
                                'status' => OrderStatus::Processing,
                                'subtotal' => $finalPrice,
                                'ship_fee' => 0,
                                'total' => $finalPrice,
                                'ship_name' => $user?->name ?? 'Pelanggan Konveksi',
                                'ship_phone' => $user?->phone ?? '081234567890',
                                'ship_addr' => $data['ship_addr'],
                                'note' => substr("Konveksi #{$record->id}: {$record->item} ({$record->qty} pcs)", 0, 100),
                                'send_at' => now()->addDays(14),
                            ]);

                            ProductionLog::create([
                                'order_id' => $order->id,
                                'stage' => ProductionStage::WaitingMaterial,
                                'note' => $data['initial_note'],
                            ]);

                            $record->update([
                                'final_price' => $finalPrice,
                                'status' => QuoteStatus::Approved,
                                'order_id' => $order->id,
                            ]);

                            Notification::make()
                                ->title('Order Produksi Konveksi Dibuat')
                                ->body("Kuotasi #{$record->id} disetujui dan terhubung ke Order {$order->code}.")
                                ->success()
                                ->send();
                        });
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
            'index' => ManageQuotes::route('/'),
        ];
    }
}
