@props(['paginator'])

@if ($paginator->hasPages())
    <nav class="flex items-center justify-between mt-8">
        <div class="text-sm text-gray-400">
            Showing
            <span class="font-medium text-white">{{ $paginator->firstItem() }}</span>
            to
            <span class="font-medium text-white">{{ $paginator->lastItem() }}</span>
            of
            <span class="font-medium text-white">{{ $paginator->total() }}</span>
            results
        </div>

        <div class="flex items-center gap-2">
            @if ($paginator->onFirstPage())
                <span class="p-3 text-sm rounded-md bg-gray-500 text-gray-500 cursor-not-allowed">
                    ← Prev
                </span>
            @else
                <button wire:click="previousPage" class="p-3 text-sm rounded-md bg-gray-800 hover:bg-gray-400">
                    ← Prev
                </button>
            @endif

            @if ($paginator->hasMorePages())
                <button wire:click="nextPage" class="p-3 text-sm rounded-md bg-gray-800 hover:bg-gray-400">
                    Next →
                </button>
            @else
                <span class="p-3 text-sm rounded-md bg-gray-500 text-gray-500 cursor-not-allowed">
                    Next →
                </span>
            @endif
        </div>
    </nav>
@endif