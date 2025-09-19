<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        // Sync legacy role dengan Shield roles
        $record = $this->getRecord();
        
        if ($record->role) {
            // Remove all existing roles first
            $record->syncRoles([]);
            // Assign new Shield role berdasarkan legacy role
            $record->assignRole($record->role);
        }
    }
}
