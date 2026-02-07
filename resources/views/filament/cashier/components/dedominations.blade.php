<div class="grid grid-cols-3 gap-1 mt-2">
    <x-filament::button wire:click="$set('data.payment_amount', 10000)" color="success"
        class="w-full">10K</x-filament::button>
    <x-filament::button wire:click="$set('data.payment_amount', 20000)" color="success"
        class="w-full">20K</x-filament::button>
    <x-filament::button wire:click="$set('data.payment_amount', 50000)" color="success"
        class="w-full">50K</x-filament::button>
    <x-filament::button wire:click="$set('data.payment_amount', 100000)" color="success"
        class="w-full">100K</x-filament::button>
    <x-filament::button wire:click="$set('data.payment_amount', 150000)" color="success"
        class="w-full">150K</x-filament::button>
    <x-filament::button wire:click="$set('data.payment_amount', 200000)" color="success"
        class="w-full">200K</x-filament::button>
</div>