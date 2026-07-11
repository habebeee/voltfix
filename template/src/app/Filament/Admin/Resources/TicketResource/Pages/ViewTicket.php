<?php

namespace App\Filament\Admin\Resources\TicketResource\Pages;

use App\Filament\Admin\Resources\TicketResource;
use App\Models\Technician;
use App\Models\Ticket;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewTicket extends ViewRecord
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        $isAdmin   = auth()->user()?->role === 'ADMIN';
        $record    = $this->record;

        return array_filter([
            // Setujui — tampil hanya untuk ADMIN dan tiket PENDING
            $isAdmin && $record->status === 'PENDING'
                ? Actions\Action::make('approve')
                    ->label('Setujui Tiket')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Tiket?')
                    ->modalDescription('Tiket akan dipindah ke antrian penugasan teknisi.')
                    ->action(function () use ($record) {
                        $record->update(['status' => 'WAITING_ASSIGNMENT']);
                        $record->logs()->create([
                            'old_status' => 'PENDING',
                            'new_status' => 'WAITING_ASSIGNMENT',
                            'note'       => 'Disetujui oleh admin.',
                        ]);
                        $this->refreshFormData(['status']);
                        Notification::make()->title('Tiket disetujui.')->success()->send();
                    })
                : null,

            // Tolak — tampil hanya untuk ADMIN dan tiket PENDING
            $isAdmin && $record->status === 'PENDING'
                ? Actions\Action::make('reject')
                    ->label('Tolak Tiket')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->form([
                        Forms\Components\Textarea::make('rejection_reason')
                            ->label('Alasan Penolakan')
                            ->required()
                            ->rows(3),
                    ])
                    ->modalHeading('Tolak Tiket')
                    ->action(function (array $data) use ($record) {
                        $record->update([
                            'status'           => 'REJECTED',
                            'rejection_reason' => $data['rejection_reason'],
                        ]);
                        $record->logs()->create([
                            'old_status' => 'PENDING',
                            'new_status' => 'REJECTED',
                            'note'       => $data['rejection_reason'],
                        ]);
                        $this->refreshFormData(['status', 'rejection_reason']);
                        Notification::make()->title('Tiket ditolak.')->warning()->send();
                    })
                : null,

            // Tugaskan teknisi — ADMIN + tiket WAITING_ASSIGNMENT
            $isAdmin && $record->status === 'WAITING_ASSIGNMENT'
                ? Actions\Action::make('assign')
                    ->label('Tugaskan Teknisi')
                    ->icon('heroicon-o-user-plus')
                    ->color('primary')
                    ->form([
                        Forms\Components\Select::make('technician_id')
                            ->label('Pilih Teknisi (' . $record->category . ')')
                            ->options(
                                Technician::with('user')
                                    ->where('skill_category', $record->category)
                                    ->where('is_available', true)
                                    ->get()
                                    ->mapWithKeys(fn ($t) => [
                                        $t->id => $t->user->name . ' — ' . $t->average_rating . '⭐ (' . $t->experience . ')',
                                    ])
                            )
                            ->required()
                            ->searchable(),
                    ])
                    ->modalHeading('Tugaskan Teknisi')
                    ->action(function (array $data) use ($record) {
                        $record->update([
                            'status'        => 'ASSIGNED',
                            'technician_id' => $data['technician_id'],
                        ]);
                        $record->logs()->create([
                            'old_status' => 'WAITING_ASSIGNMENT',
                            'new_status' => 'ASSIGNED',
                            'note'       => 'Teknisi ditugaskan oleh admin.',
                        ]);
                        $this->refreshFormData(['status', 'technician_id']);
                        Notification::make()->title('Teknisi berhasil ditugaskan.')->success()->send();
                    })
                : null,

            // Tombol edit (ADMIN only)
            $isAdmin
                ? Actions\EditAction::make()->label('Edit Tiket')
                : null,
        ]);
    }
}
