<?php

namespace App\Filament\Resources\Tickets;

use App\Filament\Resources\Tickets\Pages\ManageTickets;
use App\Models\Ticket;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
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

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTicket;

    protected static ?string $navigationLabel = 'E-Boarding Pass & Tiket';

    protected static ?string $modelLabel = 'Tiket';

    protected static ?string $pluralModelLabel = 'E-Boarding Pass & Tiket';

    protected static string|UnitEnum|null $navigationGroup = 'Tour & Ticketing';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Kode Tiket')
                    ->default(fn (): string => Ticket::newCode())
                    ->required()
                    ->maxLength(16),

                Select::make('user_id')
                    ->label('Akun Pemesan')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('category')
                    ->label('Kategori Tiket')
                    ->options([
                        'transport' => 'Transportasi (KAI / Pesawat / Bus / Kapal)',
                        'event' => 'Event / Seminar UNWAHAS',
                    ])
                    ->required(),

                TextInput::make('title')
                    ->label('Nama Rute / Acara')
                    ->required()
                    ->maxLength(100),

                TextInput::make('passenger_name')
                    ->label('Nama Penumpang / Peserta')
                    ->required()
                    ->maxLength(50),

                TextInput::make('passenger_id')
                    ->label('NIK / NIM / NIP')
                    ->required()
                    ->maxLength(30),

                DateTimePicker::make('schedule_at')
                    ->label('Jadwal Keberangkatan / Acara')
                    ->required(),

                TextInput::make('seat_info')
                    ->label('Nomor Kursi / Lokasi Gate')
                    ->required()
                    ->maxLength(50),

                TextInput::make('price')
                    ->label('Harga Tiket (Rp)')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),

                Select::make('status')
                    ->label('Status Boarding')
                    ->options([
                        0 => 'Valid / Belum Check-in',
                        1 => 'Sudah Boarding / Di-scan',
                        2 => 'Dibatalkan',
                    ])
                    ->default(0)
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
                    ->label('Kode E-Ticket')
                    ->searchable()
                    ->weight('bold')
                    ->copyable(),

                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'transport' ? 'Transportasi' : 'Event Kampus')
                    ->color(fn (string $state): string => $state === 'transport' ? 'info' : 'primary'),

                TextColumn::make('title')
                    ->label('Rute / Acara')
                    ->searchable()
                    ->description(fn (Ticket $record): string => "{$record->passenger_name} ({$record->passenger_id}) • {$record->seat_info}"),

                TextColumn::make('schedule_at')
                    ->label('Jadwal')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status Gate')
                    ->badge()
                    ->formatStateUsing(fn (Ticket $record): string => $record->status_label)
                    ->color(fn (int $state): string => match ($state) {
                        0 => 'success',
                        1 => 'info',
                        2 => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'transport' => 'Transportasi',
                        'event' => 'Event Kampus',
                    ]),
            ])
            ->recordActions([
                Action::make('scanCheckIn')
                    ->label('Scan Check-In')
                    ->icon('heroicon-o-qr-code')
                    ->color('success')
                    ->visible(fn (Ticket $record): bool => $record->status === 0)
                    ->requiresConfirmation()
                    ->action(function (Ticket $record): void {
                        $record->update([
                            'status' => 1,
                            'checked_in_at' => now(),
                        ]);

                        Notification::make()
                            ->title('E-Boarding Pass Tervalidasi')
                            ->body("Tiket {$record->code} atas nama {$record->passenger_name} berhasil check-in.")
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
            'index' => ManageTickets::route('/'),
        ];
    }
}
