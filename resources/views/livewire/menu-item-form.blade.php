<form wire:submit.prevent="submit">
    {{ $this->form }}

    <x-filament::button type="submit" class="ml-2">
        Add New
    </x-filament::button>
</form>
