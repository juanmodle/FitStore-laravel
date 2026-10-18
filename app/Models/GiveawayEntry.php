<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class GiveawayEntry extends Pivot
{
    public $incrementing = true;

    protected function casts(): array
    {
        return ['joined_at' => 'datetime'];
    }
}
