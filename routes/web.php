<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\UserDashboardController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Home route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Admin route (protected)
Route::middleware(['admin'])->group(function () {
    Route::get('/admin', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('products', ProductController::class)->except(['show']);
    Route::resource('coupons', CouponController::class)->except(['show', 'edit', 'update']);
    Route::post('coupons/{coupon}/toggle', [CouponController::class, 'toggleStatus'])->name('coupons.toggle');
    Route::resource('categories', CategoryController::class);
});

// Public product routes
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Auth routes
Auth::routes();

// Product routes
Route::resource('categories', CategoryController::class)->only(['store']);

// Auth-protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    // Cart routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart/remove/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
    
    // Coupon routes
    Route::post('/cart/apply-coupon', [CouponController::class, 'apply'])->name('coupons.apply');
    Route::post('/cart/remove-coupon', [CouponController::class, 'remove'])->name('coupons.remove');

    // Order routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/order', [OrderController::class, 'store'])->name('order.store');

    // Wishlist routes
    Route::post('wishlist/{product}', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('wishlist/{product}', [WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::get('wishlist', [WishlistController::class, 'index'])->name('wishlist.index');

    // Review route
    Route::post('reviews/{product}', [ReviewController::class, 'store'])->name('reviews.store');
});
