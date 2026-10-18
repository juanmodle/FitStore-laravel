<?php

use App\Models\Address;
use App\Models\Brand;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\Coupon;
use App\Models\CustomerProfile;
use App\Models\Discount;
use App\Models\Document;
use App\Models\Giveaway;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostTranslation;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductTranslation;
use App\Models\ProductVariant;
use App\Models\Tag;
use App\Models\User;
use App\Models\VipSubscription;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    Carbon::setTestNow('2026-10-18 12:00:00');
});

afterEach(function () {
    Carbon::setTestNow();
});

it('crea datos válidos con cada factory', function (string $model) {
    $record = $model::factory()->create();

    expect($record->exists)->toBeTrue();
    $this->assertDatabaseHas($record->getTable(), ['id' => $record->id]);
})->with([
    User::class, CustomerProfile::class, Address::class, Category::class, Brand::class,
    Product::class, ProductVariant::class, ProductImage::class, Tag::class,
    Cart::class, CartItem::class, Discount::class, Coupon::class, Order::class,
    OrderItem::class, VipSubscription::class, Payment::class, Giveaway::class,
    PostCategory::class, Post::class, ProductTranslation::class,
    CategoryTranslation::class, PostTranslation::class, Document::class,
]);

it('relaciona el usuario con su perfil y sus direcciones', function () {
    $user = User::factory()->create();
    $profile = CustomerProfile::factory()->for($user)->create();
    $addresses = Address::factory()->for($user)->count(2)->create();

    expect($user->profile->is($profile))->toBeTrue()
        ->and($profile->user->is($user))->toBeTrue()
        ->and($user->addresses)->toHaveCount(2)
        ->and($addresses->first()->user->is($user))->toBeTrue();
});

it('relaciona categorías marcas productos variantes imágenes y traducciones', function () {
    $product = Product::factory()->withVariants(2)->create();
    $image = ProductImage::factory()->for($product)->create();
    $translation = ProductTranslation::factory()->for($product)->create();
    $categoryTranslation = CategoryTranslation::factory()->for($product->category)->create();

    expect($product->category->products->contains($product))->toBeTrue()
        ->and($product->brand->products->contains($product))->toBeTrue()
        ->and($product->variants)->toHaveCount(2)
        ->and($product->variants->first()->product->is($product))->toBeTrue()
        ->and($product->images->contains($image))->toBeTrue()
        ->and($image->product->is($product))->toBeTrue()
        ->and($product->translations->contains($translation))->toBeTrue()
        ->and($translation->product->is($product))->toBeTrue()
        ->and($categoryTranslation->category->is($product->category))->toBeTrue();
});

it('guarda y actualiza los campos de la relación producto etiqueta', function () {
    $product = Product::factory()->create();
    $tag = Tag::factory()->create();
    $product->tags()->attach($tag->id, ['priority' => 1, 'is_visible' => false]);

    $pivot = $product->tags()->firstOrFail()->pivot;
    expect($pivot->priority)->toBe(1)->and($pivot->is_visible)->toBeFalse();

    $product->tags()->updateExistingPivot($tag->id, ['priority' => 5, 'is_visible' => true]);
    $pivot = $product->tags()->firstOrFail()->pivot;
    expect($pivot->priority)->toBe(5)->and($pivot->is_visible)->toBeTrue()
        ->and($tag->products->contains($product))->toBeTrue();
});

it('registra usos de un cupón con fecha y contador', function () {
    $user = User::factory()->create();
    $coupon = Coupon::factory()->create();
    $coupon->users()->attach($user->id, ['used_at' => now(), 'times_used' => 1]);
    $uses = $coupon->users()->firstOrFail()->pivot->times_used;
    $coupon->users()->updateExistingPivot($user->id, ['times_used' => $uses + 1, 'used_at' => now()]);

    $pivot = $user->coupons()->firstOrFail()->pivot;
    expect($pivot->times_used)->toBe(2)
        ->and($pivot->used_at->equalTo(now()))->toBeTrue()
        ->and($coupon->discount->coupons->contains($coupon))->toBeTrue();
});

it('inscribe participantes sin duplicarlos y permite elegir un ganador inscrito', function () {
    $user = User::factory()->create();
    $giveaway = Giveaway::factory()->create();
    foreach (range(1, 2) as $attempt) {
        $giveaway->users()->syncWithoutDetaching([$user->id => ['joined_at' => now()]]);
    }
    $giveaway->update(['winner_user_id' => $user->id]);

    expect($giveaway->users)->toHaveCount(1)
        ->and($giveaway->users->first()->pivot->joined_at->equalTo(now()))->toBeTrue()
        ->and($user->giveaways->contains($giveaway))->toBeTrue()
        ->and($giveaway->winner->is($user))->toBeTrue()
        ->and($user->wonGiveaways->contains($giveaway))->toBeTrue();
});

