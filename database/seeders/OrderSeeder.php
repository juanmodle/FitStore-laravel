<?php

namespace Database\Seeders;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $coupon = Coupon::where('code', 'BIENVENIDA10')->firstOrFail();
        foreach (range(1, 4) as $number) {
            $user = User::where('email', 'cliente'.$number.'@fitstore.test')->firstOrFail();
            $variants = ProductVariant::whereIn('sku', ['FIT-'.$number.'-1', 'FIT-'.($number + 1).'-1'])->orderBy('id')->get();
            $subtotal = $variants->sum('price_cents');
            $reduction = $number === 1 ? intdiv($subtotal * 10 + 50, 100) : 0;
            $paid = $number <= 2;
            $address = $user->addresses()->firstOrFail()->only(['recipient', 'street', 'city', 'province', 'postal_code', 'country']);
            $order = Order::updateOrCreate(['number' => 'FIT-DEMO-00'.$number], [
                'user_id' => $user->id, 'coupon_id' => $number === 1 ? $coupon->id : null,
                'coupon_code' => $number === 1 ? $coupon->code : null,
                'status' => $paid ? 'paid' : 'pending', 'currency' => 'EUR',
                'subtotal_cents' => $subtotal, 'discount_cents' => $reduction,
                'shipping_cents' => 300, 'tax_cents' => 0, 'total_cents' => $subtotal - $reduction + 300,
                'customer_name' => $user->name, 'customer_email' => $user->email,
                'shipping_address' => $address, 'billing_address' => $address, 'placed_at' => now(),
            ]);
        }
    }
}
