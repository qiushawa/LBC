<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Shop\HomeController;
use App\Http\Controllers\Shop\AuthController;
use App\Http\Controllers\Shop\UserProfileController;
use App\Http\Controllers\Shop\UserOrderController;
use App\Http\Controllers\Shop\UserSettingsController;
use App\Http\Controllers\Shop\ConfigurationController;

Route::domain('shop.qiushawa.studio')->group(function () {

    // 首頁與廣告
    Route::controller(HomeController::class)
        ->name('shop.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/ad/{id}', 'showAd')->name('ad.show');
        });

    // 使用者登入註冊相關
    Route::controller(AuthController::class)->group(function () {
        Route::get('/login', 'showLoginForm')->name('user.login');
        Route::post('/login', 'login')->name('user.login.post');
        Route::get('/register', 'showRegisterForm')->name('user.register');
        Route::post('/register', 'register')->name('user.register.post');
        Route::post('/logout', 'logout')->name('user.logout');
    });

    // 登入後功能區
    Route::middleware('auth')->group(function () {

        // 儀表板（Dashboard）
        Route::get('/dashboard', fn () => view('dashboard'))->name('user.dashboard');

        // 使用者個人資料
        Route::prefix('/profile')->controller(UserProfileController::class)->group(function () {
            Route::get('/', 'show')->name('user.profile');
            Route::post('/', 'update')->name('user.profile.update');
        });

        // 設定
        Route::prefix('/settings')->controller(UserSettingsController::class)->group(function () {
            Route::get('/', 'index')->name('user.settings');
            Route::post('/', 'update')->name('user.settings.update');
        });

        // 訂單
        Route::prefix('/orders')->controller(UserOrderController::class)->group(function () {
            Route::get('/', 'index')->name('user.orders');
            Route::get('/{order_id}', 'show')->name('user.order.show');
        });

        // 客製化電腦配置（Custom Configuration）
        Route::prefix('/configuration')->controller(ConfigurationController::class)->group(function () {
            Route::post('/', 'submitCustomConfiguration')->name('configuration.submit'); // 兼容原路由
            Route::get('/{config_id}', 'showConfiguration')->name('configuration.show');
            Route::patch('/{config_id}/discounts', 'updateDiscounts')->name('configuration.updateDiscounts');
        });

        // 訂單提交（根據配置）
        Route::prefix('/order')->controller(ConfigurationController::class)->group(function () {
            Route::get('/confirm/{config_id}', 'showOrderConfirmation')->name('order.confirm');
            Route::post('/submit/{config_id}', 'submitOrder')->name('order.submit');
        });
    });
});
