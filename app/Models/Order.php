<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'coupon_id', 'number', 'status', 'currency', 'subtotal_cents', 'discount_cents', 'shipping_cents', 'tax_cents', 'total_cents', 'coupon_code', 'customer_name', 'customer_email', 'shipping_address', 'billing_address', 'placed_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal_cents' => 'integer',
            'discount_cents' => 'integer',
            'shipping_cents' => 'integer',
            'tax_cents' => 'integer',
            'total_cents' => 'integer',
            'shipping_address' => 'array',
            'billing_address' => 'array',
            'placed_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
