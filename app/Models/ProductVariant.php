<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id', 'sku', 'size', 'color', 'flavor', 'format', 'price_cents', 'stock', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price_cents' => 'integer',
            'stock' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
