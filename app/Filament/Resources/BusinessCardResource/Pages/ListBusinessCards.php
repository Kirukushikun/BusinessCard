<?php

namespace App\Filament\Resources\BusinessCardResource\Pages;

use App\Filament\Resources\BusinessCardResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBusinessCards extends ListRecords
{
    protected static string $resource = BusinessCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
