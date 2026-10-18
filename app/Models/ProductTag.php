<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductTag extends Pivot
{
    public $incrementing = true;

    protected function casts(): array
    {
        return ['priority' => 'integer', 'is_visible' => 'boolean'];
    }
}
