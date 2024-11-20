<?php

namespace Biostate\FilamentMenuBuilder\Views;

use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class Menu extends Component
{
    public $menu;

    public $menuItems;

    public function __construct(string $slug)
    {
        $this->menu = \Biostate\FilamentMenuBuilder\Models\Menu::query()
            ->where('slug', $slug)
            ->first();

        if (! $this->menu) {
            return;
        }

        $lastUpdated = $this->menu->getAttribute('updated_at')?->format('Y-m-d-h:i:s');
        $slug = $this->menu->getAttribute('slug');

        $this->menuItems = Cache::remember('menu-component-'.$slug.'-'.$lastUpdated, 60, function () {
            return $this->menu->items()->with('menuable')->get()->toTree();
        });
    }

    public function render()
    {
        return view('filament-menu-builder::components.menu');
    }
}
