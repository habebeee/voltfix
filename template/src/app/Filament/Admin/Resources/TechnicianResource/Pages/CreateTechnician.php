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
        $user = User::create([
            'name'     => $data['tech_name'],
            'email'    => $data['tech_email'],
            'phone'    => $data['tech_phone'],
            'password' => Hash::make($data['tech_password'] ?? 'password'),
            'role'     => 'TECHNICIAN',
        ]);

        $data['user_id'] = $user->id;

        unset($data['tech_name'], $data['tech_email'], $data['tech_phone'], $data['tech_password']);

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
