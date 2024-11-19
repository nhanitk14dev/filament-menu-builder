@php
    $isMegamenu = $item->parameters && $item->parameters->has('mega_menu') && $item->parameters->get('mega_menu') == true;
    $columns = $item->parameters && $item->parameters->has('mega_menu_columns') ? $item->parameters->get('mega_menu_columns') : 1;
@endphp

<div class="item" data-id="{{ $item->id }}" wire:key="{{'menu-item-'.$item->id}}">
    <div class="flex justify-between">
    <div>
    <span>{{ $item->name }}</span>
    <span class="text-xs text-gray-500">/  {{ $item->normalized_type }} @if($isMegamenu) / {{ $isMegamenu ? "Mega menü - ".$columns : "" }} @endif </span>
    </div>
    <div class="flex gap-2">
        {{($this->createSubItemAction)(['menuItemId' => $item->id])}}
        {{($this->editAction)(['menuItemId' => $item->id])}}
        {{($this->viewAction)(['menuItemId' => $item->id])}}
        {{($this->goToLinkAction)(['menuItemId' => $item->id])}}
        {{($this->deleteAction)(['menuItemId' => $item->id])}}
    </div>
    </div>

    <div
        @class(['nested' => true, 'grid grid-cols-'.$columns.' gap-4' => $columns > 0])
        data-id="{{ $item->id }}"
        x-data="{
            init(){
                new Sortable(this.$el, {
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
