<?php

namespace App\Filament\Resources\TourPackages;

use App\Filament\Resources\TourPackages\Pages\ManageTourPackages;
use App\Models\TourPackage;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class TourPackageResource extends Resource
{
    protected static ?string $model = TourPackage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    protected static ?string $navigationLabel = 'Paket Tour & Umrah';

    protected static ?string $modelLabel = 'Paket Tour';

    protected static ?string $pluralModelLabel = 'Paket Tour & Umrah';

    protected static string|UnitEnum|null $navigationGroup = 'Katalog & Inventaris';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Nama Paket Perjalanan')
                    ->required()
                    ->maxLength(80),

                Select::make('category')
                    ->label('Kategori Paket')
                    ->options([
                        'wisata' => 'Wisata & Study Tour',
                        'ziarah' => 'Ziarah Wali',
                        'umrah' => 'Paket Umrah',
                    ])
                    ->required(),

                DatePicker::make('departure_date')
                    ->label('Tanggal Keberangkatan')
                    ->required(),

                TextInput::make('duration')
                    ->label('Durasi Perjalanan')
                    ->placeholder('3 Hari 2 Malam / 9 Hari')
                    ->required()
                    ->maxLength(30),

                TextInput::make('price')
                    ->label('Harga Umum / Pax (Rp)')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),

                TextInput::make('price_civitas')
                    ->label('Harga Khusus Civitas / Pax (Rp)')
                    ->numeric()
                    ->prefix('Rp'),

                TextInput::make('min_dp')
                    ->label('Minimal DP Cicilan / Pax (Rp)')
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0)
                    ->required(),

                TextInput::make('quota')
                    ->label('Total Kuota Kursi')
                    ->numeric()
                    ->default(45)
                    ->required(),

                TextInput::make('booked')
                    ->label('Terisi (Pax)')
                    ->numeric()
                    ->default(0)
                    ->required(),

                Textarea::make('itinerary')
                    ->label('Ringkasan Itinerary & Fasilitas')
                    ->maxLength(250)
                    ->columnSpanFull(),

                Toggle::make('allow_installment')
                    ->label('Izinkan Skema Cicilan / Tabungan')
                    ->default(true),

                Toggle::make('is_active')
                    ->label('Paket Aktif')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')
                    ->label('Nama Paket')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn (TourPackage $record): string => $record->duration),

                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => strtoupper($state))
                    ->color(fn (string $state): string => match ($state) {
                        'umrah' => 'success',
                        'ziarah' => 'info',
                        default => 'warning',
                    }),

                TextColumn::make('departure_date')
                    ->label('Berangkat')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('price')
                    ->label('Harga Umum')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                TextColumn::make('price_civitas')
                    ->label('Harga Civitas')
                    ->money('IDR', locale: 'id')
                    ->placeholder('-'),

                TextColumn::make('booked')
                    ->label('Keterisian Kuota')
                    ->badge()
                    ->formatStateUsing(fn (TourPackage $record): string => "{$record->booked} / {$record->quota} pax")
                    ->color(fn (TourPackage $record): string => $record->booked >= $record->quota ? 'danger' : 'success'),

                IconColumn::make('allow_installment')
                    ->label('Bisa Cicilan')
                    ->boolean(),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'wisata' => 'Wisata & Study Tour',
                        'ziarah' => 'Ziarah Wali',
                        'umrah' => 'Paket Umrah',
                    ]),
            ])
            ->recordActions([
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
            'index' => ManageTourPackages::route('/'),
        ];
    }
}
