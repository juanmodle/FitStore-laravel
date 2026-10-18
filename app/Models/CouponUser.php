<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CouponUser extends Pivot
{
    public $incrementing = true;

    protected function casts(): array
    {
        return ['used_at' => 'datetime', 'times_used' => 'integer'];
    }
}
