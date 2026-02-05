<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    public function products()
    {
        return $this->hasMany(Product::class, 'variant_id', 'id');
    }
}
