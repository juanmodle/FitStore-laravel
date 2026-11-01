<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
ALTER TABLE order_items ADD COLUMN discount_cents INTEGER NOT NULL DEFAULT 0
CHECK (typeof(discount_cents) = 'integer' AND discount_cents >= 0 AND discount_cents <= line_total_cents)
SQL);

        // Repartir el descuento histórico sin cambiar precios ni totales del pedido.
        foreach (DB::table('orders')->where('discount_cents', '>', 0)->orderBy('id')->get() as $order) {
            $items = DB::table('order_items')->where('order_id', $order->id)->orderBy('id')->get();
            $subtotal = (int) $items->sum('line_total_cents');
            if ($subtotal !== (int) $order->subtotal_cents || $order->discount_cents > $subtotal || $subtotal === 0) {
                throw new RuntimeException('No se puede repartir el descuento del pedido '.$order->id.': sus líneas no cuadran.');
            }

            $discounts = [];
            $remainders = [];
            foreach ($items as $item) {
                $amount = $item->line_total_cents * $order->discount_cents;
                $discounts[$item->id] = intdiv($amount, $subtotal);
                $remainders[$item->id] = $amount % $subtotal;
            }

            $remaining = $order->discount_cents - array_sum($discounts);
            arsort($remainders, SORT_NUMERIC);
            foreach ($remainders as $id => $remainder) {
                if ($remaining === 0) {
                    break;
                }
                $discounts[$id]++;
                $remaining--;
            }
            foreach ($discounts as $id => $discount) {
                DB::table('order_items')->where('id', $id)->update(['discount_cents' => $discount]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('discount_cents');
        });
    }
};
