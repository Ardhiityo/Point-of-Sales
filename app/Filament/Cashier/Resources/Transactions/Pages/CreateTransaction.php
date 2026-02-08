<?php

namespace App\Filament\Cashier\Resources\Transactions\Pages;

use App\Filament\Cashier\Resources\Transactions\TransactionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;
}
