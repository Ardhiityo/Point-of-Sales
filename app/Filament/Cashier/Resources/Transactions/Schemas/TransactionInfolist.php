<?php

namespace App\Filament\Cashier\Resources\Transactions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TransactionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('trx_id'),
                TextEntry::make('customer'),
                TextEntry::make('payment_amount')
                    ->numeric(),
                TextEntry::make('balance_returned')
                    ->numeric(),
                TextEntry::make('payment_method')
                    ->badge(),
                TextEntry::make('cost_subtotal')
                    ->numeric(),
                TextEntry::make('cost_grandtotal')
                    ->numeric(),
                TextEntry::make('sales_subtotal')
                    ->numeric(),
                TextEntry::make('sales_grandtotal')
                    ->numeric(),
                TextEntry::make('profit')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
