<?php

namespace App\Filament\Resources\Users;

use App\Enums\Role;
use App\Enums\VerificationStatus;
use App\Filament\Resources\Users\Pages\ManageUsers;
use App\Models\User;
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
use Filament\Tables\Filters\TernaryFilter;
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

    public static function getNavigationBadge(): ?string
    {
        $pendingCount = static::getModel()::where('verification_status', VerificationStatus::Pending)->count();

        return $pendingCount > 0 ? (string) $pendingCount : null;
    }

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

                TextInput::make('faculty')
                    ->label('Fakultas / Unit Kerja')
                    ->maxLength(80),

                TextInput::make('company_name')
                    ->label('Nama Instansi / Mitra B2B')
                    ->maxLength(100),

                TextInput::make('position')
                    ->label('Jabatan PIC Mitra')
                    ->maxLength(60),

                TextInput::make('loyalty_points')
                    ->label('Saldo Buana Loyalty Points')
                    ->numeric()
                    ->default(0)
                    ->minValue(0),

                Select::make('role')
                    ->label('Peran / Hak Akses Aktif')
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

                Select::make('verification_status')
                    ->label('Status Verifikasi Akun')
                    ->options([
                        VerificationStatus::None->value => VerificationStatus::None->label(),
                        VerificationStatus::Pending->value => VerificationStatus::Pending->label(),
                        VerificationStatus::Approved->value => VerificationStatus::Approved->label(),
                        VerificationStatus::Rejected->value => VerificationStatus::Rejected->label(),
                    ])
                    ->default(VerificationStatus::None->value)
                    ->required(),

                Toggle::make('tempo_approved')
                    ->label('Setujui Fasilitas Tempo (TOP 30 Hari)')
                    ->default(false),

                TextInput::make('tempo_limit')
                    ->label('Limit Tempo (Rp)')
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0)
                    ->minValue(0),

                Textarea::make('verification_note')
                    ->label('Catatan Verifikasi Admin')
                    ->maxLength(255)
                    ->columnSpanFull(),

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
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (User $record): string => trim(collect([
                        $record->email,
                        $record->faculty,
                        $record->company_name ? "{$record->company_name}".($record->position ? " ({$record->position})" : '') : null,
                    ])->filter()->implode(' • '))),

                TextColumn::make('phone')
                    ->label('No. WhatsApp')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('campus_id')
                    ->label('NIM / NIP')
                    ->badge()
                    ->color('info')
                    ->placeholder('Umum'),

                TextColumn::make('verification_status')
                    ->label('Verifikasi')
                    ->badge()
                    ->formatStateUsing(fn (?VerificationStatus $state, User $record): string => $state === VerificationStatus::Pending && $record->requested_role
                        ? "Pending ({$record->requested_role->label()})"
                        : ($state?->label() ?? 'Reguler'))
                    ->color(fn (?VerificationStatus $state): string => match ($state) {
                        VerificationStatus::Pending => 'warning',
                        VerificationStatus::Approved => 'success',
                        VerificationStatus::Rejected => 'danger',
                        default => 'gray',
                    }),

                IconColumn::make('tempo_approved')
                    ->label('Tempo')
                    ->boolean(),

                TextColumn::make('tempo_limit')
                    ->label('Limit Tempo')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                TextColumn::make('loyalty_points')
                    ->label('Buana Points')
                    ->badge()
                    ->color('warning')
                    ->formatStateUsing(fn (int $state): string => number_format($state, 0, ',', '.').' Poin')
                    ->sortable(),

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
                SelectFilter::make('verification_status')
                    ->label('Status Verifikasi')
                    ->options([
                        VerificationStatus::Pending->value => VerificationStatus::Pending->label(),
                        VerificationStatus::Approved->value => VerificationStatus::Approved->label(),
                        VerificationStatus::Rejected->value => VerificationStatus::Rejected->label(),
                        VerificationStatus::None->value => VerificationStatus::None->label(),
                    ]),
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
                TernaryFilter::make('tempo_approved')
                    ->label('Fasilitas Tempo'),
            ])
            ->recordActions([
                Action::make('approveVerification')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn (User $record): bool => $record->verification_status === VerificationStatus::Pending)
                    ->schema(fn (User $record): array => [
                        Select::make('target_role')
                            ->label('Aktifkan Sebagai Role')
                            ->options([
                                Role::Civitas->value => Role::Civitas->label(),
                                Role::Coordinator->value => Role::Coordinator->label(),
                            ])
                            ->default($record->requested_role?->value ?? ($record->company_name ? Role::Coordinator->value : Role::Civitas->value))
                            ->required(),
                        Toggle::make('approve_tempo')
                            ->label('Sekaligus Setujui Fasilitas Tempo (TOP 30 Hari)')
                            ->default(false),
                        TextInput::make('tempo_limit')
                            ->label('Limit Kredit / Tempo (Rp)')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->minValue(0),
                        Textarea::make('note')
                            ->label('Catatan Verifikasi (Opsional)')
                            ->maxLength(255),
                    ])
                    ->action(function (User $record, array $data): void {
                        $targetRole = Role::from((int) $data['target_role']);
                        $approveTempo = (bool) ($data['approve_tempo'] ?? false);
                        $tempoLimit = (int) ($data['tempo_limit'] ?? 0);

                        $record->approveVerification(
                            $targetRole,
                            $approveTempo && $tempoLimit > 0,
                            $tempoLimit,
                            $data['note'] ?? null
                        );

                        Notification::make()
                            ->title('Akun Diverifikasi')
                            ->body("{$record->name} disetujui sebagai {$targetRole->label()}.")
                            ->success()
                            ->send();
                    }),

                Action::make('rejectVerification')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (User $record): bool => $record->verification_status === VerificationStatus::Pending)
                    ->schema([
                        Textarea::make('note')
                            ->label('Alasan Penolakan')
                            ->placeholder('Contoh: NIM/NIP tidak ditemukan di arsip atau surat instansi belum lengkap.')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->action(function (User $record, array $data): void {
                        $record->rejectVerification($data['note']);

                        Notification::make()
                            ->title('Pengajuan Ditolak')
                            ->body("Pengajuan {$record->name} ditolak. Akun tetap dapat bertransaksi sebagai Pelanggan Umum (Lunas/DP).")
                            ->warning()
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
            'index' => ManageUsers::route('/'),
        ];
    }
}
