<?php

namespace App\Filament\Resources\Events;

use App\Filament\Resources\Events\Pages\ManageEvents;
use App\Models\Event;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?string $navigationLabel = 'Event & Seminar Kampus';

    protected static ?string $modelLabel = 'Event Kampus';

    protected static ?string $pluralModelLabel = 'Event & Seminar Kampus';

    protected static string|UnitEnum|null $navigationGroup = 'Katalog & Inventaris';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Nama Acara / Seminar / Workshop')
                    ->required()
                    ->maxLength(80),

                TextInput::make('organizer')
                    ->label('Penyelenggara / Fakultas / Unit')
                    ->required()
                    ->maxLength(50),

                DateTimePicker::make('event_date')
                    ->label('Jadwal Pelaksanaan')
                    ->required(),

                TextInput::make('location')
                    ->label('Lokasi / Gedung / Aula')
                    ->required()
                    ->maxLength(80),

                TextInput::make('price')
                    ->label('Harga Tiket Umum (Rp)')
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0)
                    ->required(),

                TextInput::make('price_civitas')
                    ->label('Harga Tiket Civitas UNWAHAS (Rp)')
                    ->numeric()
                    ->prefix('Rp'),

                TextInput::make('quota')
                    ->label('Kapasitas Kuota Peserta')
                    ->numeric()
                    ->default(200)
                    ->required(),

                TextInput::make('booked')
                    ->label('Tiket Terjual')
                    ->numeric()
                    ->default(0)
                    ->required(),

                Toggle::make('is_active')
                    ->label('Penjualan Tiket Dibuka')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')
                    ->label('Nama Event')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn (Event $record): string => "{$record->organizer} • {$record->location}"),

                TextColumn::make('event_date')
                    ->label('Jadwal')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                TextColumn::make('price')
                    ->label('Harga Umum')
                    ->money('IDR', locale: 'id'),

                TextColumn::make('price_civitas')
                    ->label('Harga Civitas')
                    ->money('IDR', locale: 'id')
                    ->placeholder('-'),

                TextColumn::make('booked')
                    ->label('Kuota Terisi')
                    ->badge()
                    ->formatStateUsing(fn (Event $record): string => "{$record->booked} / {$record->quota}")
                    ->color(fn (Event $record): string => $record->booked >= $record->quota ? 'danger' : 'success'),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                //
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
            'index' => ManageEvents::route('/'),
        ];
    }
}
