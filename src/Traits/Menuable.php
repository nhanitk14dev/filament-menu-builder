<?php

namespace Biostate\FilamentMenuBuilder\Traits;

use Illuminate\Contracts\Database\Eloquent\Builder;

trait Menuable
{
    public function getMenuLinkAttribute(): string
    {
        throw new \Exception('You need to implement the menuLink method');
    }

    public function getMenuNameAttribute(): string
    {
        return 'name';
    }

    public static function getFilamentSearchLabel(): string
    {
        return 'name';
    }

    public function scopeFilamentSearch(Builder $query, $search)
    {
        $hasTranslations = in_array('Spatie\Translatable\HasTranslations::class', class_uses_recursive(static::class));

        if ($hasTranslations) {
            $query->whereRaw("LOWER(json_unquote(JSON_EXTRACT(?, '$.tr'))) like LOWER(?)", [
                $this->getFilamentSearchLabel(),
                "%{$search}%",
            ]);
        } else {
            $query->where($this->getFilamentSearchLabel(), 'like', "%{$search}%");
        }

        $query->limit(10);
    }

    public function getFilamentSearchOptionName()
    {
        return $this->{$this->getFilamentSearchLabel()};
    }
}
