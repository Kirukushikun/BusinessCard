<?php

namespace App\Filament\Resources\BusinessCardResource\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BusinessCardForm
{
    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Personal Info')
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, callable $set, $get) {
                            if (empty($get('slug'))) {
                                $set('slug', Str::slug($state));
                            }
                        }),
                    TextInput::make('slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255)
                        ->helperText('Auto-generated from name. This will be the public URL.'),
                    TextInput::make('position')->maxLength(255),
                    TextInput::make('company')->maxLength(255),
                ])->columns(2),

            Section::make('Contact Details')
                ->schema([
                    TextInput::make('email')->email()->maxLength(255),
                    TextInput::make('work_phone')->tel()->maxLength(255),
                    TextInput::make('mobile')->tel()->maxLength(255),
                ])->columns(3),

            Section::make('Settings')
                ->schema([
                    FileUpload::make('photo')
                        ->image()
                        ->imageEditor()
                        ->directory('business-cards/photos')
                        ->columnSpan(1),
                    Toggle::make('is_active')
                        ->label('Active')
                        ->helperText('Inactive cards will return a 404.')
                        ->default(true)
                        ->columnSpan(1),
                ])->columns(2),
        ]);
    }
}