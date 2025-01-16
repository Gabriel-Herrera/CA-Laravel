<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DiscountController;


// Rutas públicas
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/productos/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/categoria/{category}', [ProductController::class, 'category'])->name('products.category');

// Rutas de administrador
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/productos', [ProductController::class, 'adminIndex'])->name('admin.products.index');
    Route::get('/productos/crear', [AdminController::class, 'createProduct'])->name('admin.products.create');
    Route::post('/productos', [AdminController::class, 'storeProduct'])->name('admin.products.store');
    Route::get('/productos/{id}/editar', [AdminController::class, 'editProduct'])->name('admin.products.edit');
    Route::put('/productos/{id}', [AdminController::class, 'updateProduct'])->name('admin.products.update');
    Route::delete('/productos/{id}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
    Route::resource('products', ProductController::class)->except(['show']);
    Route::resource('categories', CategoryController::class);
    route::get('/categories',[CategoryController::class,'categoryIndex'])->name('admin.categories.index');
    Route::get('/products/search', [ProductController::class, 'search'])->name('admin.products.search');
});

Route::group(['prefix' => 'admin'], function () {
    Route::get('/discounts', [DiscountController::class, 'index'])->name('admin.discounts.index');
    Route::get('/discounts/create', [DiscountController::class, 'create'])->name('admin.discounts.create');
    Route::post('/discounts', [DiscountController::class, 'store'])->name('admin.discounts.store');
    Route::get('/discounts/{discount}/edit', [DiscountController::class, 'edit'])->name('admin.discounts.edit');
    Route::put('/discounts/{discount}', [DiscountController::class, 'update'])->name('admin.discounts.update');
    Route::delete('/discounts/{discount}', [DiscountController::class, 'destroy'])->name('admin.discounts.destroy');
});

// Rutas del carrito
Route::group(['middleware' => ['web']], function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
});

Route::group(['prefix' => 'cart'], function () {
    // Existing routes...

    Route::post('/apply-discount', [CartController::class, 'applyDiscount'])->name('cart.apply-discount');

    // Rest of the routes...
});

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
});
// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
});
// Nuevas rutas para categorías
Route::resource('categories', CategoryController::class);

// Ruta para búsqueda de productos


require __DIR__.'/auth.php';
