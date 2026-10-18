<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'discount_id', 'code', 'starts_at', 'ends_at', 'minimum_order_cents', 'max_uses', 'max_uses_per_user', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_active' => 'boolean',
            'minimum_order_cents' => 'integer',
            'max_uses' => 'integer',
            'max_uses_per_user' => 'integer',
        ];
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'coupon_user')->using(CouponUser::class)->withPivot('used_at', 'times_used')->withTimestamps();
    }
}
