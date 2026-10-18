<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    public function run(): void
    {
        foreach (range(1, 4) as $number) {
            $order = Order::where('number', 'FIT-DEMO-00'.$number)->firstOrFail();
            $variants = ProductVariant::whereIn('sku', ['FIT-'.$number.'-1', 'FIT-'.($number + 1).'-1'])->orderBy('id')->get();
            foreach ($variants as $variant) {
                OrderItem::updateOrCreate(['order_id' => $order->id, 'product_variant_id' => $variant->id], [
                    'product_name' => $variant->product->name, 'sku' => $variant->sku, 'quantity' => 1,
                    'unit_price_cents' => $variant->price_cents, 'line_total_cents' => $variant->price_cents,
                ]);
            }
        }
    }
}
