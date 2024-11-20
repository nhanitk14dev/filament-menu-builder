<li
    class="{{ $item->wrapper_class }}"
>
    <a
        target="{{ $item->target }}"
        class="{{ $item->link_class }}"
        href="{{ $item->link }}"
    >
        {{ $item->menu_name }}
        @if(! $item->children->isEmpty())
            <ul>
                @foreach($item->children as $child)
                    @include('filament-menu-builder::components.menu-item', ['item' => $child])
                @endforeach
            </ul>
        @endif
    </a>
</li>
