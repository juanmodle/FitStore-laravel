<?php

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\VipSubscription;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

it('reparte los céntimos sin perder descuento ni superar el importe de línea', function (array $amounts, int $discount, array $expected) {
    $subtotal = array_sum($amounts);
    $order = Order::factory()->create([
        'subtotal_cents' => $subtotal, 'discount_cents' => $discount,
        'shipping_cents' => 0, 'total_cents' => $subtotal - $discount,
    ]);
    foreach ($amounts as $amount) {
        OrderItem::factory()->for($order)->create([
            'quantity' => 1, 'unit_price_cents' => $amount, 'line_total_cents' => $amount,
        ]);
    }

    $allocation = $order->lineDiscounts();
    expect(array_values($allocation))->toBe($expected)
        ->and(array_sum($allocation))->toBe($discount);

    foreach ($order->items()->orderBy('id')->get() as $item) {
        $item->update(['discount_cents' => $allocation[$item->id]]);
        expect($item->fresh()->net_total_cents)->toBeGreaterThanOrEqual(0);
    }
    expect($order->fresh()->total_cents)->toBe($subtotal - $discount);
})->with([
    'proporcional' => [[1000, 2000], 300, [100, 200]],
    'empate de fracciones' => [[1, 1, 1], 2, [1, 1, 0]],
    'línea gratuita' => [[0, 3, 7], 3, [0, 1, 2]],
    'descuento completo' => [[1, 99], 100, [1, 99]],
    'pedido gratuito' => [[0, 0], 0, [0, 0]],
]);

it('rechaza un descuento de línea inválido en la base de datos', function ($discount) {
    OrderItem::factory()->create(['discount_cents' => $discount]);
})->with([-1, 2001, 1.5])->throws(QueryException::class);

it('mantiene el descuento y el precio históricos al cambiar el producto', function () {
    $order = Order::factory()->withItems()->create([
        'discount_cents' => 200, 'total_cents' => 2100,
    ]);
    $item = $order->items()->firstOrFail();
    $item->variant->update(['price_cents' => 9999]);

    expect($item->fresh()->unit_price_cents)->toBe(2000)
        ->and($item->fresh()->discount_cents)->toBe(200)
        ->and($item->fresh()->net_total_cents)->toBe(1800)
        ->and($order->fresh()->total_cents)->toBe(2100);
});

it('detecta un pedido cuyas líneas no cuadran antes de calcular el reparto', function () {
    $order = Order::factory()->create(['subtotal_cents' => 3000, 'total_cents' => 3300]);
    OrderItem::factory()->for($order)->create();
    $order->lineDiscounts();
})->throws(InvalidArgumentException::class);

it('la migración reparte el descuento anterior sin cambiar los datos históricos', function () {
    $order = Order::factory()->paid()->create([
        'subtotal_cents' => 100, 'discount_cents' => 1, 'shipping_cents' => 0, 'total_cents' => 99,
    ]);
    foreach ([67, 33] as $amount) {
        OrderItem::factory()->for($order)->create([
            'quantity' => 1, 'unit_price_cents' => $amount, 'line_total_cents' => $amount,
        ]);
    }
    $before = $order->fresh()->getAttributes();
    $migration = require database_path('migrations/2026_11_01_000001_add_discount_to_order_items_table.php');
    $migration->down();
    $migration->up();

    expect($order->items()->orderBy('id')->pluck('discount_cents')->all())->toBe([1, 0])
        ->and($order->fresh()->getAttributes())->toBe($before);
});

it('la migración rechaza un histórico incoherente', function () {
    $order = Order::factory()->create(['discount_cents' => 100, 'total_cents' => 2200]);
    OrderItem::factory()->for($order)->create(['quantity' => 1, 'unit_price_cents' => 500, 'line_total_cents' => 500]);
    $migration = require database_path('migrations/2026_11_01_000001_add_discount_to_order_items_table.php');
    $migration->down();
    $migration->up();
})->throws(RuntimeException::class);

it('computa los descuentos de todos los pedidos de demostración', function () {
    $this->seed();
    foreach (Order::with('items')->get() as $order) {
        expect($order->items->sum('discount_cents'))->toBe($order->discount_cents)
            ->and($order->items->sum('net_total_cents'))->toBe($order->subtotal_cents - $order->discount_cents);
    }
    expect(DB::table('order_items')->sum('discount_cents'))->toBe(450);
});

it('la suscripción deja de estar vigente en el instante exacto de vencimiento', function () {
    $user = User::factory()->create();
    VipSubscription::factory()->for($user)->active()->create([
        'starts_at' => '2026-10-01 00:00:00', 'ends_at' => '2026-11-01 00:00:00',
    ]);
    try {
        Carbon::setTestNow('2026-10-31 23:59:59');
        expect($user->isVip())->toBeTrue();
        Carbon::setTestNow('2026-11-01 00:00:00');
        expect($user->isVip())->toBeFalse();
    } finally {
        Carbon::setTestNow();
    }
});
