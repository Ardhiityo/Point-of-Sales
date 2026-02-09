<?php

namespace App\Filament\App\Resources\Variants;

use App\Filament\App\Resources\Variants\Pages\CreateVariant;
use App\Filament\App\Resources\Variants\Pages\EditVariant;
use App\Filament\App\Resources\Variants\Pages\ListVariants;
use App\Filament\App\Resources\Variants\Schemas\VariantForm;
use App\Filament\App\Resources\Variants\Tables\VariantsTable;
use App\Models\Variant;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VariantResource extends Resource
{
    protected static ?string $model = Variant::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Tag;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return VariantForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VariantsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVariants::route('/')
        ];
    }
}
