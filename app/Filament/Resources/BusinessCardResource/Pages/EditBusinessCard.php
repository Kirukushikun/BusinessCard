<?php

namespace App\Filament\Resources\BusinessCardResource\Pages;

use App\Filament\Resources\BusinessCardResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBusinessCard extends EditRecord
{
    protected static string $resource = BusinessCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
