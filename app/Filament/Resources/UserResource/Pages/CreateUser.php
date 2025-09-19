<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterCreate(): void
    {
        // Sync legacy role dengan Shield roles
        $record = $this->getRecord();
        
        if ($record->role) {
            // Assign Shield role berdasarkan legacy role
            $record->assignRole($record->role);
        }
    }
}
