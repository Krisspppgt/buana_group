<?php

namespace App\Filament\Resources\Expenses;

use App\Enums\Unit;
use App\Filament\Resources\Expenses\Pages\ManageExpenses;
use App\Models\Expense;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class ExpenseResource extends Resource
{
    protected static ?string $model = Expense::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedReceiptPercent;

    protected static ?string $navigationLabel = 'Biaya & Pengeluaran';

    protected static ?string $modelLabel = 'Biaya Operasional';

    protected static ?string $pluralModelLabel = 'Biaya & Pengeluaran';

    protected static string|UnitEnum|null $navigationGroup = 'Laporan & Keuangan';

    protected static ?int $navigationSort = 4;

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
                        Unit::Tour->value => Unit::Tour->label(),
                        Unit::Ticketing->value => Unit::Ticketing->label(),
                    ])
                    ->placeholder('Umum / Pusat (Semua Unit)'),

                Select::make('category')
                    ->label('Kategori Biaya')
                    ->options(Expense::categories())
                    ->default('operasional')
                    ->required(),

                TextInput::make('title')
                    ->label('Keterangan Pengeluaran')
                    ->placeholder('Contoh: BBM Kurir / Biaya Listrik Gudang')
                    ->required()
                    ->maxLength(120),

                TextInput::make('amount')
                    ->label('Nominal (Rp)')
                    ->numeric()
                    ->prefix('Rp')
                    ->minValue(1)
                    ->required(),

                DatePicker::make('incurred_at')
                    ->label('Tanggal Pengeluaran')
                    ->default(now())
                    ->required(),

                Textarea::make('note')
                    ->label('Catatan Tambahan')
                    ->maxLength(255)
                    ->columnSpanFull(),

                Hidden::make('recorded_by')
                    ->default(fn (): ?int => auth()->id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('incurred_at', 'desc')
            ->columns([
                TextColumn::make('incurred_at')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('title')
                    ->label('Keterangan')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn (Expense $record): ?string => $record->note),

                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Expense::categories()[$state] ?? ucfirst($state))
                    ->color(fn (string $state): string => $state === 'kerusakan' ? 'danger' : 'gray'),

                TextColumn::make('unit')
                    ->label('Unit Usaha')
                    ->badge()
                    ->formatStateUsing(fn (?Unit $state): string => $state?->label() ?? 'Umum / Pusat'),

                TextColumn::make('amount')
                    ->label('Nominal')
                    ->money('IDR', locale: 'id')
                    ->sortable()
                    ->weight('bold')
                    ->color('danger'),

                TextColumn::make('recorder.name')
                    ->label('Dicatat Oleh')
                    ->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Kategori')
                    ->options(Expense::categories()),

                SelectFilter::make('unit')
                    ->label('Unit Usaha')
                    ->options([
                        Unit::Air->value => Unit::Air->label(),
                        Unit::Food->value => Unit::Food->label(),
                        Unit::Konveksi->value => Unit::Konveksi->label(),
                        Unit::Tour->value => Unit::Tour->label(),
                        Unit::Ticketing->value => Unit::Ticketing->label(),
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
            'index' => ManageExpenses::route('/'),
        ];
    }
}
