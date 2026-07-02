<?php

namespace App\Traits;

trait HasSearchable
{
    public function getSearchable(): array
    {
        return defined('static::SEARCHABLE')
            ? static::SEARCHABLE
            : [];
    }
}
