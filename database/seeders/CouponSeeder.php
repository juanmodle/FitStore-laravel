<?php

namespace Database\Seeders;

use App\Models\Coupon;
use App\Models\Discount;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $discount = Discount::where('name', 'Bienvenida 10 %')->firstOrFail();
        $expired = Discount::where('name', 'Promoción terminada')->firstOrFail();
        Coupon::updateOrCreate(['code' => 'BIENVENIDA10'], [
            'discount_id' => $discount->id, 'starts_at' => '2026-01-01', 'ends_at' => '2027-01-01',
            'minimum_order_cents' => 1000, 'max_uses' => 100, 'max_uses_per_user' => 2, 'is_active' => true,
        ]);
        Coupon::updateOrCreate(['code' => 'CADUCADO5'], [
            'discount_id' => $expired->id, 'starts_at' => '2025-01-01', 'ends_at' => '2025-02-01',
            'minimum_order_cents' => 2000, 'max_uses' => 20, 'max_uses_per_user' => 1, 'is_active' => false,
        ]);
    }
}
