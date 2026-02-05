<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TransactionProduct extends Pivot
{
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }
}
