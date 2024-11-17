# This is my package filament-menu-builder

[![Latest Version on Packagist](https://img.shields.io/packagist/v/biostate/filament-menu-builder.svg?style=flat-square)](https://packagist.org/packages/biostate/filament-menu-builder)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/biostate/filament-menu-builder/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/biostate/filament-menu-builder/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/biostate/filament-menu-builder/fix-php-code-styling.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/biostate/filament-menu-builder/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/biostate/filament-menu-builder.svg?style=flat-square)](https://packagist.org/packages/biostate/filament-menu-builder)



This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require biostate/filament-menu-builder
```

Add the plugin to your `AdminPanelServiceProvider.php`:

```php
public function panel(Panel $panel): Panel
{
    return $panel
        // Your other configurations
        ->plugins([
            \Biostate\FilamentMenuBuilder\FilamentMenuBuilderPlugin::make(), // Add this line
        ]);
}
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-menu-builder-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-menu-builder-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-menu-builder-views"
```

This is the contents of the published config file:

```php
return [
];
```

## Menuable

You can create relationships between menu items and your models. To enable this feature, you need to add the `Menuable` trait to your model.

```php
use Biostate\FilamentMenuBuilder\Traits\Menuable;

class Product extends Model
{
    use Menuable;
}
```

After this you need to add your model in to the config file. You can add multiple models.

```php
return [
    'models' => [
        'Product' => \App\Models\Product::class,
    ],
];
```

If you add these configurations, you can see the menu items in the menu item forms as a select input.

## Blade Components

Todo: Add blade components

Todo: add parameters like mega menu, dropdown, etc.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Süleyman Özgür Özarpacı](https://github.com/Biostate)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
