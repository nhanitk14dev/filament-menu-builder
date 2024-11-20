<ul>
    @foreach($menuItems as $menuItem)
        @include('filament-menu-builder::components.menu-item', ['item' => $menuItem])
    @endforeach
</ul>
