<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BusinessCardResource\Pages;
use App\Filament\Resources\BusinessCardResource\Schemas\BusinessCardForm;
use App\Filament\Resources\BusinessCardResource\Tables\BusinessCardsTable;
use App\Models\BusinessCard;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class BusinessCardResource extends Resource
{
    protected static ?string $model = BusinessCard::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-identification';
    protected static ?string $navigationLabel = 'Business Cards';

    public static function form(Schema $schema): Schema
    {
        return BusinessCardForm::form($schema);
    }

    public static function table(Table $table): Table
    {
        return BusinessCardsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBusinessCards::route('/'),
            'create' => Pages\CreateBusinessCard::route('/create'),
            'edit'   => Pages\EditBusinessCard::route('/{record}/edit'),
        ];
    }
}