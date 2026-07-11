<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\TicketResource;
use App\Models\Ticket;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PendingTicketsWidget extends BaseWidget
{
    protected static ?string $heading = 'Tiket Butuh Konfirmasi';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return in_array(auth()->user()?->role, ['ADMIN', 'MANAGER']);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Ticket::with(['customer'])
                    ->where('status', 'PENDING')
                    ->orderBy('created_at')
            )
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->label('Invoice')
                    ->weight('semibold'),

                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Pelanggan'),

                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => match ($state) {
                        'TV'     => '📺 TV',
                        'HP'     => '📱 HP',
                        'LAPTOP' => '💻 Laptop',
                        default  => $state ?? '—',
                    })
                    ->color(fn (?string $state) => match ($state) {
                        'TV'     => 'success',
                        'HP'     => 'warning',
                        'LAPTOP' => 'info',
                        default  => 'gray',
                    }),

                Tables\Columns\TextColumn::make('description')
                    ->label('Keluhan')
                    ->limit(50),

                Tables\Columns\TextColumn::make('preferred_date')
                    ->label('Jadwal')
                    ->date('d/m/Y'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Diterima')
                    ->since()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Setujui')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn () => auth()->user()?->role === 'ADMIN')
                    ->requiresConfirmation()
                    ->action(function (Ticket $record) {
                        $record->update(['status' => 'WAITING_ASSIGNMENT']);
                        $record->logs()->create([
                            'old_status' => 'PENDING',
                            'new_status' => 'WAITING_ASSIGNMENT',
                            'note'       => 'Disetujui dari dashboard.',
                        ]);
                    }),

                Tables\Actions\Action::make('view')
                    ->label('Detail')
                    ->url(fn (Ticket $record) => TicketResource::getUrl('view', ['record' => $record]))
                    ->icon('heroicon-o-eye')
                    ->color('gray'),
            ])
            ->emptyStateHeading('Tidak ada tiket yang menunggu konfirmasi')
            ->emptyStateIcon('heroicon-o-check-badge')
            ->paginated(false);
    }
}
