<?php

namespace App\Filament\App\Resources\Products\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Product')->schema([
                    TextInput::make('name')
                        ->required(),
                    FileUpload::make('image_path')
                        ->label('image')
                        ->disk('public')
                        ->visibility('public')
                        ->image()
                        ->maxSize(1024)
                        ->required(),
                    Select::make('variant_id')
                        ->relationship('variant', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    TextInput::make('cost_price')
                        ->required()
                        ->numeric()
                        ->prefix('Rp '),
                    TextInput::make('sales_price')
                        ->required()
                        ->numeric()
                        ->prefix('Rp '),
                ])->columnSpanFull()
            ]);
    }
}