it('impide elegir un ganador que no participa', function () {
    $giveaway = Giveaway::factory()->create();
    $giveaway->update(['winner_user_id' => User::factory()->create()->id]);
})->throws(QueryException::class);

it('impide eliminar la participación ganadora', function () {
    $user = User::factory()->create();
    $giveaway = Giveaway::factory()->create();
    $giveaway->users()->attach($user->id, ['joined_at' => now()]);
    $giveaway->update(['winner_user_id' => $user->id]);
    $giveaway->users()->detach($user->id);
})->throws(QueryException::class);

it('relaciona el carrito con sus líneas y variantes', function () {
    $cart = Cart::factory()->create();
    $item = CartItem::factory()->for($cart)->create();

    expect($cart->user->cart->is($cart))->toBeTrue()
        ->and($cart->items->contains($item))->toBeTrue()
        ->and($item->cart->is($cart))->toBeTrue()
        ->and($item->variant->cartItems->contains($item))->toBeTrue();
});

it('conserva el precio nombre SKU dirección e identidad originales del pedido', function () {
    $order = Order::factory()->withItems()->create();
    $line = $order->items()->firstOrFail();
    $original = $line->only(['product_name', 'sku', 'unit_price_cents', 'line_total_cents']);
    $address = $order->shipping_address;
    $email = $order->customer_email;

    $line->variant->product->update(['name' => 'Nombre nuevo']);
    $line->variant->update(['price_cents' => 9999, 'sku' => 'SKU-NUEVO']);
    $order->user->update(['name' => 'Otro nombre', 'email' => 'nuevo@fitstore.test']);
    Address::factory()->for($order->user)->create(['street' => 'Otra calle']);

    expect($line->fresh()->only(array_keys($original)))->toBe($original)
        ->and($order->fresh()->shipping_address)->toBe($address)
        ->and($order->fresh()->customer_email)->toBe($email)
        ->and($order->user->orders->contains($order))->toBeTrue()
        ->and($line->variant->orderItems->contains($line))->toBeTrue();
});

it('conserva pedidos y variantes históricas al retirar un producto y un usuario', function () {
    $order = Order::factory()->withItems()->create();
    $line = $order->items()->firstOrFail();
    $line->variant->product->delete();
    $line->variant->delete();
    $order->user->delete();

    expect($line->fresh()->variant)->not->toBeNull()
        ->and($line->fresh()->variant->product)->not->toBeNull()
        ->and($order->fresh()->user)->not->toBeNull()
        ->and(Order::find($order->id))->not->toBeNull();
    $this->assertSoftDeleted('users', ['id' => $order->user_id]);
});

it('relaciona pedidos pagos documentos y cupones', function () {
    $coupon = Coupon::factory()->create();
    $order = Order::factory()->for($coupon)->withItems()->paid()->create(['coupon_code' => $coupon->code]);
    $payment = Payment::factory()->for($order)->paid()->create();
    $document = Document::factory()->for($order)->create();

    expect($order->payments->contains($payment))->toBeTrue()
        ->and($payment->order->is($order))->toBeTrue()
        ->and($payment->amount_cents)->toBe($order->total_cents)
        ->and($order->documents->contains($document))->toBeTrue()
        ->and($document->order->is($order))->toBeTrue()
        ->and($document->generatedBy->documents->contains($document))->toBeTrue()
        ->and($coupon->orders->contains($order))->toBeTrue();
});

it('relaciona suscripciones y pagos y calcula si el usuario es VIP', function () {
    $user = User::factory()->create();
    expect($user->isVip())->toBeFalse();
    $subscription = VipSubscription::factory()->for($user)->active()->create();
    $payment = Payment::factory()->forSubscription($subscription)->paid()->create();

    expect($user->isVip())->toBeTrue()
        ->and($subscription->user->is($user))->toBeTrue()
        ->and($subscription->payments->contains($payment))->toBeTrue()
        ->and($payment->vipSubscription->is($subscription))->toBeTrue()
        ->and($payment->order_id)->toBeNull();

    Carbon::setTestNow('2026-11-02 12:00:00');
    expect($user->isVip())->toBeFalse();
});

it('relaciona publicaciones autor categoría traducciones y etiquetas', function () {
    $post = Post::factory()->published()->create();
    $tag = Tag::factory()->create();
    $translation = PostTranslation::factory()->for($post)->create();
    $post->tags()->attach($tag->id);

    expect($post->author->posts->contains($post))->toBeTrue()
        ->and($post->category->posts->contains($post))->toBeTrue()
        ->and($post->tags->contains($tag))->toBeTrue()
        ->and($tag->posts->contains($post))->toBeTrue()
        ->and($post->translations->contains($translation))->toBeTrue()
        ->and($translation->post->is($post))->toBeTrue();
});

