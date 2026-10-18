<?php

use App\Models\Coupon;
use App\Models\Giveaway;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

it('puebla todas las entidades y mantiene las relaciones coherentes', function () {
    $this->seed();

    $expected = [
        'users' => 4, 'customer_profiles' => 4, 'addresses' => 4, 'categories' => 4,
        'brands' => 3, 'products' => 8, 'product_variants' => 16, 'product_images' => 8,
        'tags' => 3, 'carts' => 4, 'cart_items' => 4, 'discounts' => 3, 'coupons' => 2,
        'orders' => 4, 'order_items' => 8, 'vip_subscriptions' => 1, 'payments' => 5,
        'giveaways' => 2, 'post_categories' => 1, 'posts' => 2, 'product_translations' => 16,
        'category_translations' => 8, 'post_translations' => 4, 'documents' => 6,
        'product_tag' => 16, 'post_tag' => 2, 'coupon_user' => 1, 'giveaway_entries' => 8,
    ];
    foreach ($expected as $table => $count) {
        $this->assertDatabaseCount($table, $count);
    }
    foreach (Order::with('items', 'payments')->get() as $order) {
        expect($order->items->sum('line_total_cents'))->toBe($order->subtotal_cents)
            ->and($order->total_cents)->toBe($order->subtotal_cents - $order->discount_cents + $order->shipping_cents + $order->tax_cents);
        foreach ($order->items as $item) {
            expect($item->line_total_cents)->toBe($item->quantity * $item->unit_price_cents);
        }
        foreach ($order->payments as $payment) {
            expect($payment->amount_cents)->toBe($order->total_cents);
        }
    }
    foreach (Payment::whereNotNull('vip_subscription_id')->get() as $payment) {
        expect($payment->amount_cents)->toBe($payment->vipSubscription->price_cents);
    }
    foreach (Product::with('variants', 'translations')->get() as $product) {
        expect($product->variants)->not->toBeEmpty()
            ->and($product->translations->pluck('locale')->sort()->values()->all())->toBe(['en', 'es']);
    }
    $coupon = Coupon::where('code', 'BIENVENIDA10')->firstOrFail();
    expect($coupon->users->first()->pivot->times_used)->toBe(1)
        ->and($coupon->orders)->toHaveCount(1);
    $closed = Giveaway::where('status', 'closed')->firstOrFail();
    expect($closed->users->contains($closed->winner))->toBeTrue();
    Carbon::setTestNow('2026-10-18 12:00:00');
    try {
        expect(User::where('email', 'cliente1@fitstore.test')->firstOrFail()->isVip())->toBeTrue();
    } finally {
        Carbon::setTestNow();
    }
});

it('permite repetir el seeder sin duplicar ni cambiar los datos de demostración', function () {
    $this->seed();
    $tables = ['users', 'products', 'product_variants', 'orders', 'order_items', 'payments', 'giveaways', 'giveaway_entries', 'coupon_user', 'documents'];
    $before = [];
    foreach ($tables as $table) {
        $before[$table] = DB::table($table)->orderBy('id')->get()->toJson();
    }
    $this->seed();
    foreach ($tables as $table) {
        expect(DB::table($table)->orderBy('id')->get()->toJson())->toBe($before[$table]);
    }
});

it('restaura el reloj después de cargar los datos', function () {
    Carbon::setTestNow('2030-03-15 09:00:00');
    try {
        $this->seed();
        expect(now()->format('Y-m-d H:i:s'))->toBe('2030-03-15 09:00:00');
    } finally {
        Carbon::setTestNow();
    }
});
