<?php

use App\Models\CustomerProfile;
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

it('completa el flujo de acceso y actualización validada del perfil', function () {
    $user = User::factory()->create();
    $user->assignRole('customer');
    $this->post(route('login.store'), ['email' => $user->email, 'password' => 'password'])->assertRedirect(route('account.dashboard'));
    $this->get(route('account.profile.edit'))->assertOk()->assertSee('Idioma preferido');
    $this->patch(route('account.profile.update'), [
        'name' => 'Nombre actualizado', 'email' => $user->email, 'phone' => '+34 600 123 456', 'locale' => 'en',
        'role' => 'admin', 'points' => 9000,
    ])->assertRedirect(route('account.profile.edit'))->assertSessionHasNoErrors();

    expect($user->fresh()->name)->toBe('Nombre actualizado')
        ->and($user->fresh()->locale)->toBe('en')
        ->and($user->fresh()->profile->phone)->toBe('+34 600 123 456')
        ->and($user->fresh()->profile->points)->toBe(0)
        ->and($user->fresh()->hasRole('admin'))->toBeFalse();
});

it('el perfil no permite cambiar la identidad ni los puntos de otro cliente', function () {
    $user = User::factory()->create();
    $user->assignRole('customer');
    CustomerProfile::factory()->for($user)->create(['points' => 50]);
    $other = User::factory()->create();
    $this->actingAs($user)->patch(route('account.profile.update'), [
        'name' => 'Mi nombre', 'email' => $user->email, 'phone' => '600123456', 'locale' => 'es',
        'user_id' => $other->id, 'points' => 999, 'permissions' => ['users.view'],
    ])->assertSessionHasNoErrors();
    expect($other->fresh()->name)->toBe($other->name)
        ->and($user->fresh()->profile->points)->toBe(50)
        ->and($user->fresh()->can('users.view'))->toBeFalse();
});

it('valida los campos del perfil antes de guardar', function (array $changes, string $field) {
    $user = User::factory()->create();
    $user->assignRole('customer');
    $data = array_merge(['name' => 'Nombre', 'email' => $user->email, 'phone' => '600123456', 'locale' => 'es'], $changes);
    $this->actingAs($user)->from(route('account.profile.edit'))
        ->patch(route('account.profile.update'), $data)->assertRedirect(route('account.profile.edit'))->assertSessionHasErrors($field);
    expect($user->fresh()->name)->toBe($user->name);
})->with([
    [['name' => ''], 'name'], [['email' => 'incorrecto'], 'email'],
    [['phone' => 'texto sin teléfono'], 'phone'], [['locale' => 'xx'], 'locale'],
    [['email' => 'otro@fitstore.test'], 'current_password'],
    [['email' => 'otro@fitstore.test', 'current_password' => 'incorrecta'], 'current_password'],
]);

it('permite cambiar el correo con contraseña y reinicia su verificación', function () {
    $user = User::factory()->create();
    $user->assignRole('customer');
    $this->actingAs($user)->patch(route('account.profile.update'), [
        'name' => $user->name, 'email' => ' NUEVO@FITSTORE.TEST ', 'locale' => 'es', 'current_password' => 'password',
    ])->assertSessionHasNoErrors();

    expect($user->fresh()->email)->toBe('nuevo@fitstore.test')->and($user->fresh()->email_verified_at)->toBeNull();
});

it('impide cambiar el correo por el de otra cuenta', function () {
    $user = User::factory()->create();
    $user->assignRole('customer');
    $other = User::factory()->create();
    $this->actingAs($user)->patch(route('account.profile.update'), [
        'name' => $user->name, 'email' => $other->email, 'locale' => 'es', 'current_password' => 'password',
    ])->assertSessionHasErrors('email');
});

it('cambia la contraseña comprobando la anterior y mantiene la sesión actual', function () {
    $user = User::factory()->create();
    $user->assignRole('customer');
    $this->actingAs($user)->get(route('account.password.edit'))->assertOk();
    $this->put(route('account.password.update'), [
        'current_password' => 'password', 'password' => 'NuevaClave123', 'password_confirmation' => 'NuevaClave123',
    ])->assertRedirect(route('account.profile.edit'))->assertSessionHasNoErrors();
    expect(Hash::check('NuevaClave123', $user->fresh()->password))->toBeTrue()
        ->and(Hash::check('password', $user->fresh()->password))->toBeFalse();
    $this->assertAuthenticated();
    $this->get(route('account.dashboard'))->assertOk();
});

it('rechaza un cambio de contraseña inválido', function (array $changes, string $field) {
    $user = User::factory()->create();
    $user->assignRole('customer');
    $data = array_merge([
        'current_password' => 'password', 'password' => 'NuevaClave123', 'password_confirmation' => 'NuevaClave123',
    ], $changes);
    $this->actingAs($user)->put(route('account.password.update'), $data)->assertSessionHasErrors($field);
    expect(Hash::check('password', $user->fresh()->password))->toBeTrue();
})->with([
    [['current_password' => 'incorrecta'], 'current_password'],
    [['password_confirmation' => 'diferente'], 'password'],
    [['password' => 'Ab1', 'password_confirmation' => 'Ab1'], 'password'],
]);

it('el listado de usuarios filtra por rol fechas y verificación con componentes reutilizados', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $included = User::factory()->create(['name' => 'Incluido en filtro', 'created_at' => '2026-10-20 12:00:00']);
    $included->assignRole('customer');
    $unverified = User::factory()->unverified()->create(['name' => 'Sin verificar', 'created_at' => '2026-10-20 12:00:00']);
    $unverified->assignRole('customer');
    $outside = User::factory()->create(['name' => 'Fuera de fechas', 'created_at' => '2025-01-01 12:00:00']);
    $outside->assignRole('customer');

    $this->actingAs($admin)->get(route('management.users.index', [
        'role' => 'customer', 'registered_from' => '2026-10-01', 'registered_until' => '2026-10-31', 'verified_only' => 1,
    ]))->assertOk()->assertSee($included->name)->assertDontSee($unverified->name)->assertDontSee($outside->name)
        ->assertSee('type="date"', false)->assertSee('type="checkbox"', false)->assertSee('<select', false);
});

it('rechaza filtros inválidos antes de consultar usuarios', function (array $query, string $field) {
    $user = User::factory()->create();
    $user->assignRole('admin');
    $this->actingAs($user)->from(route('management.users.index'))->get(route('management.users.index', $query))
        ->assertRedirect(route('management.users.index'))->assertSessionHasErrors($field);
})->with([
    [['role' => 'inexistente'], 'role'],
    [['registered_from' => 'no-es-fecha'], 'registered_from'],
    [['registered_from' => '2026-11-01', 'registered_until' => '2026-10-01'], 'registered_until'],
    [['verified_only' => 'incorrecto'], 'verified_only'],
]);
