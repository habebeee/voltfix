<?php

namespace App\Filament\Admin\Resources;

use App\Helpers\CategoryHelper;
use App\Filament\Admin\Resources\TicketResource\Pages;
use App\Models\Technician;
use App\Models\Ticket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationGroup = 'Manajemen Servis';

    protected static ?string $navigationLabel = 'Tiket Servis';

    protected static ?string $modelLabel = 'Tiket';

    protected static ?string $pluralModelLabel = 'Semua Tiket';

    protected static ?int $navigationSort = 1;

    // ──────────────────────────────────────────────────────────────────────────
    // Authorization
    // ──────────────────────────────────────────────────────────────────────────

    public static function canViewAny(): bool
    {
        return in_array(auth()->user()?->role, ['ADMIN', 'MANAGER']);
    }

    public static function canView($record): bool
    {
        return in_array(auth()->user()?->role, ['ADMIN', 'MANAGER']);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->role === 'ADMIN';
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->role === 'ADMIN';
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Navigation badge (pending + waiting assignment)
    // ──────────────────────────────────────────────────────────────────────────

    public static function getNavigationBadge(): ?string
    {
        return Ticket::whereIn('status', ['PENDING', 'WAITING_ASSIGNMENT'])->count() ?: null;
    }

    public static function getNavigationBadgeColor(): string
    {
        return 'warning';
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Infolist (digunakan pada ViewRecord)
    // ──────────────────────────────────────────────────────────────────────────

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([

            Infolists\Components\Grid::make(3)->schema([

                // ── Kolom kiri (detail tiket) ──────────────────────────────
                Infolists\Components\Group::make()->columnSpan(2)->schema([

                    Infolists\Components\Section::make('Informasi Tiket')
                        ->columns(2)
                        ->schema([
                            Infolists\Components\TextEntry::make('invoice_number')
                                ->label('No. Invoice')
                                ->weight('bold')
                                ->copyable(),

                            Infolists\Components\TextEntry::make('status')
                                ->label('Status')
                                ->badge()
                                ->color(fn ($state) => match ($state) {
                                    'PENDING'            => 'warning',
                                    'REJECTED'           => 'danger',
                                    'WAITING_ASSIGNMENT' => 'info',
                                    'ASSIGNED', 'ON_THE_WAY', 'DIAGNOSIS', 'REPAIR' => 'primary',
                                    'WAITING_PART'       => 'warning',
                                    'COMPLETED'          => 'success',
                                    'CLOSED'             => 'gray',
                                    default              => 'gray',
                                })
                                ->formatStateUsing(fn ($state) => self::statusOptions()[$state] ?? $state),

                            Infolists\Components\TextEntry::make('customer.name')
                                ->label('Pelanggan'),

                            Infolists\Components\TextEntry::make('customer.phone')
                                ->label('WhatsApp Pelanggan')
                                ->url(fn ($record) => 'https://wa.me/' . $record->customer?->phone, shouldOpenInNewTab: true)
                                ->color('success'),

                            Infolists\Components\TextEntry::make('category')
                                ->label('Kategori')
                                ->badge()
                                ->formatStateUsing(fn (?string $state) => CategoryHelper::label($state))
                                ->color(fn (?string $state) => CategoryHelper::filamentColor($state)),

                            Infolists\Components\TextEntry::make('brand')
                                ->label('Merek')
                                ->default('-'),

                            Infolists\Components\TextEntry::make('preferred_date')
                                ->label('Tanggal Pilihan')
                                ->date('d M Y'),

                            Infolists\Components\TextEntry::make('preferred_time')
                                ->label('Jam Pilihan'),

                            Infolists\Components\TextEntry::make('description')
                                ->label('Keluhan')
                                ->columnSpanFull(),

                            Infolists\Components\TextEntry::make('address')
                                ->label('Alamat Penjemputan')
                                ->columnSpanFull()
                                ->placeholder('—')
                                ->formatStateUsing(function ($state, $record) {
                                    if (! $state) return '—';
                                    $parts = array_filter([
                                        $state,
                                        $record->district,
                                        $record->city . ($record->postal_code ? ' ' . $record->postal_code : ''),
                                        $record->address_notes ? '📍 ' . $record->address_notes : null,
                                    ]);
                                    return implode(' · ', $parts);
                                }),

                            Infolists\Components\TextEntry::make('rejection_reason')
                                ->label('Alasan Penolakan')
                                ->color('danger')
                                ->visible(fn ($record) => filled($record->rejection_reason))
                                ->columnSpanFull(),
                        ]),

                    // Foto kerusakan
                    Infolists\Components\Section::make('Foto Kerusakan')
                        ->visible(fn ($record) => ! empty($record->photo_urls))
                        ->schema([
                            Infolists\Components\ImageEntry::make('photo_urls')
                                ->label('')
                                ->height(120)
                                ->width(160)
                                ->visibility('public'),
                        ]),

                    // Riwayat status
                    Infolists\Components\Section::make('Riwayat Perubahan Status')
                        ->schema([
                            Infolists\Components\RepeatableEntry::make('logs')
                                ->label('')
                                ->schema([
                                    Infolists\Components\TextEntry::make('new_status')
                                        ->label('Status Baru')
                                        ->badge()
                                        ->color('primary'),
                                    Infolists\Components\TextEntry::make('old_status')
                                        ->label('Status Lama')
                                        ->badge()
                                        ->color('gray'),
                                    Infolists\Components\TextEntry::make('note')
                                        ->label('Catatan')
                                        ->default('-'),
                                    Infolists\Components\TextEntry::make('created_at')
                                        ->label('Waktu')
                                        ->dateTime('d/m/Y H:i'),
                                ])
                                ->columns(4),
                        ]),
                ]),

                // ── Kolom kanan (teknisi & rating) ────────────────────────
                Infolists\Components\Group::make()->columnSpan(1)->schema([

                    Infolists\Components\Section::make('Teknisi')
                        ->schema([
                            Infolists\Components\TextEntry::make('technician.user.name')
                                ->label('Nama')
                                ->default('Belum ditugaskan'),

                            Infolists\Components\TextEntry::make('technician.skill_category')
                                ->label('Keahlian')
                                ->badge()
                                ->default('-'),

                            Infolists\Components\TextEntry::make('technician.average_rating')
                                ->label('Rating')
                                ->formatStateUsing(fn ($state) => $state ? $state . ' ⭐' : '-'),

                            Infolists\Components\TextEntry::make('technician.user.phone')
                                ->label('WhatsApp')
                                ->url(fn ($record) => $record->technician?->user?->phone
                                    ? 'https://wa.me/' . $record->technician->user->phone
                                    : null, shouldOpenInNewTab: true)
                                ->color('success')
                                ->default('-'),
                        ]),

                    Infolists\Components\Section::make('Rating Pelanggan')
                        ->visible(fn ($record) => $record->rating !== null)
                        ->schema([
                            Infolists\Components\TextEntry::make('rating.rating')
                                ->label('Bintang')
                                ->formatStateUsing(fn ($state) => str_repeat('★', $state) . str_repeat('☆', 5 - $state) . " ({$state}/5)"),
                            Infolists\Components\TextEntry::make('rating.review')
                                ->label('Ulasan')
                                ->default('-'),
                        ]),

                    Infolists\Components\Section::make('Waktu')
                        ->schema([
                            Infolists\Components\TextEntry::make('created_at')
                                ->label('Diajukan')
                                ->dateTime('d/m/Y H:i'),
                            Infolists\Components\TextEntry::make('closed_at')
                                ->label('Ditutup')
                                ->formatStateUsing(fn ($state) => $state ? $state->format('d/m/Y H:i') : '—')
                                ->placeholder('Belum ditutup'),
                        ]),
                ]),
            ]),
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Form (digunakan pada EditRecord — hanya untuk ADMIN)
    // ──────────────────────────────────────────────────────────────────────────

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Tiket (Baca Saja)')
                ->columns(2)
                ->collapsed()
                ->schema([
                    Forms\Components\TextInput::make('invoice_number')->label('No. Invoice')->disabled(),
                    Forms\Components\Select::make('customer_id')->label('Pelanggan')
                        ->relationship('customer', 'name')->disabled(),
                    Forms\Components\Select::make('category')->label('Kategori')
                        ->options(CategoryHelper::options())
                        ->disabled(),
                    Forms\Components\TextInput::make('brand')->label('Merek')->disabled(),
                    Forms\Components\DatePicker::make('preferred_date')->label('Tanggal Pilihan')->disabled(),
                    Forms\Components\TextInput::make('preferred_time')->label('Jam Pilihan')->disabled(),
                    Forms\Components\Textarea::make('description')->label('Keluhan')->columnSpanFull()->disabled(),
                ]),

            Forms\Components\Section::make('Tindakan Admin')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('status')
                        ->label('Ubah Status')
                        ->options(self::statusOptions())
                        ->required()
                        ->live(),

                    Forms\Components\Select::make('technician_id')
                        ->label('Tugaskan Teknisi')
                        ->options(function (Get $get) {
                            $category = $get('category') ?? '';
                            return Technician::with('user')
                                ->when($category, fn ($q) => $q->where('skill_category', $category))
                                ->where('is_available', true)
                                ->get()
                                ->mapWithKeys(fn ($t) => [$t->id => $t->user->name . ' (' . $t->average_rating . '⭐)']);
                        })
                        ->searchable()
                        ->nullable(),

                    Forms\Components\Textarea::make('rejection_reason')
                        ->label('Alasan Penolakan')
                        ->columnSpanFull()
                        ->nullable()
                        ->visible(fn (Get $get) => $get('status') === 'REJECTED'),
                ]),
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Table
    // ──────────────────────────────────────────────────────────────────────────

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'asc') // FIFO
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->label('Invoice')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),

                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Pelanggan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->formatStateUsing(fn ($state) => CategoryHelper::filamentBadge($state))
                    ->color(fn ($state) => CategoryHelper::filamentColor($state)),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => self::statusOptions()[$state] ?? $state)
                    ->color(fn ($state) => match ($state) {
                        'PENDING'            => 'warning',
                        'REJECTED'           => 'danger',
                        'WAITING_ASSIGNMENT' => 'info',
                        'ASSIGNED', 'ON_THE_WAY', 'DIAGNOSIS', 'REPAIR' => 'primary',
                        'WAITING_PART'       => 'warning',
                        'COMPLETED'          => 'success',
                        'CLOSED'             => 'gray',
                        default              => 'gray',
                    }),

                Tables\Columns\TextColumn::make('technician.user.name')
                    ->label('Teknisi')
                    ->default('—')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('preferred_date')
                    ->label('Jadwal')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(self::statusOptions()),

                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori')
                    ->options(CategoryHelper::options()),
            ])
            ->actions([
                // ── Aksi cepat untuk ADMIN (muncul langsung di baris) ──────
                Tables\Actions\Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Ticket $record) => $record->status === 'PENDING'
                        && auth()->user()?->role === 'ADMIN')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Tiket?')
                    ->modalDescription(fn (Ticket $record) => 'Tiket ' . $record->invoice_number . ' akan dipindah ke antrian penugasan teknisi.')
                    ->action(function (Ticket $record) {
                        $record->update(['status' => 'WAITING_ASSIGNMENT']);
                        $record->logs()->create([
                            'old_status' => 'PENDING',
                            'new_status' => 'WAITING_ASSIGNMENT',
                            'note'       => 'Disetujui oleh admin.',
                        ]);
                        Notification::make()->title('Tiket berhasil disetujui.')->success()->send();
                    }),

                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Ticket $record) => $record->status === 'PENDING'
                        && auth()->user()?->role === 'ADMIN')
                    ->form([
                        Forms\Components\Textarea::make('rejection_reason')
                            ->label('Alasan Penolakan')
                            ->required()
                            ->rows(3),
                    ])
                    ->modalHeading('Tolak Tiket')
                    ->action(function (Ticket $record, array $data) {
                        $record->update([
                            'status'           => 'REJECTED',
                            'rejection_reason' => $data['rejection_reason'],
                        ]);
                        $record->logs()->create([
                            'old_status' => 'PENDING',
                            'new_status' => 'REJECTED',
                            'note'       => $data['rejection_reason'],
                        ]);
                        Notification::make()->title('Tiket ditolak.')->warning()->send();
                    }),

                Tables\Actions\Action::make('assign')
                    ->label('Tugaskan')
                    ->icon('heroicon-o-user-plus')
                    ->color('primary')
                    ->visible(fn (Ticket $record) => $record->status === 'WAITING_ASSIGNMENT'
                        && auth()->user()?->role === 'ADMIN')
                    ->form(function (Ticket $record) {
                        return [
                            Forms\Components\Select::make('technician_id')
                                ->label('Pilih Teknisi (' . $record->category . ')')
                                ->options(
                                    Technician::with('user')
                                        ->where('skill_category', $record->category)
                                        ->where('is_available', true)
                                        ->get()
                                        ->mapWithKeys(fn ($t) => [
                                            $t->id => $t->user->name . ' — rating ' . $t->average_rating . '⭐ (' . $t->experience . ')',
                                        ])
                                )
                                ->required()
                                ->searchable(),
                        ];
                    })
                    ->modalHeading('Tugaskan Teknisi')
                    ->action(function (Ticket $record, array $data) {
                        $record->update([
                            'status'        => 'ASSIGNED',
                            'technician_id' => $data['technician_id'],
                        ]);
                        $record->logs()->create([
                            'old_status' => 'WAITING_ASSIGNMENT',
                            'new_status' => 'ASSIGNED',
                            'note'       => 'Teknisi ditugaskan oleh admin.',
                        ]);
                        Notification::make()->title('Teknisi berhasil ditugaskan.')->success()->send();
                    }),

                // ── Lihat detail (semua role) ──────────────────────────────
                Tables\Actions\ViewAction::make()->label('Detail'),

                // ── Edit lengkap (ADMIN only) ──────────────────────────────
                Tables\Actions\EditAction::make()->label('Edit')
                    ->visible(fn () => auth()->user()?->role === 'ADMIN'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()?->role === 'ADMIN'),
                ]),
            ]);
    }

    public static function statusOptions(): array
    {
        return [
            'PENDING'            => 'Menunggu Konfirmasi',
            'REJECTED'           => 'Ditolak',
            'WAITING_ASSIGNMENT' => 'Menunggu Penugasan',
            'ASSIGNED'           => 'Teknisi Ditugaskan',
            'ON_THE_WAY'         => 'Teknisi Dalam Perjalanan',
            'DIAGNOSIS'          => 'Diagnosa',
            'WAITING_PART'       => 'Menunggu Spare Part',
            'REPAIR'             => 'Perbaikan',
            'COMPLETED'          => 'Selesai',
            'CLOSED'             => 'Ditutup',
        ];
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTickets::route('/'),
            'edit'   => Pages\EditTicket::route('/{record}/edit'),
            'view'   => Pages\ViewTicket::route('/{record}'),
        ];
    }
}
