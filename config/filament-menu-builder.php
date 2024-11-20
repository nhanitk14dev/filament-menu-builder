<?php

return [
    'models' => [
        // 'Product' => 'App\\Models\\Product',
    ],
    'cache' => [
        'enabled' => true,
        'key' => 'filament-menu-builder',
        'ttl' => 60 * 60 * 24,
    ],
    'exclude_route_names' => [
        '/^debugbar\./', // Exclude debugbar routes
        '/^filament\./',   // Exclude filament routes
        '/^livewire\./',   // Exclude livewire routes
    ],
    'exclude_routes' => [
        //
    ],
];
