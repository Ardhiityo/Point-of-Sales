<?php

namespace App\Filament\Cashier\Pages;

use App\Models\Product;
use Filament\Pages\Page;

class Products extends Page
{
    protected string $view = 'filament.cashier.pages.products';

    protected static ?string $navigationLabel = 'Products';

    protected static ?string $title = 'All Products';

    protected ?string $subheading = 'Choose the product you want to order';

    public function getProducts()
    {
        return Product::paginate(16);
    }
}
