<?php

namespace App\Filament\Resources\Products;

use App\Enums\Unit;
use App\Filament\Resources\Products\Pages\ManageProducts;
use App\Models\Product;
use App\Services\StockService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    protected static ?string $navigationLabel = 'Produk & Inventaris';

    protected static ?string $modelLabel = 'Produk';

    protected static ?string $pluralModelLabel = 'Produk & Inventaris';

    protected static string|UnitEnum|null $navigationGroup = 'Katalog & Inventaris';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('is_active', true)->count();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('unit')
                    ->label('Unit Usaha')
                    ->options([
                        Unit::Air->value => Unit::Air->label(),
                        Unit::Food->value => Unit::Food->label(),
                        Unit::Konveksi->value => Unit::Konveksi->label(),
                    ])
                    ->required(),

                TextInput::make('name')
                    ->label('Nama Produk / Paket')
                    ->required()
                    ->maxLength(60),

                TextInput::make('uom')
                    ->label('Satuan (UOM)')
                    ->placeholder('galon / dus / box / pcs')
                    ->required()
                    ->maxLength(15),

                TextInput::make('stock')
                    ->label('Stok Gudang')
                    ->numeric()
                    ->default(100)
                    ->minValue(0)
                    ->required(),

                TextInput::make('price')
                    ->label('Harga Umum (Rp)')
                    ->numeric()
                    ->prefix('Rp')
                    ->minValue(0)
                    ->required(),

                TextInput::make('cost_price')
                    ->label('Harga Modal / HPP (Rp)')
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0)
                    ->minValue(0)
                    ->helperText('Digunakan untuk menghitung laba kotor pada Laporan Laba & Rugi.'),

                TextInput::make('price_civitas')
                    ->label('Harga Khusus Civitas (Rp)')
                    ->numeric()
                    ->prefix('Rp')
                    ->minValue(0),

                TextInput::make('price_bulk')
                    ->label('Harga Grosir / B2B (Rp)')
                    ->numeric()
                    ->prefix('Rp')
                    ->minValue(0),

                TextInput::make('bulk_min')
                    ->label('Minimal Qty Grosir')
                    ->numeric()
                    ->default(0)
                    ->minValue(0),

                Textarea::make('info')
                    ->label('Deskripsi / Spesifikasi Singkat')
                    ->maxLength(120)
                    ->columnSpanFull(),

                Toggle::make('is_active')
                    ->label('Tampilkan di Katalog Aktif')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Product $record): string => $record->info ?? '-'),

                TextColumn::make('unit')
                    ->label('Unit Usaha')
                    ->badge()
                    ->formatStateUsing(fn (Unit $state): string => $state->label())
                    ->color(fn (Unit $state): string => match ($state) {
                        Unit::Air => 'info',
                        Unit::Food => 'warning',
                        Unit::Konveksi => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('price')
                    ->label('Harga Umum')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                TextColumn::make('cost_price')
                    ->label('HPP / Modal')
                    ->money('IDR', locale: 'id')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('price_civitas')
                    ->label('Harga Civitas')
                    ->money('IDR', locale: 'id')
                    ->placeholder('-'),

                TextColumn::make('price_bulk')
                    ->label('Harga B2B')
                    ->money('IDR', locale: 'id')
                    ->description(fn (Product $record): string => $record->bulk_min > 0 ? "Min. {$record->bulk_min} {$record->uom}" : '-')
                    ->placeholder('-'),

                TextColumn::make('stock')
                    ->label('Stok')
                    ->sortable()
                    ->badge()
                    ->formatStateUsing(fn (Product $record): string => "{$record->stock} {$record->uom}")
                    ->color(fn (int $state): string => $state <= 20 ? 'danger' : ($state <= 50 ? 'warning' : 'success')),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('unit')
                    ->label('Filter Unit Usaha')
                    ->options([
                        Unit::Air->value => Unit::Air->label(),
                        Unit::Food->value => Unit::Food->label(),
                        Unit::Konveksi->value => Unit::Konveksi->label(),
                    ]),
            ])
            ->recordActions([
                Action::make('restock')
                    ->label('Tambah Stok')
                    ->icon('heroicon-o-plus-circle')
                    ->color('success')
                    ->schema([
                        TextInput::make('added_stock')
                            ->label('Jumlah Stok Masuk')
                            ->numeric()
                            ->default(50)
                            ->minValue(1)
                            ->required(),
                        TextInput::make('note')
                            ->label('Catatan / Sumber Supplier')
                            ->placeholder('Contoh: Restok dari pabrik / PO #123')
                            ->maxLength(200),
                    ])
                    ->action(function (Product $record, array $data): void {
                        app(StockService::class)->stockIn(
                            product: $record,
                            qty: (int) $data['added_stock'],
                            reason: 'restock',
                            userId: auth()->id(),
                            note: $data['note'] ?? 'Restok manual admin',
                        );

                        Notification::make()
                            ->title('Stok Gudang Diperbarui')
                            ->body("Stok {$record->name} berhasil ditambah sebanyak {$data['added_stock']} {$record->uom}.")
                            ->success()
                            ->send();
                    }),
                Action::make('adjustStock')
                    ->label('Opname / Keluar')
                    ->icon('heroicon-o-minus-circle')
                    ->color('warning')
                    ->schema([
                        Select::make('reason')
                            ->label('Jenis Penyesuaian')
                            ->options([
                                'damaged' => 'Barang Rusak / Kedaluwarsa (Stok Keluar)',
                                'manual_out' => 'Pengambilan Internal / Sample (Stok Keluar)',
                                'opname' => 'Stok Opname (Set Saldo Akhir)',
                            ])
                            ->default('damaged')
                            ->required(),
                        TextInput::make('qty')
                            ->label('Jumlah / Saldo Baru')
                            ->numeric()
                            ->minValue(0)
                            ->required(),
                        TextInput::make('note')
                            ->label('Keterangan')
                            ->maxLength(200),
                    ])
                    ->action(function (Product $record, array $data): void {
                        $stockService = app(StockService::class);
                        $qty = (int) $data['qty'];

                        if ($data['reason'] === 'opname') {
                            $stockService->adjust(
                                product: $record,
                                newStock: $qty,
                                reason: 'opname',
                                userId: auth()->id(),
                                note: $data['note'] ?? 'Stok opname admin',
                            );
                        } else {
                            $deduct = min($qty, (int) $record->stock);
                            if ($deduct > 0) {
                                $stockService->stockOut(
                                    product: $record,
                                    qty: $deduct,
                                    reason: $data['reason'],
                                    userId: auth()->id(),
                                    note: $data['note'] ?? 'Penyesuaian stok keluar',
                                );
                            }
                        }

                        Notification::make()
                            ->title('Mutasi Stok Dicatat')
                            ->body("Stok {$record->name} kini menjadi {$record->fresh()->stock} {$record->uom}.")
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
            'index' => ManageProducts::route('/'),
        ];
    }
}
