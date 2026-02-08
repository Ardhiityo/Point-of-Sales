<?php

namespace App\Filament\Resources\Transactions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('trx_id')
                    ->required(),
                TextInput::make('customer')
                    ->required(),
                TextInput::make('payment_amount')
                    ->required()
                    ->numeric(),
                TextInput::make('balance_returned')
                    ->required()
                    ->numeric(),
                Select::make('payment_method')
                    ->options(['cash' => 'Cash', 'cashless' => 'Cashless'])
                    ->required(),
                TextInput::make('cost_subtotal')
                    ->required()
                    ->numeric(),
                TextInput::make('cost_grandtotal')
                    ->required()
                    ->numeric(),
                TextInput::make('sales_subtotal')
                    ->required()
                    ->numeric(),
                TextInput::make('sales_grandtotal')
                    ->required()
                    ->numeric(),
                TextInput::make('profit')
                    ->required()
                    ->numeric(),
            ]);
    }
}
