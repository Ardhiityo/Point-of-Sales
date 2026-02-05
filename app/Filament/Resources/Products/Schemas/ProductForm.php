<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

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
                        ->label('Variant')
                        ->required(),
                    TextInput::make('cost_price')
                        ->required()
                        ->numeric()
                        ->prefix('$'),
                    TextInput::make('sales_price')
                        ->required()
                        ->numeric()
                        ->prefix('$'),
                ])->columnSpanFull()
            ]);
    }
}
