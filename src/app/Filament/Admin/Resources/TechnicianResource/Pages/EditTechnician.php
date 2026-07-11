<?php

namespace App\Filament\Admin\Resources\TechnicianResource\Pages;

use App\Filament\Admin\Resources\TechnicianResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;

class EditTechnician extends EditRecord
{
    protected static string $resource = TechnicianResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $user = $this->record->user;

        $data['tech_name']  = $user->name;
        $data['tech_email'] = $user->email;
        $data['tech_phone'] = $user->phone;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $formData = $this->form->getState();

        $updateData = [
            'name'  => $formData['tech_name'] ?? $this->record->user->name,
            'email' => $formData['tech_email'] ?? $this->record->user->email,
            'phone' => $formData['tech_phone'] ?? $this->record->user->phone,
        ];

        if (filled($formData['tech_password'] ?? null)) {
            $updateData['password'] = Hash::make($formData['tech_password']);
        }

        $this->record->user->update($updateData);

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
