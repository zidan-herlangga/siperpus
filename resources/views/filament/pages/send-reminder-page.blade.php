<x-filament::page>
    <div class="space-y-4">
        <x-filament::button wire:click="runCommand" color="primary" wire:loading.attr="disabled">
            <span wire:loading.remove> Jalankan Pengingat Sekarang </span>
            <span wire:loading> Menjalankan... </span>
        </x-filament::button>

        @if (session()->has('output'))
            <div class="p-4 bg-gray-100 rounded text-sm font-mono whitespace-pre-wrap">
                {{ session('output') }}
            </div>
        @endif
    </div>
</x-filament::page>
