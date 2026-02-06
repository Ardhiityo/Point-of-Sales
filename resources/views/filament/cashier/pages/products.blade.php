<x-filament::page>
    <main class="grid grid-cols-1 md:grid-cols-12 gap-6">
        <section class="md:col-span-8 lg:col-span-9 order-last md:order-first">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($this->products as $product)
                    <div class="bg-gray-900 text-white rounded-xl shadow pb-2 border border-gray-600 flex flex-col h-full">
                        <img src="{{ Storage::url($product->image_path) }}" class="rounded-t-xl h-40 w-full object-cover">
                        <section class="flex flex-col gap-1 p-3 flex-grow">
                            <div class="font-semibold line-clamp-1">{{ $product->name }}</div>
                            <div class="text-gray-400 text-sm">{{ $product->variant->name }}</div>
                            <div class="text-blue-400 font-semibold mb-2">Rp
                                {{ number_format($product->sales_price, 0, ",", ".") }}
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
                        <div class="flex flex-col border border-gray-600 p-2 rounded-md bg-gray-900">
                            <div class="font-semibold">{{ $cart['name'] }}</div>
                            <div class="text-sm">
                                <p class="text-gray-400">{{ $cart['quantity'] }}x</p>
                                <p class="text-blue-400 font-semibold">Rp
                                    {{ number_format($cart['sales_price'], 0, ",", ".") }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-400 py-3">Keranjang Belanja Kosong</div>
                    @endforelse
                    <div>

                        <div class="flex items-center justify-between mt-4">
                            <div class="text-gray-400">Subtotal</div>
                            <div class="font-semibold">Rp {{ number_format($this->subtotal, 0, ",", ".") }}</div>
                        </div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="text-gray-400">Grandtotal</div>
                            <div class="font-semibold text-xl">Rp
                                {{ number_format($this->grandtotal, 0, ",", ".") }}
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <x-filament::button wire:click="clearCart()" color="gray" class="flex-1">
                                Bersihkan
                            </x-filament::button>
                            <x-filament::button wire:click="checkout()" color="success" class="flex-1">
                                Checkout
                            </x-filament::button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-filament::page>