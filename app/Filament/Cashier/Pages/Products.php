<?php

namespace App\Filament\Cashier\Pages;

use App\Models\Product;
use App\Models\Variant;
use Filament\Pages\Page;
use Illuminate\View\View;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Livewire\Attributes\Computed;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Concerns\InteractsWithActions;

class Products extends Page implements HasForms, HasTable, HasActions
{
    use InteractsWithForms;
    use InteractsWithTable;
    use InteractsWithActions;

    protected string $view = 'filament.cashier.pages.products';

    public array $data = [
        'product_name' => '',
        'variant' => '',
    ];

    public array $cart_ids = [];

    public string $customer = '';

    public string $payment_method = '';

    public int $payment_amount = 0;

    public bool $showCheckoutModal = false;

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
                        TextInput::make('product_name')
                            ->placeholder('Search products...')
                            ->prefixIcon('heroicon-m-magnifying-glass')
                            ->hiddenLabel()
                            ->live(debounce: 500)
                            ->suffixAction(
                                Action::make('clear')
                                    ->icon('heroicon-m-x-mark')
                                    ->color('gray')
                                    ->action(function ($set) {
                                        $set('product_name', '');
                                    })
                            )
                            ->columnSpan(['md' => 2]),
                        Select::make('variant')
                            ->options(Variant::pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->placeholder('All Variants')
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
        return Product::whereLike('name', "{$this->data['product_name']}%")
            ->when(
                $this->data['variant'],
                fn($query) => $query->where('variant_id', $this->data['variant'])
            )
            ->get();
    }

    public function modalForm(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Grid::make(2)
                    ->schema([
                        TextInput::make('customer')
                            ->placeholder('Customer Name')
                            ->required(),
                        TextInput::make('payment_amount')
                            ->placeholder('Payment Amount')
                            ->required(),
                        Select::make('payment_method')
                            ->options([
                                'cash' => 'Cash',
                                'cashless' => 'Cashless',
                            ])
                            ->required(),
                        Placeholder::make('denominations')->content(view('filament.cashier.components.dedominations')),
                    ])
            ])
            ->statePath('data');
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

    public function removeFromCart($id)
    {
        $this->cart_ids = array_values(array_diff($this->cart_ids, [$id]));
    }

    public function clearCart()
    {
        $this->cart_ids = [];
    }

    public function openCheckout()
    {
        $this->showCheckoutModal = true;
    }

    public function save()
    {
        // Validasi data
        $this->validate([
            'checkoutData.customer_name' => 'required|string|min:3',
            'checkoutData.phone' => 'nullable|string',
            'checkoutData.notes' => 'nullable|string',
        ]);

        // Proses checkout
        logger('Checkout data:', $this->checkoutData);

        Notification::make()
            ->title('Berhasil!')
            ->body('Transaksi berhasil diproses.')
            ->success()
            ->send();

        // Reset form
        $this->checkoutData = [
            'customer_name' => '',
            'phone' => '',
            'notes' => '',
        ];

        // Reset keranjang
        $this->clearCart();

        // Close modal
        $this->dispatch('close-modal', id: 'checkout');
    }
}
