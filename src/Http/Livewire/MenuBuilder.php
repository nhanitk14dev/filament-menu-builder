<?php

namespace Biostate\FilamentMenuBuilder\Http\Livewire;

use Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource;
use Biostate\FilamentMenuBuilder\Models\Menu;
use Biostate\FilamentMenuBuilder\Models\MenuItem;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Grid;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Support\Enums\ActionSize;
use Illuminate\Support\Collection;
use Livewire\Component;

class MenuBuilder extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public int $menuId;

    public Collection $items;

    public array $data = [];

    protected $listeners = [
        'menu-item-created' => 'fillItems',
    ];

    public function mount(int $menuId): void
    {
        $this->menuId = $menuId;
        $this->fillItems();
    }

    public function deleteAction(): Action
    {
        // TODO: extend action and make new delete action for this component
        return Action::make('delete')
            ->size(ActionSize::ExtraSmall)
            ->icon('heroicon-m-trash')
            ->iconButton()
            ->requiresConfirmation()
            ->modalHeading(__('filament-menu-builder::menu-builder.destroy_menu_item_heading'))
            ->modalDescription('Are you sure you want to delete this menu item? All items below will be deleted as well.')
            ->modalSubmitActionLabel(__('Destroy'))
            ->color('danger')
            ->action(function (array $arguments) {
                $menuItemId = $arguments['menuItemId'];

                $menuItem = MenuItem::find($menuItemId);
                if (! $menuItem) {
                    return;
                }
                MenuItem::descendantsOf($menuItem)->each(function (MenuItem $menuItem) {
                    $menuItem->delete();
                });

                $menuItem->delete();
            });
    }

    public function editAction(): Action
    {
        // TODO: extend action and make new edit action for this component
        return Action::make('edit')
            ->size(ActionSize::ExtraSmall)
            ->icon('heroicon-m-pencil')
            ->iconButton()
            ->fillForm(function (array $arguments) {
                $menuItemId = $arguments['menuItemId'];
                $menuItem = MenuItem::find($menuItemId);

                return [
                    'name' => $menuItem->name,
                    'icon' => $menuItem->icon,
                    'target' => $menuItem->target,
                    'link_class' => $menuItem->link_class,
                    'wrapper_class' => $menuItem->wrapper_class,
                    'menuable_type' => $menuItem->menuable_type,
                    'menuable_id' => $menuItem->menuable_id,
                    'url' => $menuItem->url,
                    'parameters' => $menuItem->parameters->toArray(),
                ];
            })
            ->form(fn () => [
                Grid::make()
                    ->schema(MenuItemResource::getFormSchema()),
            ])
            ->action(function (array $arguments, $data) {
                $menuItemId = $arguments['menuItemId'];

                $menuItem = MenuItem::find($menuItemId);
                if (! $menuItem) {
                    return;
                }

                $menuItem->update($data);
            });
    }

    public function createSubItemAction(): Action
    {
        // TODO: extend action and make new edit action for this component
        return Action::make('createSubItem')
            ->size(ActionSize::ExtraSmall)
            ->icon('heroicon-m-plus')
            ->iconButton()
            ->form(fn () => [
                Grid::make()
                    ->schema(MenuItemResource::getFormSchema()),
            ])
            ->action(function (array $arguments, $data) {
                $parent = MenuItem::find($arguments['menuItemId']);
                if (! $parent) {
                    return;
                }

                $menuItem = MenuItem::create([
                    ...$data,
                    'menu_id' => $this->menuId,
                ]);
                $parent->appendNode($menuItem);
            });
    }

    public function viewAction(): Action
    {
        // TODO: extend action and make new edit action for this component
        return Action::make('view')
            ->size(ActionSize::ExtraSmall)
            ->icon('heroicon-m-eye')
            ->iconButton()
            ->url(fn (array $arguments) => MenuItemResource::getUrl('edit', ['record' => $arguments['menuItemId']]));
    }

    public function goToLinkAction(): Action
    {
        // TODO: extend action and make new edit action for this component
        return Action::make('goToLink')
            ->size(ActionSize::ExtraSmall)
            ->icon('heroicon-m-link')
            ->iconButton()
            ->url(fn (array $arguments) => MenuItem::find($arguments['menuItemId'])->link);
    }

    public function duplicateAction(): Action
    {
        // TODO: extend action and make new edit action for this component
        return Action::make('duplicate')
            ->size(ActionSize::ExtraSmall)
            ->icon('heroicon-m-document-duplicate')
            ->iconButton()
            ->requiresConfirmation()
            ->modalDescription('Are you sure you want to duplicate this menu item?')
            ->action(function (array $arguments) {
                $menuItemId = $arguments['menuItemId'];

                $menuItem = MenuItem::find($menuItemId);
                if (! $menuItem) {
                    return;
                }

                $newMenuItem = $menuItem->replicate();
                $newMenuItem->name = $newMenuItem->name . ' (copy)';
                $newMenuItem->afterNode($menuItem)->save();

                // TODO: add extra action and name "Duplicate and Edit"
            });

    }


    public function render()
    {
        return view('filament-menu-builder::livewire.menu-builder');
    }

    public function fillItems(): void
    {
        $this->items = Menu::find($this->menuId)
            ->items()
            ->defaultOrder()
            ->get()
            ->toTree();
    }

    public function save(): void
    {
        if (empty($this->data)) {
            return;
        }

        MenuItem::rebuildTree($this->data);

        Notification::make()
            ->title(__('filament-menu-builder::menu-builder.menu_saved'))
            ->success()
            ->send();

        $this->fillItems();
    }
}
