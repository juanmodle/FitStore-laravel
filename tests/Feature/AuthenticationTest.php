<?php

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed([PermissionSeeder::class, RoleSeeder::class, RolePermissionSeeder::class]);
});

it('permite consultar el inicio y los formularios públicos', function () {
    $this->get(route('home'))->assertOk()->assertSee('FIT');
    $this->get(route('login'))->assertOk()->assertSee('Recordar mi sesión');
    $this->get(route('register'))->assertOk()->assertSee('Repite la contraseña');
});

it('registra un cliente con perfil sin permitir que elija un rol privilegiado', function () {
    $this->get(route('register'));
    $oldSession = session()->getId();
    $this->post(route('register.store'), [
        'name' => 'Cliente nuevo', 'email' => ' NUEVO@FITSTORE.TEST ',
        'password' => 'ClaveSegura123', 'password_confirmation' => 'ClaveSegura123',
        'role' => 'admin', 'roles' => ['admin'], 'points' => 9000,
    ])->assertRedirect(route('account.dashboard'));

    $user = User::where('email', 'nuevo@fitstore.test')->firstOrFail();
    $this->assertAuthenticatedAs($user);
    expect($user->hasRole('customer'))->toBeTrue()
        ->and($user->hasRole('admin'))->toBeFalse()
        ->and($user->profile->points)->toBe(0)
        ->and(Hash::check('ClaveSegura123', $user->password))->toBeTrue()
        ->and(session()->getId())->not->toBe($oldSession);
    $this->get(route('account.dashboard'))->assertOk();
});

it('rechaza registros inválidos sin crear una cuenta', function (array $changes, string $field) {
    $data = array_merge([
        'name' => 'Cliente nuevo', 'email' => 'nuevo@fitstore.test',
        'password' => 'ClaveSegura123', 'password_confirmation' => 'ClaveSegura123',
    ], $changes);
    $this->from(route('register'))->post(route('register.store'), $data)
        ->assertRedirect(route('register'))->assertSessionHasErrors($field);
    $this->assertGuest();
    $this->assertDatabaseCount('users', 0);
    expect(session('_old_input.password'))->toBeNull()
        ->and(session('_old_input.password_confirmation'))->toBeNull();
})->with([
    'sin nombre' => [['name' => ''], 'name'],
    'correo inválido' => [['email' => 'sin-correo'], 'email'],
    'contraseña corta' => [['password' => 'Ab1', 'password_confirmation' => 'Ab1'], 'password'],
    'sin números' => [['password' => 'SoloLetras', 'password_confirmation' => 'SoloLetras'], 'password'],
    'confirmación distinta' => [['password_confirmation' => 'OtraClave123'], 'password'],
    'contraseña demasiado larga' => [['password' => str_repeat('a', 80).'1', 'password_confirmation' => str_repeat('a', 80).'1'], 'password'],
    'longitud de contraseña con acentos' => [['password' => str_repeat('á', 40).'1', 'password_confirmation' => str_repeat('á', 40).'1'], 'password'],
]);

it('no reutiliza el correo de una cuenta existente ni eliminada', function (bool $deleted) {
    $user = User::factory()->create(['email' => 'existente@fitstore.test']);
    if ($deleted) {
        $user->delete();
    }
    $this->post(route('register.store'), [
        'name' => 'Otro cliente', 'email' => 'EXISTENTE@FITSTORE.TEST',
        'password' => 'ClaveSegura123', 'password_confirmation' => 'ClaveSegura123',
    ])->assertSessionHasErrors('email');
    expect(User::withTrashed()->count())->toBe(1);
})->with([false, true]);

it('inicia sesión y redirige a la página privada solicitada', function () {
    $user = User::factory()->create();
    $user->assignRole('customer');
    $this->get(route('account.profile.edit'))->assertRedirect(route('login'));
    $this->post(route('login.store'), [
        'email' => mb_strtoupper($user->email), 'password' => 'password', 'remember' => '1',
    ])->assertRedirect(route('account.profile.edit'));
    $this->assertAuthenticatedAs($user);
    $this->get(route('account.profile.edit'))->assertOk()->assertSee($user->name);
});

it('rechaza credenciales incorrectas y cuentas eliminadas', function (bool $deleted) {
    $user = User::factory()->create();
    if ($deleted) {
        $user->delete();
    }
    $this->post(route('login.store'), [
        'email' => $user->email, 'password' => $deleted ? 'password' : 'incorrecta',
    ])->assertSessionHasErrors('email');
    $this->assertGuest();
})->with([false, true]);

it('limita los intentos repetidos de acceso', function () {
    foreach (range(1, 5) as $attempt) {
        $this->post(route('login.store'), ['email' => 'ausente@fitstore.test', 'password' => 'incorrecta'])->assertRedirect();
    }
    $this->post(route('login.store'), ['email' => 'AUSENTE@FITSTORE.TEST', 'password' => 'incorrecta'])->assertStatus(429);
});

it('impide volver a registro o acceso si ya existe una sesión', function () {
    $user = User::factory()->create();
    $user->assignRole('customer');
    $this->actingAs($user)->get(route('login'))->assertRedirect(route('account.dashboard'));
    $this->get(route('register'))->assertRedirect(route('account.dashboard'));
});

it('cierra sesión e invalida los datos de la sesión anterior', function () {
    $user = User::factory()->create();
    $user->assignRole('customer');
    $this->actingAs($user)->withSession(['dato_privado' => 'anterior'])
        ->post(route('logout'))->assertRedirect(route('home'))->assertSessionMissing('dato_privado');
    $this->assertGuest();
    $this->get(route('account.dashboard'))->assertRedirect(route('login'));
    $this->get('/salir')->assertStatus(405);
});

it('redirige a los invitados fuera de las rutas privadas', function (string $name) {
    $this->get(route($name))->assertRedirect(route('login'));
})->with([
    'account.dashboard', 'account.profile.edit', 'account.password.edit', 'account.orders.index',
    'account.vip', 'management.catalog.index', 'management.orders.index', 'management.users.index',
]);
