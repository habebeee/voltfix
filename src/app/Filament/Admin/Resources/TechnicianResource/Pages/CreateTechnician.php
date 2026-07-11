<?php

namespace App\Filament\Admin\Resources\TechnicianResource\Pages;

use App\Filament\Admin\Resources\TechnicianResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateTechnician extends CreateRecord
{
    protected static string $resource = TechnicianResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $formData = $this->form->getState();

        $user = User::create([
            'name'     => $formData['tech_name'],
            'email'    => $formData['tech_email'],
            'phone'    => $formData['tech_phone'],
            'password' => Hash::make($formData['tech_password'] ?? 'password'),
            'role'     => 'TECHNICIAN',
        ]);

        $data['user_id'] = $user->id;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
