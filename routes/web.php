<?php

use App\Http\Controllers\Commerce\CartController;
use App\Http\Controllers\Commerce\CheckoutController;
use App\Http\Controllers\Commerce\OrderResultController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\Free\FreeResourceController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Learn\ClassController;
use App\Http\Controllers\Learn\LearnController;
use App\Http\Controllers\Learn\SubjectController;
use App\Http\Controllers\Learn\TopicController;
use App\Http\Controllers\Payments\PaymentController;
use App\Http\Controllers\Payments\WebhookController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Shop\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/health', HealthController::class)->name('health');

Route::get('/learn', LearnController::class)->name('learn.index');

Route::get('/free', [FreeResourceController::class, 'index'])->name('free.index');
Route::get('/free/{class}/{subject}/{slug}', [FreeResourceController::class, 'show'])
    ->where('class', 'class-[a-z0-9-]+')
    ->name('free.show');

Route::get('/search', SearchController::class)->name('search');

Route::get('/download/{resource:slug}', DownloadController::class)->name('downloads.show');

Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{type}', [ShopController::class, 'type'])
    ->where('type', 'topic-packs|workbooks|ebooks|holiday-packs|revision-packs|bundles')
    ->name('shop.type');
Route::get('/shop/{class}/{slug}', [ShopController::class, 'show'])
    ->where('class', 'class-[a-z0-9-]+|all-classes')
    ->name('shop.show');
Route::get('/products/{slug}', [ShopController::class, 'alias'])->name('products.alias');

Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
Route::post('/cart/items', [CartController::class, 'addItem'])->name('cart.items.store');
Route::delete('/cart/items/{product}', [CartController::class, 'removeItem'])->name('cart.items.destroy');
Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon.store');
Route::delete('/cart/coupon', [CartController::class, 'removeCoupon'])->name('cart.coupon.destroy');

Route::post('/webhooks/razorpay', WebhookController::class)->name('webhooks.razorpay');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/checkout/payment', [PaymentController::class, 'store'])->name('checkout.payment');
    Route::post('/payments/razorpay/callback', [PaymentController::class, 'callback'])->name('payments.razorpay.callback');
    Route::get('/order/{order}/success', [OrderResultController::class, 'success'])->name('orders.success');
    Route::get('/order/{order}/payment-pending', [OrderResultController::class, 'pending'])->name('orders.payment-pending');
});

// Constrained so this class-landing wildcard cannot swallow /free, /search
// and the other top-level pages above (docs/reference/routes-and-screens.md,
// "URL rules").
Route::get('/{class}', ClassController::class)
    ->where('class', 'class-[a-z0-9-]+')
    ->name('learn.class');

Route::get('/{class}/{subject}', SubjectController::class)
    ->where('class', 'class-[a-z0-9-]+')
    ->name('learn.subject');

Route::get('/{class}/{subject}/{topic}', TopicController::class)
    ->where('class', 'class-[a-z0-9-]+')
    ->name('learn.topic');

require __DIR__.'/account.php';
require __DIR__.'/settings.php';
