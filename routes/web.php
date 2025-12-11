<?php

use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard', ['userId' => Auth::user()->userId]);
    }
    return redirect()->route('login');
});

Route::get('login', [LoginController::class, 'index'])->name('login');
Route::get('admin/create', [LoginController::class, 'admin'])->name('admin.create');
Route::post('admin/store', [LoginController::class, 'storeAdmin'])->name('admin.store');
Route::post('login_proses', [LoginController::class, 'login_proses'])->name('login-proses');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard/{userId}', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/product/{userId}', [ProductController::class, 'index'])->name('product.index');
    Route::get('/product/{userId}/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/product/{userId}', [ProductController::class, 'store'])->name('product.store');
    Route::get('/product/{userId}/{product}', [ProductController::class, 'show'])->name('product.show');
    Route::get('/product/{userId}/{product}/edit', [ProductController::class, 'edit'])->name('product.edit');
    Route::put('/product/{userId}/{product}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('/product/{userId}/{product}', [ProductController::class, 'destroy'])->name('product.destroy');


    Route::get('/kategori/{userId}', [KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/kategori/{userId}/create', [KategoriController::class, 'create'])->name('kategori.create');
    Route::post('/kategori/{userId}', [KategoriController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/{userId}/{kategori}', [KategoriController::class, 'show'])->name('kategori.show');
    Route::get('/kategori/{userId}/{kategori}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('/kategori/{userId}/{kategori}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{userId}/{kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

    Route::get('/transaction/{userId}', [TransactionController::class, 'index'])->name('transaction.index');
    Route::post('/transaction/checkout/{userId}', [TransactionController::class, 'checkout'])->name('transaction.checkout');
    // Route::get('/transaction/invoice/{id}', [TransactionController::class, 'invoice'])->name('transaction.invoice');
    Route::get('/transaction/nota/{id}', [TransactionController::class, 'nota'])->name('transaction.nota'); // Tambahkan ini
    Route::get('/transaction/history/{userId}', [TransactionController::class, 'history'])->name('transaction.history');

    Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{userId}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{userId}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{userId}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    Route::get('/welcome', function () {
        return view('welcome'); })->name('welcome');
});