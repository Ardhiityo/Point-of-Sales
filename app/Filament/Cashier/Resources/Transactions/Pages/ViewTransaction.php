<?php

namespace App\Filament\Cashier\Resources\Transactions\Pages;

use App\Filament\Cashier\Resources\Transactions\TransactionResource;
use Filament\Resources\Pages\ViewRecord;

class ViewTransaction extends ViewRecord
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
