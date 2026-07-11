<?php

namespace App\Filament\Admin\Resources\TicketResource\Pages;

use App\Filament\Admin\Resources\TicketResource;
use App\Models\Ticket;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListTickets extends ListRecords
{
    protected static string $resource = TicketResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua')
                ->badge(Ticket::count()),

            'pending' => Tab::make('Menunggu Konfirmasi')
                ->badge(Ticket::where('status', 'PENDING')->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'PENDING')),

            'waiting_assignment' => Tab::make('Menunggu Penugasan')
                ->badge(Ticket::where('status', 'WAITING_ASSIGNMENT')->count())
                ->badgeColor('info')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'WAITING_ASSIGNMENT')),

            'in_progress' => Tab::make('Dalam Proses')
                ->badge(Ticket::whereIn('status', ['ASSIGNED', 'ON_THE_WAY', 'DIAGNOSIS', 'WAITING_PART', 'REPAIR'])->count())
                ->badgeColor('primary')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', ['ASSIGNED', 'ON_THE_WAY', 'DIAGNOSIS', 'WAITING_PART', 'REPAIR'])),

            'completed' => Tab::make('Selesai')
                ->badge(Ticket::where('status', 'COMPLETED')->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'COMPLETED')),

            'closed' => Tab::make('Ditutup / Ditolak')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', ['CLOSED', 'REJECTED'])),
        ];
    }
}
