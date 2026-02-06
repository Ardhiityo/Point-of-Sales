<?php

namespace App\Filament\Cashier\Pages;

use App\Models\Product;
use App\Models\Variant;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Livewire\Attributes\Computed;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;

class Products extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.cashier.pages.products';

    public array $data = [
        'search' => '',
        'category' => null,
    ];

    public array $cart_ids = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function getHeader(): ?View
    {
        return view('filament.cashier.pages.header');
    }

    public function header(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Grid::make(4)
                    ->schema([
                        TextInput::make('search')
                            ->placeholder('Search products...')
                            ->prefixIcon('heroicon-m-magnifying-glass')
                            ->hiddenLabel()
                            ->live(debounce: 500)
                            ->columnSpan(['md' => 2]),
                        Select::make('category')
                            ->options(Variant::pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->placeholder('All Categories')
                            ->hiddenLabel()
                            ->live()
                            ->columnSpan(['md' => 1]),
                    ])
            ])
            ->statePath('data');
    }

    #[Computed]
    public function products()
    {
        return Product::whereLike('name', "{$this->data['search']}%")->get();
    }

    #[Computed]
    public function carts()
    {
        $counts = array_count_values($this->cart_ids);

        $products = Product::whereIn('id', array_keys($counts))->get();

        return $products->map(function ($product) use ($counts) {
            $quantity = $counts[$product->id];
            return [
                'id' => $product->id,
                'name' => $product->name,
                'quantity' => $quantity,
                'sales_price' => $product->sales_price * $quantity,
            ];
        });
    }

    #[Computed]
    public function subtotal(): int
    {
        return $this->carts->sum('sales_price');
    }

    #[Computed]
    public function grandtotal(): int
    {
        return $this->subtotal();
    }

    public function addToCart($id)
    {
        $this->cart_ids[] = $id;
    }

    public function clearCart()
    {
        $this->cart_ids = [];
    }
}
