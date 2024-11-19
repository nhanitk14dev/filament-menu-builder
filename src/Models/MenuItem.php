<?php

namespace Biostate\FilamentMenuBuilder\Models;

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

    public function getTypeAttribute($value)
    {
        if ($this->menuable_type === null) {
            return 'Link';
        }

        return Str::afterLast($this->menuable_type, '\\');
    }

    public function getLinkAttribute($value)
    {
        if ($this->menuable_type === null || $this->menuable === null) {
            return $this->url;
        }

        return $this->menuable->menu_link;
    }

    public function childrenDeep()
    {
        return $this->children()->with(['childrenDeep' => function ($query) {
            $query->defaultOrder();
        }]);
    }
}
