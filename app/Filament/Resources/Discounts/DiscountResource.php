<?php

namespace App\Filament\Resources\Discounts;

use App\Enums\DiscountType;
use App\Enums\Unit;
use App\Filament\Resources\Discounts\Pages\ManageDiscounts;
use App\Models\Discount;
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
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use UnitEnum;

class DiscountResource extends Resource
{
    protected static ?string $model = Discount::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTicket;

    protected static ?string $navigationLabel = 'Diskon & Voucher';

    protected static ?string $modelLabel = 'Diskon';

    protected static ?string $pluralModelLabel = 'Diskon & Voucher';

    protected static string|UnitEnum|null $navigationGroup = 'Operasional & Transaksi';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'code';

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::currentlyAvailable()->count();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Promo')
                    ->placeholder('Contoh: Promo Awal Semester')
                    ->required()
                    ->maxLength(80),

                TextInput::make('code')
                    ->label('Kode Voucher')
                    ->placeholder('Contoh: BUANA10')
                    ->required()
                    ->maxLength(32)
                    ->unique(ignoreRecord: true)
                    ->alphaDash()
                    ->dehydrateStateUsing(fn (?string $state): string => strtoupper(trim((string) $state))),

                Select::make('type')
                    ->label('Tipe Potongan')
                    ->options([
                        DiscountType::Percentage->value => DiscountType::Percentage->label(),
                        DiscountType::Fixed->value => DiscountType::Fixed->label(),
                    ])
                    ->default(DiscountType::Percentage->value)
                    ->live()
                    ->required(),

                TextInput::make('value')
                    ->label(fn (Get $get): string => $get('type') === DiscountType::Fixed->value ? 'Nominal Potongan (Rp)' : 'Persentase Diskon (%)')
                    ->numeric()
                    ->prefix(fn (Get $get): ?string => $get('type') === DiscountType::Fixed->value ? 'Rp' : null)
                    ->suffix(fn (Get $get): ?string => $get('type') === DiscountType::Fixed->value ? null : '%')
                    ->minValue(1)
                    ->maxValue(fn (Get $get): ?int => $get('type') === DiscountType::Fixed->value ? null : 100)
                    ->required(),

                TextInput::make('max_discount')
                    ->label('Maksimal Potongan (Rp)')
                    ->numeric()
                    ->prefix('Rp')
                    ->minValue(1)
                    ->helperText('Opsional. Membatasi potongan untuk diskon persen.')
                    ->visible(fn (Get $get): bool => $get('type') !== DiscountType::Fixed->value),

