<?php

namespace App\Filament\Admin\Resources\TicketResource\Pages;

use App\Filament\Admin\Resources\TicketResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTicket extends EditRecord
{
    protected static string $resource = TicketResource::class;

    private string $oldStatus;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Simpan status lama sebelum disimpan
        $this->oldStatus = $this->record->status;

        return $data;
    }

    protected function afterSave(): void
    {
        $newStatus = $this->record->status;

        if ($this->oldStatus !== $newStatus) {
            $this->record->logs()->create([
                'old_status' => $this->oldStatus,
                'new_status' => $newStatus,
                'note'       => 'Status diubah oleh admin melalui form edit.',
            ]);
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()->label('Lihat Detail'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}
