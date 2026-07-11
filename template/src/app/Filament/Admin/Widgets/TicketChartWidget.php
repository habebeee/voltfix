<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Ticket;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class TicketChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Tiket per Bulan (12 Bulan Terakhir)';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $months = collect(range(11, 0))->map(fn ($i) => now()->subMonths($i));

        $labels = $months->map(fn ($m) => $m->format('M Y'))->toArray();

        $completed = $months->map(fn ($m) => Ticket::where('status', 'COMPLETED')
            ->whereYear('updated_at', $m->year)
            ->whereMonth('updated_at', $m->month)
            ->count()
        )->toArray();

        $submitted = $months->map(fn ($m) => Ticket::whereYear('created_at', $m->year)
            ->whereMonth('created_at', $m->month)
            ->count()
        )->toArray();

        return [
            'datasets' => [
                [
                    'label'           => 'Tiket Masuk',
                    'data'            => $submitted,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'borderColor'     => 'rgb(59, 130, 246)',
                    'borderWidth'     => 2,
                ],
                [
                    'label'           => 'Tiket Selesai',
                    'data'            => $completed,
                    'backgroundColor' => 'rgba(16, 185, 129, 0.2)',
                    'borderColor'     => 'rgb(16, 185, 129)',
                    'borderWidth'     => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
