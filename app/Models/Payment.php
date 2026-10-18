<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'vip_subscription_id', 'reference', 'amount_cents', 'currency', 'status', 'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'amount_cents' => 'integer',
            'paid_at' => 'datetime',
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function vipSubscription()
    {
        return $this->belongsTo(VipSubscription::class);
    }
}
