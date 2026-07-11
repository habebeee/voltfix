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
        $updateData = [
            'name'  => $data['tech_name'],
            'email' => $data['tech_email'],
            'phone' => $data['tech_phone'],
        ];

        if (! empty($data['tech_password'])) {
            $updateData['password'] = Hash::make($data['tech_password']);
        }

        $this->record->user->update($updateData);

        unset($data['tech_name'], $data['tech_email'], $data['tech_phone'], $data['tech_password']);

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
