<?php

namespace App\Filament\Admin\Widgets;

use App\Helpers\CategoryHelper;
use App\Models\Technician;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TechnicianRatingWidget extends BaseWidget
{
    protected static ?string $heading = 'Performa Teknisi';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Technician::with('user')->withCount('tickets'))
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama Teknisi'),

                Tables\Columns\TextColumn::make('skill_category')
                    ->label('Keahlian')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => CategoryHelper::label($state))
                    ->color(fn (?string $state) => CategoryHelper::filamentColor($state)),

                Tables\Columns\TextColumn::make('tickets_count')
                    ->label('Total Tiket')
                    ->sortable(),

                Tables\Columns\TextColumn::make('average_rating')
                    ->label('Rating')
                    ->formatStateUsing(fn ($state) => $state . ' ⭐')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_available')
                    ->label('Tersedia')
                    ->boolean(),
            ]);
    }
}
