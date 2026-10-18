<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        foreach (range(1, 2) as $number) {
            $order = Order::where('number', 'FIT-DEMO-00'.$number)->firstOrFail();
            $user = $order->user;
            Document::updateOrCreate(['path' => 'documents/factura-'.$order->number.'.pdf'], [
                'order_id' => $order->id, 'user_id' => $user->id, 'type' => 'invoice',
                'name' => 'Factura '.$order->number,
            ]);
        }
        $user = User::where('email', 'cliente1@fitstore.test')->firstOrFail();
        foreach (['sales_report', 'products_report', 'vip_report', 'giveaways_report'] as $type) {
            Document::updateOrCreate(['path' => 'documents/'.$type.'.pdf'], [
                'type' => $type, 'name' => 'Informe de demostración: '.$type,
                'order_id' => null, 'user_id' => $user->id,
            ]);
        }
    }
}
