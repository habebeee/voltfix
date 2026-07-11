<?php

namespace App\Filament\Admin\Resources;

use App\Helpers\CategoryHelper;
use App\Filament\Admin\Resources\TechnicianResource\Pages;
use App\Models\Technician;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TechnicianResource extends Resource
{
    protected static ?string $model = Technician::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationGroup = 'Manajemen Servis';

    protected static ?string $navigationLabel = 'Data Teknisi';

    protected static ?string $modelLabel = 'Teknisi';

    protected static ?string $pluralModelLabel = 'Data Teknisi';

    protected static ?int $navigationSort = 2;

    public static function canCreate(): bool
    {
        return auth()->user()?->role === 'ADMIN';
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->role === 'ADMIN';
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->role === 'ADMIN';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Akun Pengguna')
                ->description('Data login untuk teknisi ini.')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('tech_name')
                        ->label('Nama Lengkap')
                        ->required()
                        ->maxLength(255)
                        ->dehydrated(false),

                    Forms\Components\TextInput::make('tech_email')
                        ->label('Email')
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->dehydrated(false),

                    Forms\Components\TextInput::make('tech_phone')
                        ->label('Nomor WhatsApp')
                        ->tel()
                        ->required()
                        ->placeholder('628xxxx')
                        ->dehydrated(false),

                    Forms\Components\TextInput::make('tech_password')
                        ->label('Password')
                        ->password()
                        ->dehydrated(false)
                        ->required(fn (string $operation) => $operation === 'create')
                        ->placeholder('Kosongkan jika tidak diubah'),
                ]),

            Forms\Components\Section::make('Profil Teknisi')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('skill_category')
                        ->label('Keahlian Utama')
                        ->options(CategoryHelper::options())
                        ->required(),

                    Forms\Components\TextInput::make('experience')
                        ->label('Pengalaman')
                        ->placeholder('Contoh: 5 tahun'),

                    Forms\Components\Toggle::make('is_available')
                        ->label('Status Tersedia')
                        ->default(true)
                        ->helperText('Matikan jika teknisi sedang cuti/tidak aktif.'),

                    Forms\Components\TextInput::make('average_rating')
                        ->label('Rating Rata-rata')
                        ->numeric()
                        ->disabled()
                        ->helperText('Diperbarui otomatis dari ulasan pelanggan.'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.phone')
                    ->label('No. WhatsApp')
                    ->searchable(),

                Tables\Columns\TextColumn::make('skill_category')
                    ->label('Keahlian')
                    ->badge()
                    ->color(fn ($state) => CategoryHelper::filamentColor($state))
                    ->formatStateUsing(fn ($state) => CategoryHelper::shortLabel($state)),

                Tables\Columns\TextColumn::make('experience')
                    ->label('Pengalaman'),

                Tables\Columns\IconColumn::make('is_available')
                    ->label('Tersedia')
                    ->boolean(),

                Tables\Columns\TextColumn::make('average_rating')
                    ->label('Rating')
                    ->formatStateUsing(fn ($state) => number_format($state, 1) . ' ⭐')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tickets_count')
                    ->label('Total Tiket')
                    ->counts('tickets')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('skill_category')
                    ->label('Keahlian')
                    ->options(CategoryHelper::options()),

                Tables\Filters\TernaryFilter::make('is_available')
                    ->label('Status Ketersediaan'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTechnicians::route('/'),
            'create' => Pages\CreateTechnician::route('/create'),
            'edit'   => Pages\EditTechnician::route('/{record}/edit'),
        ];
    }
}
