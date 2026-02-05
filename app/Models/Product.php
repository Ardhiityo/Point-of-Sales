<?php

namespace App\Models;

use App\Models\Variant;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'transaction_product', 'product_id', 'transaction_id')->withPivot('cost_subtotal', 'cost_grandtotal', 'sales_subtotal', 'sales_grandtotal', 'profit', 'quantity');
    }

    public function variant()
    {
        return $this->belongsTo(Variant::class, 'variant_id', 'id');
    }
}
