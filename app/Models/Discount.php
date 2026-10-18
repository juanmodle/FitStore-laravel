<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'type', 'value', 'starts_at', 'ends_at', 'is_active', 'vip_only',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'integer',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_active' => 'boolean',
            'vip_only' => 'boolean',
        ];
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }
}
