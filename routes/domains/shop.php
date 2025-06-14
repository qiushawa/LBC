<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Shop\HomeController;
use App\Http\Controllers\Shop\AuthController;
use App\Http\Controllers\Shop\UserProfileController;
use App\Http\Controllers\Shop\UserOrderController;
use App\Http\Controllers\Shop\UserSettingsController;
use App\Http\Controllers\Shop\ConfigurationController;


Route::domain('shop.qiushawa.studio')->group(function () {
    Route::controller(HomeController::class)
        ->name('shop.')
        ->group(function () {
            Route::get('/', 'index')->name('index');

            Route::get('/ad/{id}', 'showAd')->name('ad.show');
        });

    // authentication routes
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('user.login');
    Route::post('/login', [AuthController::class, 'login'])->name('user.login.post');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('user.register');
    Route::post('/register', [AuthController::class, 'register'])->name('user.register.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('user.logout');

    // 需要身份驗證的路由
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard'); // Create a dashboard.blade.php with an overview
        })->name('user.dashboard')->middleware('auth');
        // Profile Routes
        Route::get('/profile', [UserProfileController::class, 'show'])->name('user.profile');
        Route::post('/profile', [UserProfileController::class, 'update'])->name('user.profile.update');

        // Settings Routes
        Route::get('/settings', [UserSettingsController::class, 'index'])->name('user.settings');
        Route::post('/settings', [UserSettingsController::class, 'update'])->name('user.settings.update'); // Added missing route

        // Order Routes
        Route::get('/orders', [UserOrderController::class, 'index'])->name('user.orders');
        Route::get('/orders/{order_id}', [UserOrderController::class, 'show'])->name('user.order.show');



Route::post('/custom-configuration', [ConfigurationController::class, 'submitCustomConfiguration'])->name('configuration.submit');
    Route::get('/configuration/{config_id}', [ConfigurationController::class, 'showConfiguration'])->name('configuration.show');
    Route::patch('/configuration/{config_id}/discounts', [ConfigurationController::class, 'updateDiscounts'])->name('configuration.updateDiscounts');
    Route::get('/order/confirm/{config_id}', [ConfigurationController::class, 'showOrderConfirmation'])->name('order.confirm');
    Route::post('/order/submit/{config_id}', [ConfigurationController::class, 'submitOrder'])->name('order.submit');
    });
});
