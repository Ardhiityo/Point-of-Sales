<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'transaction_product', 'transaction_id', 'product_id')->withPivot('cost_subtotal', 'cost_grandtotal', 'sales_subtotal', 'sales_grandtotal', 'profit', 'quantity');
    }
}
