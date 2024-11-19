<?php

namespace Biostate\FilamentMenuBuilder\Models;

use Biostate\FilamentMenuBuilder\Enums\MenuItemType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;
use Kalnoy\Nestedset\NodeTrait;

class MenuItem extends Model
{
    use HasFactory;
    use NodeTrait;

    protected $fillable = [
        'name',
        'target',
        'type',
        'route',
        'route_parameters',
        'link_class',
        'wrapper_class',
        'menu_id',
        'parameters',
        'menuable_id',
        'menuable_type',
        'url',
    ];

    protected $casts = [
        'parameters' => 'collection',
        'route_parameters' => 'collection',
    ];

    public $timestamps = false;

    protected $touches = ['menu'];

    public function menuable(): MorphTo
    {
        return $this->morphTo();
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function getNormalizedTypeAttribute($value)
    {
        if ($this->type !== 'model') {
            return MenuItemType::fromValue($this->type)->getLabel();
        }

        return Str::afterLast($this->menuable_type, '\\');
    }

    public function getLinkAttribute($value): string
    {
        return match ($this->type) {
            'model' => $this->menuable->menu_link,
            'url' => $this->url,
            default => route($this->route, $this->route_parameters->toArray()),
        };
    }
}
