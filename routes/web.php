<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::get('/productos', [ProductController::class, 'index'])->name('products.index');
Route::get('/productos/{product}', [ProductController::class, 'show'])->name('products.show');

Route::middleware('guest')->group(function () {
    Route::get('/entrar', [AuthController::class, 'login'])->name('login');
    Route::post('/entrar', [AuthController::class, 'authenticate'])->middleware('throttle:login')->name('login.store');
    Route::get('/registro', [AuthController::class, 'register'])->name('register');
    Route::post('/registro', [AuthController::class, 'store'])->middleware('throttle:registration')->name('register.store');
});

Route::post('/salir', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'auth.session', 'can:account.access'])->group(function () {
    Route::prefix('mi-cuenta')->name('account.')->group(function () {
        Route::view('/', 'account.dashboard')->name('dashboard');
        Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/perfil', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/password', [ProfileController::class, 'editPassword'])->name('password.edit');
        Route::put('/password', [ProfileController::class, 'password'])->name('password.update');
        Route::get('/pedidos', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/pedidos/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::view('/vip', 'account.vip')->middleware('can:vip.access')->name('vip');
    });

    Route::prefix('gestion')->name('management.')->group(function () {
        Route::get('/catalogo', [ProductController::class, 'manage'])->middleware('can:catalog.view-drafts')->name('catalog.index');
        Route::get('/pedidos', [OrderController::class, 'manage'])->middleware('can:orders.view-all')->name('orders.index');
        Route::get('/usuarios', [UserController::class, 'index'])->middleware('can:users.view')->name('users.index');
    });
});
