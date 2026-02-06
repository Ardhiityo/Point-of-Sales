<x-filament::page>
    <main class="grid grid-cols-12">
        <section class="col-span-9">
            <div class="flex flex-wrap gap-3">
                @foreach($this->products as $product)
                    <div class="bg-gray-900 text-white rounded-xl shadow pb-2 w-[200px] border border-2 border-gray-600">
                        <img src="{{ Storage::url($product->image_path) }}" class="rounded h-40 w-full object-cover">
                        <section class="flex flex-col gap-1 p-3">
                            <div class="font-semibold">{{ $product->name }}</div>
                            <div class="text-gray-400 text-sm">{{ $product->variant->name }}</div>
                            <div class="text-blue-400 font-semibold">Rp
                                {{ number_format($product->sales_price, 0, ",", ".") }}
                            </div>
                            <x-filament::button wire:click="addToCart({{ $product->id }})" color="primary">
                                Tambah
                            </x-filament::button>
                        </section>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="col-span-3">
            <div class="bg-gray-800 p-4 rounded-md">
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

                        <div class="flex items-center justify-between">
                            <div>Subtotal</div>
                            <div>Rp {{ number_format($this->subtotal, 0, ",", ".") }}</div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>Total Bayar</div>
                            <div>Rp {{ number_format($this->grandtotal, 0, ",", ".") }}</div>
                        </div>

                        <div class="flex gap-2 mt-2">
                            <x-filament::button wire:click="clearCart()" color="gray">
                                Bersihkan
                            </x-filament::button>
                            <x-filament::button wire:click="checkout()" color="success">
                                Checkout
                            </x-filament::button>
                        </div>
                    </div>
                </div>
        </section>
    </main>
</x-filament::page>