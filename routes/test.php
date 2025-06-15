<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Shop\HomeController;
use App\Http\Controllers\Shop\ConfigurationController;

// 首頁與廣告
Route::controller(HomeController::class)
    ->name('shop.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/ad/{id}', 'showAd')->name('ad.show');
    });
Route::middleware('auth')->group(function () {
    // 訂單提交（根據配置）
    Route::prefix('/order')
        ->controller(ConfigurationController::class)
        ->group(function () {
            Route::get('/confirm/{config_id}', 'showOrderConfirmation')->name('order.confirm');
            Route::post('/submit/{config_id}', 'submitOrder')->name('order.submit');
    });
});
// * api.php
Route::prefix('/configuration')->controller(ConfigurationController::class)->group(function () {
    Route::post('/', 'submitCustomConfiguration')->name('configuration.submit'); // 兼容原路由
    Route::get('/{config_id}', 'showConfiguration')->name('configuration.show');
    Route::patch('/{config_id}/discounts', 'updateDiscounts')->name('configuration.updateDiscounts');
});
