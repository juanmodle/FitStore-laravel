<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

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

    public function lineDiscounts(): array
    {
        $items = $this->items()->orderBy('id')->get();
        $subtotal = (int) $items->sum('line_total_cents');
        if ($subtotal !== $this->subtotal_cents || $this->discount_cents < 0 || $this->discount_cents > $subtotal) {
            throw new InvalidArgumentException('Las líneas y el descuento deben cuadrar con el subtotal del pedido.');
        }

        $discounts = [];
        $remainders = [];
        foreach ($items as $item) {
            $amount = $item->line_total_cents * $this->discount_cents;
            $discounts[$item->id] = $subtotal === 0 ? 0 : intdiv($amount, $subtotal);
            $remainders[$item->id] = $subtotal === 0 ? 0 : $amount % $subtotal;
        }

        // Los céntimos pendientes van a las mayores fracciones; en empate, al menor id.
        $remaining = $this->discount_cents - array_sum($discounts);
        arsort($remainders, SORT_NUMERIC);
        foreach ($remainders as $id => $remainder) {
            if ($remaining === 0) {
                break;
            }
            $discounts[$id]++;
            $remaining--;
        }

        return $discounts;
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
