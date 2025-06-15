<?php

use Illuminate\Support\Facades\Route;
// 首頁與廣告
Route::controller(HomeController::class)
    ->name('shop.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/ad/{id}', 'showAd')->name('ad.show');
    });
Route::middleware('auth')->group(function () {
    // 客製化電腦配置（Custom Configuration）
    Route::prefix('/configuration')
        ->controller(ConfigurationController::class)
        ->group(function () {
            Route::post('/', 'submitCustomConfiguration')
                ->name('configuration.submit');
        });
});
// * api.php
Route::controller(ApiController::class)
    ->name('api.')
    ->group(function () {
        Route::get('/products/category', 'productsByCategory')
            ->name('productsByCategory');
    });
