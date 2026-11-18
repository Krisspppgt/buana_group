<?php

namespace App\Filament\Resources\TourBookings;

use App\Filament\Resources\TourBookings\Pages\ManageTourBookings;
use App\Models\TourBooking;
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
use UnitEnum;

class TourBookingResource extends Resource
{
    protected static ?string $model = TourBooking::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $navigationLabel = 'Booking & Cicilan Tour';

    protected static ?string $modelLabel = 'Booking Tour';

    protected static ?string $pluralModelLabel = 'Booking & Cicilan Tour';

    protected static string|UnitEnum|null $navigationGroup = 'Tour & Ticketing';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'code';

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::whereIn('status', [0, 1])->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Kode Booking')
                    ->default(fn (): string => TourBooking::newCode())
                    ->required()
                    ->maxLength(16),

                Select::make('tour_package_id')
                    ->label('Paket Perjalanan')
                    ->relationship('tourPackage', 'title')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('user_id')
                    ->label('Akun Pemesan')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('coordinator_name')
                    ->label('Nama Koordinator Rombongan')
                    ->required()
                    ->maxLength(50),

                TextInput::make('coordinator_phone')
                    ->label('No. WhatsApp Koordinator')
                    ->required()
                    ->maxLength(15),

                TextInput::make('total_pax')
                    ->label('Jumlah Peserta (Pax)')
                    ->numeric()
                    ->minValue(1)
                    ->required(),

                Select::make('payment_scheme')
                    ->label('Skema Pembayaran')
                    ->options([
                        'cash' => 'Tunai / Lunas Langsung',
                        'cicilan' => 'Cicilan / Tabungan Perjalanan',
                    ])
                    ->required(),

                TextInput::make('installment_tenor')
                    ->label('Tenor Cicilan (Bulan)')
                    ->numeric()
                    ->default(1)
                    ->required(),

                TextInput::make('paid_amount')
                    ->label('Sudah Dibayar / DP Masuk (Rp)')
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0)
                    ->required(),

                TextInput::make('total_amount')
                    ->label('Total Tagihan Rombongan (Rp)')
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0)
                    ->required(),

                Select::make('status')
                    ->label('Status Booking')
                    ->options([
                        0 => 'Menunggu Konfirmasi',
                        1 => 'Cicilan Aktif',
                        2 => 'Lunas / Siap Berangkat',
                        3 => 'Selesai',
                        9 => 'Dibatalkan',
                    ])
                    ->default(1)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('code')
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('code')
                    ->label('Kode Booking')
                    ->searchable()
                    ->weight('bold')
                    ->copyable(),

                TextColumn::make('tourPackage.title')
                    ->label('Paket Perjalanan')
                    ->searchable()
                    ->description(fn (TourBooking $record): string => "Koordinator: {$record->coordinator_name} ({$record->total_pax} pax)"),

                TextColumn::make('payment_scheme')
                    ->label('Skema')
                    ->badge()
                    ->formatStateUsing(fn (string $state, TourBooking $record): string => $state === 'cicilan' ? "Cicilan {$record->installment_tenor}x" : 'Tunai')
                    ->color(fn (string $state): string => $state === 'cicilan' ? 'warning' : 'success'),

                TextColumn::make('paid_amount')
                    ->label('Terbayar')
                    ->money('IDR', locale: 'id')
                    ->color('success')
                    ->weight('bold'),

                TextColumn::make('total_amount')
                    ->label('Total Tagihan')
                    ->money('IDR', locale: 'id')
                    ->description(function (TourBooking $record): string {
                        $remaining = max(0, $record->total_amount - $record->paid_amount);

                        return $remaining > 0
                            ? 'Sisa: Rp '.number_format($remaining, 0, ',', '.')
                            : 'Lunas 100%';
                    }),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (TourBooking $record): string => $record->status_label)
                    ->color(fn (int $state): string => match ($state) {
                        0 => 'warning',
                        1 => 'info',
                        2, 3 => 'success',
                        9 => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('payment_scheme')
                    ->label('Skema Bayar')
                    ->options([
                        'cash' => 'Tunai',
                        'cicilan' => 'Cicilan / Tabungan',
                    ]),
            ])
            ->recordActions([
                Action::make('payInstallment')
                    ->label('Catat Angsuran')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->visible(fn (TourBooking $record): bool => $record->paid_amount < $record->total_amount && $record->status !== 9)
                    ->schema([
                        TextInput::make('amount')
                            ->label('Nominal Setoran Angsuran (Rp)')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(fn (TourBooking $record): int => max(0, $record->total_amount - $record->paid_amount))
                            ->minValue(10000)
                            ->required(),
                    ])
                    ->action(function (TourBooking $record, array $data): void {
                        $newPaid = min($record->total_amount, $record->paid_amount + (int) $data['amount']);
                        $isFullyPaid = $newPaid >= $record->total_amount;

                        $record->update([
                            'paid_amount' => $newPaid,
                            'status' => $isFullyPaid ? 2 : 1,
                        ]);

                        Notification::make()
                            ->title($isFullyPaid ? 'Cicilan Lunas 100%!' : 'Setoran Angsuran Tercatat')
                            ->body("Total pembayaran booking {$record->code} kini Rp ".number_format($newPaid, 0, ',', '.'))
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
            'index' => ManageTourBookings::route('/'),
        ];
    }
}
