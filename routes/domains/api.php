<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::domain('api.qiushawa.studio')->group(function () {

    // 公開 API
    Route::controller(ApiController::class)
        ->name('api.')
        ->group(function () {
            Route::get('/status', 'status')->name('status');
            Route::get('/products/category', 'productsByCategory')->name('productsByCategory');
        });


    // 上線人數查詢
    Route::get('/status/online', [ApiController::class, 'getOnlineUsers'])->name('status.online');

    // 需要登入的 API 路由
    Route::middleware('auth:sanctum')->group(function () {
        // 暫且矇在鼓裡
    });
});
