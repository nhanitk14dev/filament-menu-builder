<?php

return [
    'models' => [
        // 'Product' => 'App\\Models\\Product',
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