it('guarda la contraseña como hash y permite cambiarla junto al nombre', function () {
    $user = User::factory()->create();
    $user->update(['name' => 'Nombre actualizado', 'password' => 'otra-clave-segura']);

    expect($user->fresh()->name)->toBe('Nombre actualizado')
        ->and(Hash::check('otra-clave-segura', $user->fresh()->password))->toBeTrue()
        ->and($user->toArray())->not->toHaveKey('password');
});

it('rechaza datos inválidos desde las restricciones de SQLite', function (string $model, array $attributes) {
    $model::factory()->create($attributes);
})->with([
    'stock negativo' => [ProductVariant::class, ['stock' => -1]],
    'stock decimal' => [ProductVariant::class, ['stock' => 1.5]],
    'precio negativo' => [ProductVariant::class, ['price_cents' => -1]],
    'precio decimal' => [ProductVariant::class, ['price_cents' => 12.5]],
    'subtotal decimal' => [Order::class, ['subtotal_cents' => 2000.5, 'total_cents' => 2300.5]],
    'envío decimal' => [Order::class, ['shipping_cents' => 300.5, 'total_cents' => 2300.5]],
    'puntos decimales' => [CustomerProfile::class, ['points' => 1.5]],
    'límite de cupón decimal' => [Coupon::class, ['max_uses' => 1.5]],
    'cantidad cero' => [CartItem::class, ['quantity' => 0]],
    'cantidad decimal' => [CartItem::class, ['quantity' => 1.5]],
    'línea inconsistente' => [OrderItem::class, ['line_total_cents' => 1]],
    'total inconsistente' => [Order::class, ['total_cents' => 1]],
    'descuento superior al subtotal' => [Order::class, ['discount_cents' => 3000, 'total_cents' => 0]],
    'porcentaje excesivo' => [Discount::class, ['value' => 101]],
    'valor de descuento cero' => [Discount::class, ['value' => 0]],
    'fechas de cupón invertidas' => [Coupon::class, ['ends_at' => '2025-01-01']],
    'fechas VIP invertidas' => [VipSubscription::class, ['ends_at' => '2025-01-01']],
    'fechas de sorteo invertidas' => [Giveaway::class, ['ends_at' => '2025-01-01']],
    'idioma inexistente' => [ProductTranslation::class, ['locale' => 'xx']],
    'estado desconocido' => [Order::class, ['status' => 'desconocido']],
    'pago sin destino' => [Payment::class, ['order_id' => null, 'vip_subscription_id' => null, 'amount_cents' => 1000]],
    'pago sin fecha' => [Payment::class, ['status' => 'paid', 'paid_at' => null]],
    'factura sin pedido' => [Document::class, ['order_id' => null, 'user_id' => null]],
    'producto sin categoría válida' => [Product::class, ['category_id' => 999999]],
])->throws(QueryException::class);

it('impide asociar un pago a pedido y suscripción a la vez', function () {
    Payment::factory()->create(['vip_subscription_id' => VipSubscription::factory()->create()->id]);
})->throws(QueryException::class);

it('impide repetir valores únicos', function (string $model, string $column) {
    $first = $model::factory()->create();
    $model::factory()->create([$column => $first->$column]);
})->with([
    [User::class, 'email'], [ProductVariant::class, 'sku'], [Coupon::class, 'code'],
    [Order::class, 'number'], [Category::class, 'slug'], [Payment::class, 'reference'],
])->throws(QueryException::class);

it('impide duplicar una variante en el mismo carrito', function () {
    $item = CartItem::factory()->create();
    CartItem::factory()->create(['cart_id' => $item->cart_id, 'product_variant_id' => $item->product_variant_id]);
})->throws(QueryException::class);

it('impide duplicar una traducción del mismo idioma', function () {
    $translation = ProductTranslation::factory()->create();
    ProductTranslation::factory()->create(['product_id' => $translation->product_id, 'locale' => $translation->locale]);
})->throws(QueryException::class);

it('impide duplicar una etiqueta del mismo producto', function () {
    $product = Product::factory()->create();
    $tag = Tag::factory()->create();
    $product->tags()->attach($tag->id);
    $product->tags()->attach($tag->id);
})->throws(QueryException::class);

it('no permite borrar físicamente una variante con compras', function () {
    $item = OrderItem::factory()->create();
    $item->variant->forceDelete();
})->throws(QueryException::class);

it('elimina las líneas al borrar un carrito', function () {
    $item = CartItem::factory()->create();
    $item->cart->delete();

    $this->assertDatabaseMissing('cart_items', ['id' => $item->id]);
});

it('crea los índices para las consultas previstas', function () {
    $indexes = collect(DB::select("PRAGMA index_list('orders')"))->pluck('name');
    expect($indexes)->toContain('orders_user_id_placed_at_index', 'orders_status_placed_at_index');
});
