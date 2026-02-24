<?php

namespace App\Filament\Resources\BusinessCardResource\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action as TableAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class BusinessCardsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo')
                    ->circular()
                    ->defaultImageUrl(fn () => 'https://ui-avatars.com/api/?background=ab0b37&color=fff&name=?'),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('position')->searchable(),
                TextColumn::make('slug')
                    ->label('Public URL')
                    ->formatStateUsing(fn ($state) => '/card/' . $state)
                    ->copyable()
                    ->copyMessage('URL copied!')
                    ->color('warning'),
                IconColumn::make('is_active')->boolean()->label('Active'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')->label('Active'),
            ])
            ->recordActions([
                TableAction::make('view_card')
                    ->label('View Card')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn ($record) => route('card.show', $record->slug))
                    ->openUrlInNewTab(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}