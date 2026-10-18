<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Models\VipSubscription;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        foreach (range(1, 4) as $number) {
            $order = Order::where('number', 'FIT-DEMO-00'.$number)->firstOrFail();
            $paid = $order->status === 'paid';
            Payment::updateOrCreate(['reference' => 'SIM-PEDIDO-'.$number], [
                'order_id' => $order->id, 'vip_subscription_id' => null, 'amount_cents' => $order->total_cents,
                'currency' => 'EUR', 'status' => $paid ? 'paid' : 'pending', 'paid_at' => $paid ? now() : null,
            ]);
        }
        $user = User::where('email', 'cliente1@fitstore.test')->firstOrFail();
        $subscription = VipSubscription::where('user_id', $user->id)->where('starts_at', '2026-10-01 00:00:00')->firstOrFail();
        Payment::updateOrCreate(['reference' => 'SIM-VIP-1'], [
            'order_id' => null, 'vip_subscription_id' => $subscription->id, 'amount_cents' => 999,
            'currency' => 'EUR', 'status' => 'paid', 'paid_at' => '2026-10-01 10:00:00',
        ]);
    }
}
