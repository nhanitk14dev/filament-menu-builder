<?php

namespace Biostate\FilamentMenuBuilder\Filament\Resources;

use Biostate\FilamentMenuBuilder\Models\MenuItem;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MenuItemResource extends Resource
{
    protected static ?string $model = MenuItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3';

    public static function getNavigationGroup(): ?string
    {
        return __('filament-menu-builder::menu-builder.navigation_group');
    }

    /**
     * @return string|null
     */
    public static function getModelLabel(): string
    {
        return __('filament-menu-builder::menu-builder.menu_item');
    }

    /**
     * @return string|null
     */
    public static function getPluralModelLabel(): string
    {
        return __('filament-menu-builder::menu-builder.menu_items');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(static::getFormSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('url'),
                Tables\Columns\TextColumn::make('menu.name'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getFormSchema()
    {
        return [
            TextInput::make('name')
                ->autofocus()
                ->required()
                ->maxLength(255),
            TextInput::make('icon')
                ->maxLength(255),
            Select::make('target')
                ->required()
                ->default('_self')
                ->options([
                    '_self' => 'Same window',
                    '_blank' => 'New window',
                ]),
            TextInput::make('link_class')
                ->maxLength(255),
            TextInput::make('wrapper_class')
                ->maxLength(255),
            Select::make('menuable_type')
                ->options(
                    array_flip(config('filament-menu-builder.models', []))
                )
                ->reactive()
                ->afterStateUpdated(fn (callable $set) => $set('menuable_id', null))
                ->hidden(fn () => empty(config('filament-menu-builder.models', []))),
            Select::make('menuable_id')
                ->searchable()
                ->options(fn ($get) => $get('menuable_type')::all()->pluck($get('menuable_type')::getFilamentSearchLabel(), 'id'))
                ->getSearchResultsUsing(function (string $search, callable $get) {
                    $className = $get('menuable_type');

                    return $className::filamentSearch($search)->pluck($className::getFilamentSearchLabel(), 'id');
                })
                ->getOptionLabelUsing(fn ($value, $get): ?string => $get('menuable_type')::find($value)?->getFilamentSearchOptionName())
                ->hidden(fn ($get) => $get('menuable_type') == null),
            TextInput::make('url')
                ->hidden(fn ($get) => $get('menuable_type') != null)
                ->maxLength(255),
            KeyValue::make('parameters')
                ->helperText('mega_menu, mega_menu_columns'),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\ListMenuItems::route('/'),
            'create' => \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\CreateMenuItem::route('/create'),
            'edit' => \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\EditMenuItem::route('/{record}/edit'),
        ];
    }
}
