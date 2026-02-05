<?php

namespace App\Filament\Resources\Variants\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class VariantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Variant')
                    ->schema([
                        TextInput::make('name')
                            ->unique()
                            ->required(),
                    ])->columnSpanFull()
            ]);
    }
}
