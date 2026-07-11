<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="mt-6 flex items-center gap-3">
            <x-filament::button type="submit" size="lg">
                Simpan Perubahan
            </x-filament::button>

            <x-filament::button
                tag="a"
                href="{{ route('home') }}"
                target="_blank"
                color="gray"
                icon="heroicon-o-arrow-top-right-on-square"
            >
                Lihat Halaman
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
