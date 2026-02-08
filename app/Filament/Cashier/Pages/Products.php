<?php

namespace App\Filament\Cashier\Pages;

use Throwable;
use App\Models\Product;
use App\Models\Variant;
use Filament\Pages\Page;
use Illuminate\View\View;
use App\Models\Transaction;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Livewire\WithPagination;
use Filament\Facades\Filament;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class Products extends Page implements HasForms, HasActions
{
    use WithPagination;
    use InteractsWithForms;
    use InteractsWithActions;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ShoppingCart;

    protected string $view = 'filament.cashier.pages.products';

    public array $search = [
        'product_name' => '',
        'variant' => '',
    ];

    public array $checkout = [
        'customer' => '',
        'payment_method' => '',
        'payment_amount' => 0,
        'balance_returned' => 0
    ];

    public array $cart_ids = [];

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
            ->statePath('search');
    }

    #[Computed]
    public function products()
    {
        return Product::whereLike('name', "{$this->search['product_name']}%")
            ->when(
                $this->search['variant'],
                fn($query) => $query->where('variant_id', $this->search['variant'])
            )
            ->paginate(1);
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
                            ->numeric()
                            ->reactive()
                            ->debounce(500)
                            ->afterStateUpdated(function ($state, callable $set) {
                                $balance_returned = $state - $this->grandtotal();
                                $set('balance_returned', $balance_returned < 0 ? 0 : $balance_returned);
                            })
                            ->placeholder('Payment Amount')
                            ->required(),
                        Select::make('payment_method')
                            ->options([
                                'cash' => 'Cash',
                                'cashless' => 'Cashless',
                            ])
                            ->required()
                            ->exists('transactions', 'payment_method'),
                        Placeholder::make('denominations')->content(view('filament.cashier.components.dedominations')),
                    ])
            ])
            ->statePath('checkout');
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
        $validated =  $this->validate([
            'checkout.customer' => 'required|string|min:3',
            'checkout.payment_amount' => 'required|numeric|gte:' . $this->grandtotal(),
            'checkout.payment_method' => 'required|string',
            'checkout.balance_returned' => 'required|numeric|min:0',
        ]);

        $products = Product::whereIn('id', array_unique($this->cart_ids))->get();

        $transaction_products = $this->carts->map(function ($cart) use ($products) {
            $product = $products->where('id', $cart['id'])->first();
            return [
                'product_id' => $product->id,
                'quantity' => $cart['quantity'],
                'cost_subtotal' => $product->cost_price * $cart['quantity'],
                'cost_grandtotal' => $product->cost_price * $cart['quantity'],
                'sales_subtotal' => $product->sales_price * $cart['quantity'],
                'sales_grandtotal' => $product->sales_price * $cart['quantity'],
                'profit' => ($product->sales_price * $cart['quantity']) - ($product->cost_price * $cart['quantity']),
            ];
        });

        try {
            DB::beginTransaction();
            $transaction = Transaction::create([
                'trx_id' => 'TRX' . time() . rand(1000, 9999),
                'customer' => data_get($validated, 'checkout.customer'),
                'payment_amount' => data_get($validated, 'checkout.payment_amount'),
                'balance_returned' => data_get($validated, 'checkout.balance_returned'),
                'payment_method' => data_get($validated, 'checkout.payment_method'),
                'cost_subtotal' => $transaction_products->sum('cost_subtotal'),
                'cost_grandtotal' => $transaction_products->sum('cost_grandtotal'),
                'sales_subtotal' => $transaction_products->sum('sales_subtotal'),
                'sales_grandtotal' => $transaction_products->sum('sales_grandtotal'),
                'profit' => $transaction_products->sum('profit'),
            ]);

            $transaction_products->each(function ($transaction_product) use ($transaction) {
                $transaction->products()->attach($transaction_product['product_id'], [
                    'quantity' => $transaction_product['quantity'],
                    'cost_subtotal' => $transaction_product['cost_subtotal'],
                    'cost_grandtotal' => $transaction_product['cost_grandtotal'],
                    'sales_subtotal' => $transaction_product['sales_subtotal'],
                    'sales_grandtotal' => $transaction_product['sales_grandtotal'],
                    'profit' => $transaction_product['profit'],
                ]);
            });

            DB::commit();

            Notification::make()
                ->title('Berhasil!')
                ->body('Transaksi berhasil diproses.')
                ->success()
                ->send();
        } catch (Throwable $th) {
            DB::rollBack();

            Notification::make()
                ->title('Gagal!')
                ->body($th->getMessage())
                ->error()
                ->send();
        }

        // Close modal
        $this->showCheckoutModal = false;

        return redirect()->to(Filament::getUrl());
    }
}
