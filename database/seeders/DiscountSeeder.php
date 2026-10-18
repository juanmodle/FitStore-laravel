<?php

namespace Database\Seeders;

use App\Models\Discount;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    public function run(): void
    {
        Discount::updateOrCreate(['name' => 'Bienvenida 10 %'], [
            'type' => 'percentage', 'value' => 10, 'starts_at' => '2026-01-01',
            'ends_at' => '2027-01-01', 'is_active' => true, 'vip_only' => false,
        ]);
        Discount::updateOrCreate(['name' => 'Promoción terminada'], [
            'type' => 'fixed', 'value' => 500, 'starts_at' => '2025-01-01',
            'ends_at' => '2025-02-01', 'is_active' => false, 'vip_only' => false,
        ]);
        Discount::updateOrCreate(['name' => 'Ventaja VIP'], [
            'type' => 'percentage', 'value' => 15, 'starts_at' => '2026-01-01',
            'ends_at' => '2027-01-01', 'is_active' => true, 'vip_only' => true,
        ]);
    }
}
