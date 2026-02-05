<x-filament::page>
    <main class="grid grid-cols-12">
        <section class="col-span-10">
            <div class="flex flex-wrap gap-3">
                @foreach($this->getProducts() as $product)
                    <div class="bg-gray-900 text-white rounded-xl shadow pb-2 w-[230px] border border-2 border-gray-600">
                        <img src="{{ Storage::url($product->image_path) }}" class="rounded h-40 w-full object-cover">
                        <section class="flex flex-col gap-1 p-3">
                            <div class="font-semibold">{{ $product->name }}</div>
                            <div class="text-gray-400 text-sm">{{ $product->variant->name }}</div>
                            <div class="text-blue-400 font-semibold">Rp
                                {{ number_format($product->sales_price, 0, ",", ".") }}
                            </div>
                            <button wire:click="addToCart({{ $product->id }})"
                                class="mt-2 w-full bg-blue-600 hover:bg-blue-800 rounded-md py-2 font-semibold">
                                + Tambah
                            </button>
                        </section>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="col-span-2">
            <div class="bg-gray-800 p-2 rounded-md">
                <h3 class="text-xl font-semibold mb-3">Keranjang Belanja</h1>
                    <div class="flex flex-col gap-2">
                        {{-- @foreach($this->getCart() as $cart) --}}
                        <div class="flex flex-col border border-gray-600 p-2 rounded-md bg-gray-900">
                            <div class="font-semibold">Product 1</div>
                            <div class="text-sm">
                                <p class="text-gray-400">1x</p>
                                <p class="text-blue-400 font-semibold">Rp 10.000</p>
                            </div>
                        </div>
                        {{-- @endforeach --}}
                        <div>
                            <div class="flex items-center justify-between">
                                <div>Subtotal</div>
                                <div>Rp 10.000</div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>Total Bayar</div>
                                <div>Rp 10.000</div>
                            </div>
                            <div class="flex gap-2">
                                <button class="mt-2 w-full bg-red-600 hover:bg-red-800 rounded-md py-2 font-semibold">
                                    Bersihkan
                                </button>
                                <button class="mt-2 w-full bg-blue-600 hover:bg-blue-800 rounded-md py-2 font-semibold">
                                    Checkout
                                </button>
                            </div>
                        </div>
                    </div>
        </section>
    </main>
</x-filament::page>