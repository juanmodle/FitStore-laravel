<?php

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed([PermissionSeeder::class, RoleSeeder::class, RolePermissionSeeder::class]);
});

it('aplica las zonas de cada uno de los cinco roles', function (string $role, int $catalog, int $orders, int $users, int $vip) {
    $user = User::factory()->create();
    $user->assignRole($role);
    $this->actingAs($user)->get(route('account.dashboard'))->assertOk();
    $this->get(route('management.catalog.index'))->assertStatus($catalog);
    $this->get(route('management.orders.index'))->assertStatus($orders);
    $this->get(route('management.users.index'))->assertStatus($users);
    $this->get(route('account.vip'))->assertStatus($vip);
})->with([
    ['customer', 403, 403, 403, 403],
    ['vip_customer', 403, 403, 403, 200],
    ['editor', 200, 403, 403, 403],
    ['manager', 200, 200, 403, 403],
    ['admin', 200, 200, 200, 200],
]);

it('no da acceso privado a una cuenta sin permisos', function () {
    $user = User::factory()->create();
    $this->actingAs($user)->get(route('account.dashboard'))->assertForbidden();
    $this->post(route('logout'))->assertRedirect(route('home'));
});

it('el administrador también respeta un permiso revocado', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');
    Role::findByName('admin', 'web')->revokePermissionTo('users.view');
    $this->actingAs($user->fresh())->get(route('management.users.index'))->assertForbidden();
});

it('la policy de producto permite publicados y deniega borradores al visitante', function () {
    $published = Product::factory()->create(['name' => 'Producto publicado de prueba']);
    $draft = Product::factory()->inactive()->create(['name' => 'Borrador privado de prueba']);
    $this->get(route('products.show', $published))->assertOk()->assertSee($published->name);
    $this->get(route('products.show', $draft))->assertForbidden();
    $this->get(route('products.index'))->assertSee($published->name)->assertDontSee($draft->name);
    $published->category->update(['is_active' => false]);
    $this->get(route('products.show', $published))->assertForbidden();
    expect(Gate::forUser(null)->allows('view', $draft))->toBeFalse();
});

it('la policy de producto distingue clientes y personal ante un borrador', function (string $role, int $status) {
    $user = User::factory()->create();
    $user->assignRole($role);
    $product = Product::factory()->inactive()->create();
    $this->actingAs($user)->get(route('products.show', $product))->assertStatus($status);
})->with([
    ['customer', 403], ['vip_customer', 403], ['editor', 200], ['manager', 200], ['admin', 200],
]);

it('la policy de pedido permite el propio y deniega el ajeno', function () {
    $user = User::factory()->create();
    $user->assignRole('customer');
    $own = Order::factory()->for($user)->withItems()->create();
    $other = Order::factory()->withItems()->create();
    $this->actingAs($user)->get(route('account.orders.index'))->assertOk()
        ->assertSee($own->number)->assertDontSee($other->number);
    $this->get(route('account.orders.show', $own))->assertOk();
    $this->get(route('account.orders.show', $other))->assertForbidden();
});

it('la policy de pedido distingue personal autorizado y editor', function (string $role, int $status) {
    $user = User::factory()->create();
    $user->assignRole($role);
    $order = Order::factory()->withItems()->create();
    $this->actingAs($user)->get(route('account.orders.show', $order))->assertStatus($status);
})->with([['editor', 403], ['manager', 200], ['admin', 200]]);

it('separa el permiso de acceso VIP de la vigencia de la suscripción', function () {
    $this->seed();
    Carbon::setTestNow('2026-11-02 00:00:00');
    try {
        $user = User::where('email', 'cliente1@fitstore.test')->firstOrFail();
        expect($user->can('vip.access'))->toBeTrue()->and($user->isVip())->toBeFalse();
        $this->actingAs($user)->get(route('account.vip'))->assertOk()->assertSee('no tienes una suscripción VIP vigente');
    } finally {
        Carbon::setTestNow();
    }
});

it('los enlaces de navegación respetan los permisos', function () {
    $user = User::factory()->create();
    $user->assignRole('customer');
    $this->actingAs($user)->get(route('account.dashboard'))->assertOk()
        ->assertDontSee(route('management.users.index'), false)
        ->assertDontSee(route('management.orders.index'), false)
        ->assertDontSee(route('management.catalog.index'), false);
});
