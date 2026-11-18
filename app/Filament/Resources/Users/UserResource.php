<?php

namespace App\Filament\Resources\Users;

use App\Enums\Role;
use App\Filament\Resources\Users\Pages\ManageUsers;
use App\Models\User;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $navigationLabel = 'Pengguna & Civitas';

    protected static ?string $modelLabel = 'Pengguna';

    protected static ?string $pluralModelLabel = 'Pengguna & Civitas';

    protected static string|UnitEnum|null $navigationGroup = 'Pengguna & Akses';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(40),

                TextInput::make('email')
                    ->label('Alamat Email')
                    ->email()
                    ->required()
                    ->maxLength(50),

                TextInput::make('phone')
                    ->label('Nomor HP / WhatsApp')
                    ->required()
                    ->maxLength(15),

                TextInput::make('campus_id')
                    ->label('NIM / NIP / Kode Civitas UNWAHAS')
                    ->placeholder('Kosongkan jika pelanggan umum')
                    ->maxLength(20),

                Select::make('role')
                    ->label('Peran / Hak Akses')
                    ->options([
                        Role::Customer->value => Role::Customer->label(),
                        Role::Civitas->value => Role::Civitas->label(),
                        Role::Coordinator->value => Role::Coordinator->label(),
                        Role::Courier->value => Role::Courier->label(),
                        Role::Operator->value => Role::Operator->label(),
                        Role::Admin->value => Role::Admin->label(),
                        Role::SuperAdmin->value => Role::SuperAdmin->label(),
                    ])
                    ->default(Role::Customer->value)
                    ->required(),

                TextInput::make('password')
                    ->label('Kata Sandi')
                    ->password()
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->maxLength(72),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (User $record): string => $record->email ?? '-'),

                TextColumn::make('phone')
                    ->label('No. WhatsApp')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('campus_id')
                    ->label('NIM / NIP')
                    ->badge()
                    ->color('info')
                    ->placeholder('Umum'),

                TextColumn::make('role')
                    ->label('Role Sistem')
                    ->badge()
                    ->formatStateUsing(fn (Role $state): string => $state->label())
                    ->color(fn (Role $state): string => match ($state) {
                        Role::SuperAdmin, Role::Admin => 'danger',
                        Role::Operator, Role::Courier => 'warning',
                        Role::Civitas, Role::Coordinator => 'success',
                        Role::Customer => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label('Filter Role')
                    ->options([
                        Role::Customer->value => Role::Customer->label(),
                        Role::Civitas->value => Role::Civitas->label(),
                        Role::Coordinator->value => Role::Coordinator->label(),
                        Role::Courier->value => Role::Courier->label(),
                        Role::Operator->value => Role::Operator->label(),
                        Role::Admin->value => Role::Admin->label(),
                        Role::SuperAdmin->value => Role::SuperAdmin->label(),
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
            'index' => ManageUsers::route('/'),
        ];
    }
}
