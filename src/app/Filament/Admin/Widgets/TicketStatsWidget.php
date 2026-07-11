<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Ticket;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TicketStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected ?string $heading = 'Ringkasan Tiket';

    protected function getStats(): array
    {
        $pendingCount = Ticket::where('status', 'PENDING')->count();
        return [
            Stat::make('Tiket Masuk Hari Ini', Ticket::whereDate('created_at', today())->count())
                ->icon('heroicon-o-inbox-arrow-down')
                ->color('info'),

            Stat::make('Menunggu Konfirmasi', $pendingCount)
                ->icon('heroicon-o-clock')
                ->color($pendingCount > 0 ? 'danger' : 'gray')
                ->description($pendingCount > 0 ? 'Perlu tindakan segera!' : 'Semua tiket sudah diproses'),

            Stat::make('Dalam Pengerjaan', Ticket::whereIn('status', ['ASSIGNED', 'ON_THE_WAY', 'DIAGNOSIS', 'WAITING_PART', 'REPAIR'])->count())
                ->icon('heroicon-o-wrench')
                ->color('primary'),

            Stat::make('Selesai Bulan Ini', Ticket::where('status', 'COMPLETED')->whereMonth('updated_at', now()->month)->count())
                ->icon('heroicon-o-check-circle')
                ->color('success'),
        ];
    }
}
