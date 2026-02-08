<x-filament::page>
    <main class="grid grid-cols-1 md:grid-cols-12 gap-6">
        <section class="md:col-span-8 lg:col-span-9 order-last md:order-first">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($this->products as $product)
                    <div class="bg-gray-900 text-white rounded-xl shadow pb-2 border border-gray-600 flex flex-col h-full">
                        <img src="{{ Storage::url($product->image_path) }}" class="rounded-t-xl h-40 w-full object-cover">
                        <section class="flex flex-col gap-1 p-3 grow">
                            <div class="font-semibold line-clamp-1">{{ $product->name }}</div>
                            <div class="text-gray-400 text-sm">{{ $product->variant->name }}</div>
                            <div class="text-blue-400 font-semibold mb-2">Rp
                                {{ number_format($product->sales_price, 0, ',', '.') }}
                            </div>
                            <div class="mt-auto">
                                <x-filament::button wire:click="addToCart({{ $product->id }})" color="primary"
                                    class="w-full">
                                    Tambah
                                </x-filament::button>
                            </div>
                        </section>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="md:col-span-4 lg:col-span-3">
            <div class="bg-gray-800 p-4 rounded-md sticky top-4">
                <h3 class="text-xl flex items-center gap-2 font-semibold">
                    <x-filament::icon icon="heroicon-m-shopping-bag" class="w-6 h-6" />
                    Keranjang Belanja
                </h3>
                <div class="flex flex-col gap-2 mt-3">
                    @forelse($this->carts as $cart)
                        <div class="flex justify-between items-center border border-gray-600 p-2 rounded-md bg-gray-900">
                            <div class="flex flex-col text-sm">
                                <div class="font-semibold">{{ $cart['name'] }}</div>
                                <div class="text-sm">
                                    <p class="text-gray-400">{{ $cart['quantity'] }}x</p>
                                    <p class="text-blue-400 font-semibold">Rp
                                        {{ number_format($cart['sales_price'], 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                            <x-filament::button wire:click="removeFromCart({{ $cart['id'] }})" color="danger" size="sm">
                                <x-filament::icon icon="heroicon-m-trash" class="w-4 h-4" />
                            </x-filament::button>
                        </div>
                    @empty
                        <div class="text-center text-gray-400 py-3">Keranjang Belanja Kosong</div>
                    @endforelse
                    <div>

                        <div class="flex items-center justify-between mt-4">
                            <div class="text-gray-400">Subtotal</div>
                            <div class="font-semibold">Rp {{ number_format($this->subtotal, 0, ',', '.') }}</div>
                        </div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="text-gray-400">Grandtotal</div>
                            <div class="font-semibold text-xl">Rp
                                {{ number_format($this->grandtotal, 0, ',', '.') }}
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <x-filament::button wire:click="clearCart()" color="gray" class="flex-1">
                                Bersihkan
                            </x-filament::button>
                            <x-filament::button wire:click="openCheckout()" :disabled="empty($this->cart_ids)"
                                color="success" class="flex-1">
                                Checkout
                            </x-filament::button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @if ($showCheckoutModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <!-- Modal Backdrop -->
            <div class="fixed inset-0 z-40 bg-black/50" wire:click="$set('showCheckoutModal', false)"></div>

            <!-- Modal -->
            <div class="relative z-50 w-full max-w-lg rounded-lg bg-white shadow-xl dark:bg-gray-800">
                <!-- Modal Header -->
                <div class="border-b border-gray-200 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Proses Checkout</h2>
                </div>

                <!-- Modal Body -->
                <form wire:submit="submitCheckout" class="px-6 py-4">

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="bg-blue-600 text-white p-4 rounded-lg">
                            <div class="flex items-center gap-2 mb-1">
                                <x-filament::icon icon="heroicon-m-banknotes" class="w-5 h-5" />
                                <span class="text-sm font-medium">Total Belanja</span>
                            </div>
                            <div class="text-2xl font-bold">
                                Rp {{ number_format($this->grandtotal, thousands_separator: '.') }}
                            </div>
                        </div>

                        <div class="bg-red-500 text-white p-4 rounded-lg">
                            <div class="flex items-center gap-2 mb-1">
                                <x-filament::icon icon="heroicon-m-arrow-path" class="w-5 h-5" />
                                <span class="text-sm font-medium">Kembalian</span>
                            </div>
                            <div class="text-2xl font-bold">
                                Rp
                                {{ number_format(data_get($this->checkout, 'balance_returned'), thousands_separator: '.') }}
                            </div>
                        </div>
                    </div>

                    <div>
                        {{ $this->modalForm }}
                    </div>

                    <!-- Modal Footer -->
                    <div class="border-t border-gray-200 mt-6 pt-4 flex gap-2 justify-end">
                        <x-filament::button wire:click="save()" :disabled="empty($this->cart_ids)" color="success"
                            class="flex-1">
                            Bayar & Simpan
                        </x-filament::button>
                        <x-filament::button wire:click="saveAndPrint()" :disabled="empty($this->cart_ids)" color="success"
                            class="flex-1">
                            Bayar & Cetak
                        </x-filament::button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</x-filament::page>