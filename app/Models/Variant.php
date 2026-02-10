<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Variant extends Model
{
    protected static function booted()
    {
        static::deleting(function ($variant) {
            Product::where('variant_id', $variant->id)
                ->chunk(100, function ($products) {
                    foreach ($products as $product) {
                        if (Storage::disk('public')->exists($product->image_path)) {
                            Storage::disk('public')->delete($product->image_path);
                        }
                    }
                });
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'variant_id', 'id');
    }
}
