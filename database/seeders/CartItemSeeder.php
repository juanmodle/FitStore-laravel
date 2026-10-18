<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Database\Seeder;

class CartItemSeeder extends Seeder
{
    public function run(): void
    {
        foreach (range(1, 4) as $number) {
            $user = User::where('email', 'cliente'.$number.'@fitstore.test')->firstOrFail();
            $cart = Cart::where('user_id', $user->id)->firstOrFail();
            CartItem::updateOrCreate([
                'cart_id' => $cart->id,
                'product_variant_id' => ProductVariant::where('sku', 'FIT-'.$number.'-1')->firstOrFail()->id,
            ], ['quantity' => 1]);
        }
    }
}
