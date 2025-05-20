<?php

namespace App\Filament\Resources\DepartmenResource\Pages;

use App\Filament\Resources\DepartmenResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDepartmen extends EditRecord
{
    protected static string $resource = DepartmenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
