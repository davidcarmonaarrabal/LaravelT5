<?php

use App\Livewire\Category\CategoryComponent;
use App\Livewire\Category\CategoryShow;
use App\Livewire\Home\Inicio;
use App\Livewire\Product\ProductComponent;
use App\Livewire\Product\ProductShow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/inicio', Inicio::class)->name('inicio');

Route::get('/categories', CategoryComponent::class)->name('categories');

Route::get('/categories/{category}', CategoryShow::class)->name('categories.show');

Route::get('/products', ProductComponent::class)->name('products');

Route::get('/products/${product}', ProductShow::class)->name('products.show');