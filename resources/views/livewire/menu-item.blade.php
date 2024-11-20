@php
    $isMegamenu = $item->parameters && $item->parameters->has('mega_menu') && $item->parameters->get('mega_menu') == true;
    $columns = $item->parameters && $item->parameters->has('mega_menu_columns') ? $item->parameters->get('mega_menu_columns') : 1;
@endphp

<div class="item" data-id="{{ $item->id }}" wire:key="{{'menu-item-'.$item->id}}">
    <div class="flex justify-between content-center mb-2 rounded bg-white border border-grey-500 shadow-sm pr-2">
        <div class="flex content-center items-center">
            <div class="border-r-2 border-grey-500 cursor-pointer bg-grey-400">
                <x-heroicon-o-arrows-up-down class="w-6 h-6 m-2 handle" />
            </div>
            <div class="ml-2">
                <span class="font-medium">{{ $item->name }}</span>
                <span class="text-xs text-gray-500">/  {{ $item->normalized_type }} @if($isMegamenu) / {{ $isMegamenu ? "Mega menü - ".$columns : "" }} @endif </span>
            </div>
        </div>
        <div class="flex gap-2 items-center [&_svg]:shrink-0">
            {{($this->createSubItemAction)(['menuItemId' => $item->id])}}
            {{($this->editAction)(['menuItemId' => $item->id])}}
            {{($this->duplicateAction)(['menuItemId' => $item->id])}}
            {{($this->viewAction)(['menuItemId' => $item->id])}}
            {{($this->goToLinkAction)(['menuItemId' => $item->id])}}
            {{($this->deleteAction)(['menuItemId' => $item->id])}}
        </div>
    </div>

    <div
        @class(['nested ml-[15px] grid gap-1 grid-cols-1' => true])
        data-id="{{ $item->id }}"
        x-data="{
            init(){
                new Sortable(this.$el, {
                    handle: '.handle',
                    group: 'nested',
                    animation: 150,
                    fallbackOnBody: true,
                    swapThreshold: 0.65,
                    onEnd: (evt) => {
                        this.data = getDataStructure(document.getElementById('parentNested'));
                    }
                })
            },
        }"
    >
        @foreach($item->children as $children)
            @include('filament-menu-builder::livewire.menu-item', ['item' => $children])
        @endforeach
    </div>
</div>