                TextInput::make('min_subtotal')
                    ->label('Minimal Belanja (Rp)')
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0)
                    ->minValue(0)
                    ->required(),

                Select::make('unit')
                    ->label('Cakupan Unit Usaha')
                    ->placeholder('Semua Unit Usaha (5 Unit)')
                    ->options([
                        Unit::Air->value => Unit::Air->label(),
                        Unit::Food->value => Unit::Food->label(),
                        Unit::Konveksi->value => Unit::Konveksi->label(),
                        Unit::Tour->value => Unit::Tour->label(),
                        Unit::Ticketing->value => Unit::Ticketing->label(),
                    ])
                    ->nullable(),

                TextInput::make('quota')
                    ->label('Kuota Total')
                    ->numeric()
                    ->minValue(1)
                    ->helperText('Kosongkan jika kuota tidak terbatas.'),

                TextInput::make('max_uses_per_user')
                    ->label('Batas Pemakaian per Akun')
                    ->numeric()
                    ->minValue(1)
                    ->helperText('Kosongkan jika tanpa batas per user.'),

                DateTimePicker::make('starts_at')
                    ->label('Mulai Berlaku')
                    ->helperText('Kosongkan agar langsung berlaku.'),

                DateTimePicker::make('expires_at')
                    ->label('Berakhir Pada')
                    ->afterOrEqual('starts_at')
                    ->helperText('Kosongkan jika tanpa batas waktu.'),

                Textarea::make('description')
                    ->label('Keterangan Singkat')
                    ->placeholder('Contoh: Berlaku untuk pembelian paket rapat & katering minimal Rp 50.000.')
                    ->maxLength(160)
                    ->columnSpanFull(),

                Toggle::make('internal_only')
                    ->label('Khusus Member Internal')
                    ->helperText('Jika aktif, hanya berlaku untuk Civitas Akademika & Staf internal (bukan Pelanggan Umum / Mitra B2B).')
                    ->default(false),

                Toggle::make('is_active')
                    ->label('Aktifkan Voucher')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('code')
                    ->label('Kode Voucher')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->badge()
                    ->color('success')
                    ->copyable()
                    ->description(fn (Discount $record): string => $record->name),

                TextColumn::make('value')
                    ->label('Potongan')
                    ->formatStateUsing(fn (Discount $record): string => $record->formattedValue())
                    ->description(fn (Discount $record): string => $record->min_subtotal > 0
                        ? 'Min. belanja Rp '.number_format($record->min_subtotal, 0, ',', '.')
                        : 'Tanpa min. belanja'),

                TextColumn::make('unit')
                    ->label('Unit Usaha')
                    ->badge()
                    ->formatStateUsing(fn (?Unit $state): string => $state?->label() ?? 'Semua Unit')
                    ->placeholder('Semua Unit')
                    ->color(fn (?Unit $state): string => match ($state) {
                        Unit::Air => 'info',
                        Unit::Food => 'warning',
                        Unit::Konveksi => 'danger',
                        Unit::Tour => 'success',
                        Unit::Ticketing => 'primary',
                        default => 'gray',
                    }),

                TextColumn::make('internal_only')
                    ->label('Target')
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Member Internal' : 'Semua Pengguna')
                    ->color(fn (bool $state): string => $state ? 'warning' : 'gray'),

                TextColumn::make('used_count')
                    ->label('Pemakaian')
                    ->sortable()
                    ->formatStateUsing(fn (Discount $record): string => $record->quota !== null
                        ? "{$record->used_count} / {$record->quota}"
                        : "{$record->used_count} / ∞")
                    ->description(fn (Discount $record): string => $record->max_uses_per_user !== null
                        ? "Maks. {$record->max_uses_per_user}x / akun"
                        : 'Tanpa batas / akun'),

                TextColumn::make('expires_at')
                    ->label('Masa Berlaku')
                    ->dateTime('d M Y, H:i')
                    ->placeholder('Selamanya')
                    ->description(fn (Discount $record): string => $record->starts_at
                        ? 'Mulai '.$record->starts_at->format('d M Y, H:i')
                        : 'Berlaku langsung'),

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
                        Unit::Tour->value => Unit::Tour->label(),
                        Unit::Ticketing->value => Unit::Ticketing->label(),
                    ]),

                SelectFilter::make('type')
                    ->label('Tipe Potongan')
                    ->options([
                        DiscountType::Percentage->value => DiscountType::Percentage->label(),
                        DiscountType::Fixed->value => DiscountType::Fixed->label(),
                    ]),

                TernaryFilter::make('internal_only')
                    ->label('Khusus Member Internal'),

                TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
            ])
            ->recordActions([
                Action::make('toggle_active')
                    ->label(fn (Discount $record): string => $record->is_active ? 'Nonaktifkan' : 'Aktifkan')
                    ->icon(fn (Discount $record): string => $record->is_active ? 'heroicon-o-pause-circle' : 'heroicon-o-play-circle')
                    ->color(fn (Discount $record): string => $record->is_active ? 'warning' : 'success')
                    ->action(function (Discount $record): void {
                        $record->update(['is_active' => ! $record->is_active]);

                        Notification::make()
                            ->title($record->is_active ? 'Voucher Diaktifkan' : 'Voucher Dinonaktifkan')
                            ->body("Status voucher {$record->code} berhasil diperbarui.")
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
            'index' => ManageDiscounts::route('/'),
        ];
    }
}
