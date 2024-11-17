<x-filament-panels::page>
    <div style="
        display: grid;
        grid-template-columns: 4fr 8fr;
        grid-gap: 1rem;
    ">
        <div>
            @livewire('menu-item-form', ['menuId' => $this->record->id])
        </div>
        <div>
            @livewire('menu-builder', ['menuId' => $this->record->id])
        </div>
    </div>
</x-filament-panels::page>

